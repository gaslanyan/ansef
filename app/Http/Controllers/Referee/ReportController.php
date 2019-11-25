<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Person;
use App\Models\Proposal;
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
//        try {
        $r_id = getUserIdByRole('referee');
        $referee_ids = Proposal::get()
            ->pluck('proposal_referees', 'id')->filter();
        $pid = [];
//        dump($referee_ids);
        //TODO mysql 5.7 json
        foreach ($referee_ids as $key => $index) {
            if (!empty($index)) {
                $elements = json_decode($index, true);

                if (in_array($r_id, (array)$elements)) {

                    if (in_array($r_id, (array)$elements))
                        $pid[] = $key;
                }
            }
        }


        $reports = RefereeReport::with(['proposal'
        => function ($query) {
                $query->select('id', 'title', 'competition_id');
            },
            'proposal.competition' => function ($query) {
                $query->select('id', 'title');
            }])
            ->whereIn('proposal_id', $pid)
            ->where('state', $state)
            ->where('referee_id', $r_id)
            ->get();

//        echo "<pre>";
//        dd($reports);
        return view('referee.report.index', compact('reports', 'state'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('referee/' . $state)->with('error', getMessage("wrong"));
//        }
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
        try {

            $report = RefereeReport::with(['proposal',
                'proposal.competition'])
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

            return view('referee.report.show', compact('report', 'cats'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/index')->with('error', getMessage("wrong"));
        }
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

            return view('referee.report.edit', compact('report', 'scoreTypes'));
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

//        try {
        $report = RefereeReport::find($id);
        if (!isset($request->state_r)) {
            $scores = $request->name;
            $overall_scope = 0;

            foreach ($scores as $index => $score) {
                $weight = ScoreType::select('weight')->where('id', $index)->first();

                $overall_scope += ($score*$weight->weight)/100;
            }
            $report->public_comment = $request->public_comment;
            $report->private_comment = $request->private_comment;
            $report->dur_date = $request->dur_date;
            $report->overall_scope = round($overall_scope / count($scores), 3);
//            $report->scores = json_encode($scores);
            if (isset($request->state_c))
                $report->state = $request->state_c;
            else
                $report->state = $request->state_p;
        } else {
            $report->state = $request->state_r;
            $this->sendEmail($report->proposal_id);
        }
        $report->save();

        foreach ($scores as $index => $score) {
//            $scs = Score::where('score_type_id', $index)->first();
            $scs = new Score();
            $scs->score_type_id = $index;
            $scs->value = $score;
            $scs->report_id = $report->id;
            $scs->save();
        }
        return redirect()->back()->with('success', getMessage("success"));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('referee/reports')->with('error', getMessage("wrong"));
//        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }

    public
    function generatePDF($id)
    {
        $report = RefereeReport::with(['proposal',
            'proposal.competition'])
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

        $data = ['title' => $report->proposal->title,
            'report' => $report,
            'cats' => $cats];

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

        Mail::send(['text' => 'referee.report.mail'],
            $data, function ($message) use ($title, $email, $f_name) {
                $message->to(env('MAIL_USERNAME'), 'Ansef')
                    ->subject('Reject ' . $title->title . ' proposal');
                $message->from($email->email, $f_name);
            });
//
//        } catch (\Exception $exception) {
//            logger()->error($exception);)
//            return redirect()->back()->with('error', getMessage("wrong"));
//        }
    }
}
