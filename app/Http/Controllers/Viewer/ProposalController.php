<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Message;
use App\Models\Proposal;
use App\Models\Recommendation;
use App\Models\RefereeReport;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade as PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Storage;


class ProposalController extends Controller
{
    public function index()
    {
    }

    public function getProposalByCompByID(Request $request)
    {
    }

    public function downloadfirstreport(Request $request)
    {
        $pid = $request['id'];
        $pr = Proposal::find($pid);
        $propreports = $pr->propreports;
        foreach ($propreports as $propreport) {
            if ($propreport->due_date == $pr->competition->first_report) {
                if (Storage::exists(ppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"))
                    return response()->download(storage_path(proppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"));
            } else {
                return response()->json(new \stdClass());
            }
        }
    }

    public function downloadsecondreport(Request $request)
    {
        $pid = $request['id'];
        $pr = Proposal::find($pid);
        $propreports = $pr->propreports;
        foreach ($propreports as $propreport) {
            if ($propreport->due_date == $pr->competition->second_report) {
                if (Storage::exists(ppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"))
                    return response()->download(storage_path(proppath($propreport->proposal_id) . "/report-" . $propreport->id . ".pdf"));
            }
        }
        return response()->json(new \stdClass());
    }

    public function display(Request $request)
    {
        $pid = $request['id'];
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
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

        return view('viewer.proposal.show', compact(
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
            'recommendations',
            'reports'
        ));
    }

    public function awardslist($cid)
    {
        try {
            $competitions = Competition::select('id', 'title')
                ->orderBy('submission_end_date', 'desc')
                ->get()->toArray();

            $referee = Role::where('name', '=', 'referee')->first();
            $admin = Role::where('name', '=', 'admin')->first();
            $superadmin = Role::where('name', '=', 'superadmin')->first();

            $referees = $referee->persons;
            $adminpersons = $admin->persons;
            $superadminpersons = $superadmin->persons;
            $admins = $adminpersons->concat($superadminpersons)->all();
            $messages = Message::all();
            $enumvals = getEnumValues('proposals', 'state');

            return view('viewer.proposal.awardsindex', compact('referees', 'admins', 'messages', 'enumvals', 'competitions', 'cid'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('viewer/proposal/list/1')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function listawards($cid, Request $request)
    {
        // ini_set('memory_limit', '384M');
        $d['data'] = [];

        if ($cid == -1) {
            $proposals = Proposal::whereIn('state', ['awarded', 'approved 1', 'approved 2'])
                ->get()->sortBy('id');
        } else {
            $proposals = Proposal::where('competition_id', '=', $cid)
                ->whereIn('state', ['awarded', 'approved 1', 'approved 2'])
                ->orderBy('id', 'asc')
                ->get();
        }
        foreach ($proposals as $index => $pr) {
            $d['data'][$index]['id'] = $pr->id;
            $d['data'][$index]['tag'] = getProposalTag($pr->id);
            $d['data'][$index]['title'] = truncate($pr->title, 25);
            $d['data'][$index]['state'] = ($pr->state);
            $d['data'][$index]['score'] = strval(round($pr->overall_score)) . "%";
            $pi = $pr->pi();
            $d['data'][$index]['pi'] = !empty($pi) ? truncate($pi->last_name, 7) . " " . $pi->first_name : 'No PI';
            $propreports = $pr->propreports;
            $d['data'][$index]['first'] = false;
            $d['data'][$index]['second'] = false;
            foreach ($propreports as $propreport) {
                if ($propreport->due_date == $pr->competition->first_report) {
                    $d['data'][$index]['first'] = true;
                } else if ($propreport->due_date == $pr->competition->second_report) {
                    $d['data'][$index]['second'] = true;
                } else {
                }
            }
        }

        return Response::json($d);
    }

    public function create(Request $request)
    {
    }


    public function store(Request $request)
    {

    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
    }

    public function downloadPDF($id){

    }

    public function destroy($id)
    {
    }

    public function generatePDF($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::find($id);
        $pid = $proposal->id;
        $recommendations = Recommendation::where('proposal_id', '=', $pid)->get();
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
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

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
            'recommendations' => $recommendations,
            'reports' => $reports
        ];

        $pdf = PDF::loadView('viewer.proposal.pdf', $data);
        $pdf->save(storage_path(proppath($pid) . '/combined.pdf'));

        $pdfMerge = PDFMerger::init();
        if(Storage::exists(ppath($pid) . '/combined.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/combined.pdf'), 'all');
        if(Storage::exists(ppath($pid) . '/document.pdf'))
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/document.pdf'), 'all');
        foreach ($recommendations as $r) {
            if (Storage::exists(ppath($pid) . '/letter-' . $r->id . '.pdf'))
                $pdfMerge->addPDF(storage_path(proppath($pid) . '/letter-' . $r->id . '.pdf'), 'all');
        }
        $pdfMerge->merge();

        $pdfMerge->save(storage_path(proppath($pid) . '/download.pdf'), 'download');

        Storage::delete('proposals/prop-' . $pid . '/combined.pdf');
    }
}
