<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\Score;
use App\Models\ScoreType;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    public function state($state)
    {
        try {
            $user_id = getUserID();
            $r_id = getPersonIdByRole('referee');
            $reports = RefereeReport::where('referee_id', '=', $r_id)
                ->where('state', '=', $state)
                ->orderBy('due_date', 'desc')
                ->get();

            return view('referee.report.index', compact('reports', 'state'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/' . $state)->with('error', messageFromTemplate("wrong"));
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $user_id = getUserID();
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
        $recommendations = Recommendation::where('proposal_id', '=', $pid)->get();

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
            'budget',
            'recommendations'
        ));
    }

    public function edit($id)
    {
        $user_id = getUserID();
        try {
            $report = RefereeReport::with('proposal')->find($id);
            $scoreTypes = ScoreType::with('score')->where('competition_id', $report->proposal->competition_id)->get();
            $scores = [];
            foreach ($scoreTypes as $s) {
                $scores[$s->id] = Score::updateOrCreate([
                    'score_type_id' => $s->id,
                    'report_id' => $id
                ], []);
                $scores[$s->id]->save();
            }
            $overall = overallScore($id);
            return view('referee.report.edit', compact('report', 'scoreTypes', 'scores', 'overall'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/edit')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        try {
            $report = RefereeReport::find($id);
            $scores = $request->name;

            $report->public_comment = $request->public_comment;
            $report->private_comment = $request->private_comment;
            $report->state = $request->submitaction;
            foreach ($scores as $index => $score) {
                $s = Score::where('score_type_id', '=', $index)
                    ->where('report_id', '=', $id)
                    ->first();
                $s->value = $score;
                $s->save();
            }
            $report->overall_score = overallScore($id);
            $report->save();
            updateProposalState($report->proposal_id);
            updateProposalScore($report->proposal_id);
            if ($report->state == 'rejected') {
                return redirect()->action('Referee\SendEmailController@showRejectedEmail', $report->id);
            } else return redirect()->action('Referee\ReportController@state', 'in-progress');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        //
    }

    public function generatePDF($id)
    {
        $user_id = getUserID();
        $report = RefereeReport::find($id);
        $pid = $report->proposal_id;
        $recommendations = Recommendation::where('proposal_id', '=', $pid)->get();
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

        $data = [
            'id' => $id,
            'pid' => $pid,
            'proposal' => $proposal,
            'institution' => $institution,
            'competition' => $competition,
            'persons' => $persons,
            'additional' => $additional,
            'categories' => $categories,
            'proposal' => $proposal,
            'cat_parent' => $cat_parent,
            'cat_sub' => $cat_sub,
            'cat_sec_parent' => $cat_sec_parent,
            'cat_sec_sub' => $cat_sec_sub,
            'pi' => $pi,
            'budget_items' => $budget_items,
            'budget' => $budget,
            'recommendations' => $recommendations
        ];

        $pdf = PDF::loadView('referee.report.pdf', $data);
        $pdf->save(storage_path(proppath($pid) . '/combined.pdf'));

        $pdfMerge = PDFMerger::init();
        if (Storage::exists(ppath($pid) . '/combined.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/combined.pdf'), 'all');
        if (Storage::exists(ppath($pid) . '/document.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/document.pdf'), 'all');
        foreach ($recommendations as $r) {
            if (Storage::exists(ppath($pid) . '/letter-' . $r->id . '.pdf'))
                $pdfMerge->addPDF(storage_path(proppath($pid) . '/letter-' . $r->id . '.pdf'), 'all');
        }
        $pdfMerge->merge();

        $pdfMerge->save(storage_path(proppath($pid) . '/download.pdf'), 'download');

        Storage::delete('proposals/prop-' . $pid . '/combined.pdf');
    }

    public function sendEmail($id)
    {
        $user_id = getUserID();
        //        try {
        $title = Proposal::select('title')->where('id', $id)->first();
        $person_id = getPersonIdByRole('referee');
        $full_name = Person::select('first_name', 'last_name')->where('id', $person_id)->first();
        $email = User::select('email')->where('id', Session::get('u_id'))->first();
        if (!empty($full_name->first_name) && !empty($full_name->first_name))
            $f_name = $full_name->first_name . " " . $full_name->last_name;
        else
            $f_name = 'Referee by ' . $email->email;
        $data = ['name' => messageFromTemplate('reject') . " " . $title->title];

        Mail::send(
            ['text' => 'referee.report.mail'],
            $data,
            function ($message) use ($title, $email, $f_name) {
                $message->to(env('MAIL_USERNAME'), 'Ansef')
                    ->subject('Reject ' . $title->title . ' proposal');
                $message->from($email->email, $f_name);
            }
        );
    }
}
