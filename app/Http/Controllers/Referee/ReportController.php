<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ProposalReports;
use App\Models\RefereeReport;
use App\Models\Score;
use App\Models\ScoreType;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;


class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function state($state)
    {
        try {
            $r_id = getUserIdByRole('referee');
            $reports = RefereeReport::where('referee_id', '=', $r_id)
                ->where('state', '=', $state)
                ->orderBy('due_date', 'asc')
                ->get();

            return view('referee.report.index', compact('reports', 'state'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/' . $state)->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report = RefereeReport::find($id);
        $pid = $report->proposal_id;
        $proposal = Proposal::find($pid);
        $institution = $proposal->institution();
        $competition = $proposal->competition;
        $persons = $proposal->persons()->get()->sortBy('last_name');
        $additional = json_decode($competition->additional);
        $categories = json_decode($proposal->categories);
        $cat_parent = Category::with('children')->where('id', $categories->parent)->get()->first();
        $cat_sub = Category::with('children')->where('id', $categories->sub)->get()->first();;
        $cat_sec_parent = property_exists($categories, 'sec_parent') ? Category::with('children')->where('id', $categories->sec_parent)->get()->first() : null;
        $cat_sec_sub = property_exists($categories, 'sec_sub') ? Category::with('children')->where('id', $categories->sec_sub)->get()->first() : null;
        $pi = $proposal->persons()->where('subtype', '=', 'PI')->first();
        $budget_items = $proposal->budgetitems()->get();
        $budget = $proposal->budget();

        return view('referee.report.show', compact(
            'id',
            'pid',
            'proposal',
            'institution',
            'competition',
            'persons',
            'additional',
            'categories',
            'proposal',
            'cat_parent',
            'cat_sub',
            'cat_sec_parent',
            'cat_sec_sub',
            'pi',
            'budget_items',
            'budget'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $report = RefereeReport::with('proposal')->find($id);
            $scoreTypes = ScoreType::with('score')->where('competition_id', $report->proposal->competition_id)->get();
            $scores = [];
            foreach ($scoreTypes as $s) {
                $scores[$s->id] = Score::firstOrCreate([
                    'score_type_id' => $s->id,
                    'report_id' => $id
                ], [
                    'value' => 0
                ]);
            }
            $overall = overallScore($id);
            return view('referee.report.edit', compact('report', 'scoreTypes', 'scores', 'overall'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/edit')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $report = RefereeReport::find($id);
            $scores = $request->name;

            $report->public_comment = $request->public_comment;
            $report->private_comment = $request->private_comment;
            $report->state = $request->submitaction;
            $report->overall_score = overallScore($id);
            $report->save();
            foreach ($scores as $index => $score) {
                $s = Score::where('score_type_id', '=', $index)
                    ->where('report_id', '=', $id)
                    ->first();
                $s->value = $score;
                $s->save();
            }
            return redirect()->action('Referee\ReportController@state', 'in-progress');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', getMessage("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        //
    }

    public function generatePDF($id)
    {
        // $pdf = new \LynX39\LaraPdfMerger\PdfManage;

        // $pdf->addPDF(public_path('/upload/1547633948.pdf'), 'all');
        // $pdf->addPDF(public_path('/upload/test.pdf'), 'all');

        // $pdf->merge('file', public_path('/upload/created.pdf'), 'P');

        $report = RefereeReport::with([
            'proposal',
            'proposal.competition'
        ])
            ->where('id', $id)
            ->first();

        $categories = json_decode($report->proposal->categories, true);

        $cats = [];
        foreach ($categories as $index => $category) {
            if ($category != 0) {
                $cat = Category::with('children')->where('id', $category)->get()->first();

                if (empty($cat->parent_id))
                    $cats[$cat->id]['parent'] = $cat->title;
                else {
                    if (in_array($cat->parent_id, $categories))
                        $cats[$cat->parent_id]['sub'] = $cat->title;
                }
            }
        }

        $data = [
            'title' => $report->proposal->title,
            'report' => $report,
            'cats' => $cats
        ];

        $pdf = PDF::loadView('referee.report.pdf', $data);

        return $pdf->download($report->proposal->title . '.pdf');
    }

    public function sendEmail($id)
    {
        //        try {
        $title = Proposal::select('title')->where('id', $id)->first();
        $person_id = getUserIdByRole('referee');
        $full_name = Person::select('first_name', 'last_name')->where('id', $person_id)->first();
        $email = User::select('email')->where('id', Session::get('u_id'))->first();
        if (!empty($full_name->first_name) && !empty($full_name->first_name))
            $f_name = $full_name->first_name . " " . $full_name->last_name;
        else
            $f_name = 'Referee by ' . $email->email;
        $data = ['name' => getMessage('reject') . " " . $title->title];

        Mail::send(
            ['text' => 'referee.report.mail'],
            $data,
            function ($message) use ($title, $email, $f_name) {
                $message->to(env('MAIL_USERNAME'), 'Ansef')
                    ->subject('Reject ' . $title->title . ' proposal');
                $message->from($email->email, $f_name);
            }
        );
        //
        //        } catch (\Exception $exception) {
        //            logger()->error($exception);)
        //            return redirect()->back()->with('error', getMessage("wrong"));
        //        }
    }
}
