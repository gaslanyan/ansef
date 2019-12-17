<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Message;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReports;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\Role;
use App\Models\Score;
use App\Models\ScoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade as PDF;
use LynX39\LaraPdfMerger\Facades\PdfMerger;
use Illuminate\Support\Facades\Storage;

class ProposalController extends Controller
{
    public function list($cid)
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

            return view('admin.proposal.index', compact('referees', 'admins', 'messages', 'enumvals', 'competitions', 'cid'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal/list/1')->with('error', getMessage("wrong"));
        }
    }

    public function index() {

    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $title = Proposal::select('title')->where('id', Session::get('p_id'))->first();
            return view("admin.proposal.create", compact('title'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    public function show($id) {

    }
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
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
        $recommendations = Recommendations::where('proposal_id', '=', $pid)->get();
        $reports = RefereeReport::where('proposal_id', '=', $pid)->get();

        return view('admin.proposal.show', compact(
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

    public function edit($id)
    {

    }

    public function update(Request $request, $id)
    {
        try {

            $v = Validator::make($request->all(), [
                'comment' => 'required|max:1024'
            ]);
            if (!$v->fails()) {
                $proposal = Proposal::find($id);
                $proposal->comment = $request->comment;
                $proposal->save();

                return redirect('admin/proposal/' . $id)->with('success', getMessage("success"));
            } else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', getMessage("wrong"));
//        }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($p_id)
    {
        DB::beginTransaction();
        try {
            BudgetItem::where('proposal_id', $p_id)->delete();
            ProposalInstitution::where('proposal_id', $p_id)->delete();
            ProposalReports::where('proposal_id', $p_id)->delete();
            Recommendations::where('proposal_id', $p_id)->delete();
            RefereeReport::where('proposal_id', $p_id)->delete();

            $file_path = storage_path('proposal/prop-' . $p_id);
            if (is_dir($file_path))
                File::deleteDirectory($file_path);
            Proposal::find($p_id)->delete();
            DB::commit();
            return redirect('admin/proposal')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', getMessage('wrong'));
        }
    }

    public function listproposals($cid, Request $request)
    {
        ini_set('memory_limit', '384M');
        $d['data'] = [];

        if ($cid == -1) {
            $proposals = Proposal::all()
                ->sortBy('id');
        } else {
            $proposals = Proposal::where('competition_id', '=', $cid)
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
            $d['data'][$index]['pi'] = !empty($pi) ? truncate($pi->last_name,7) . " " . $pi->first_name : 'No PI';
            $refs = $pr->refereesasstring();
            $d['data'][$index]['refs'] = !empty($refs) ? $refs : '';
            $a = $pr->admin()->first();
            $d['data'][$index]['admin'] = !empty($a) ? substr($a->last_name,0,4).'.' : 'None';
        }

        return Response::json($d);
    }

    public function checkProposal(Request $request) {
        DB::beginTransaction();
        try {
            $proposal_ids = $request->id;
            foreach ($proposal_ids as $index => $p_id) {
                $r = checkproposal($p_id);
                $messages =  $r['messages'];
                $submittedrecs = $r['submittedrecs'];
                $p = Proposal::find($p_id);

                if(count($messages) > 0 || count($submittedrecs) < $p->competition->recommendations) {
                    $p->state = "disqualified";
                    $p->save();
                }
                else {
                    updateProposalState($p->id);
                }
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Error while checking'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        DB::commit();
        return response()->json($response);
    }

    public function deleteProposal(Request $request)
    {
        DB::beginTransaction();
        try {
            //        if (isset($request->_token)) {
            $proposal_ids = $request->id;
            foreach ($proposal_ids as $index => $p_id) {
                BudgetItem::where('proposal_id', $p_id)->delete();
                ProposalInstitution::where('proposal_id', $p_id)->delete();
                ProposalReports::where('proposal_id', $p_id)->delete();
                Recommendations::where('proposal_id', $p_id)->delete();
                RefereeReport::where('proposal_id', $p_id)->delete();
                $file_path = storage_path('proposal/prop-' . $p_id);
                if (is_dir($file_path))
                    File::deleteDirectory($file_path);
                Proposal::find($p_id)->delete();
            }
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        //        }
        DB::commit();
        return response()->json($response);
    }

    public function changeState(Request $request)
    {
        try {
            $IDs = json_decode($request->ids);
            foreach ($IDs as $index => $ID) {
                Proposal::where('id', $ID)
                    ->update(['state' => $request->state]);
            }
            $response = [
                'success' => true,
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        return response()->json($response);
        exit();
    }

    public function sendEmail(Request $request)
    {
        $IDs = json_decode($request->ids);

        $message = Message::where('id', '=', $request->t_id)->first();
        $objSend = new \stdClass();
        $objSend->message = $message->text;
        $objSend->sender = 'Ansef';
        $objSend->receiver = 'collages';

        $items = [];
        // foreach ($pi_json as $index => $json) {
        //     $email = Person::with('user')->where('id', $pi->person_pi_id)->first();
        //     //             $email->user->email;
        //     Mail::to($email->user->email)->send(new \App\Mail\Invitation($objSend));
        //     return response()->json('ok');
        // }
    }

    public function addUsers(Request $request)
    {
        if (isset($request->_token)) {
            $p_ids = json_decode($request->p_ids);
            $u_ids = json_decode($request->u_ids);
            foreach ($u_ids as $index => $item) {
                $u_id[$index] = $item;
            }

            $response = [];
            $scors = [];

            $flag = true;
            foreach ($p_ids as $pid) {
                if(!$flag) break;
                $proposal = Proposal::find($pid);
                foreach ($u_ids as $uid) {
                    if($request->type == "admin") {
                        $proposal->proposal_admin = $uid;
                    }
                    else if ($request->type == "referee") {
                        $report = new RefereeReport();
                        $report->proposal_id = $pid;
                        $report->referee_id = $uid;
                        $report->competition_id = $proposal->competition->id;
                        $report->overall_score = 0;
                        $report->private_comment = "";
                        $report->public_comment = "";
                        $report->state = "in-progress";
                        $report->due_date = date('Y-m-d', strtotime('+3 months'));
                        $flag = $flag & $report->save();
                        if (!$flag) break;
                        $proposal->state = 'in-review';
                    }
                    else {

                    }
                }
                $flag = $flag & $proposal->save();
            }

            $response = [
                'success' => true
            ];
            if (!$flag) {
                $response = [
                    'success' => false,
                    'error' => 'Cannot make assignment'
                ];
            }
            return response()->json($response);
        }
    }

    public function generatePDF($id)
    {
        $user_id = getUserID();
        $proposal = Proposal::find($id);
        $pid = $proposal->id;
        $recommendations = Recommendations::where('proposal_id', '=', $pid)->get();
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

        $pdf = PDF::loadView('admin.proposal.pdf', $data);
        $pdf->save(storage_path(proppath($pid) . '/combined.pdf'));

        $pdfMerge = PDFMerger::init();
        $pdfMerge->addPDF(storage_path(proppath($pid) . '/combined.pdf'), 'all');
        $pdfMerge->addPDF(storage_path(proppath($pid) . '/document.pdf'), 'all');
        foreach ($recommendations as $r) {
            $pdfMerge->addPDF(storage_path(proppath($pid) . '/letter-' . $r->id . '.pdf'), 'all');
        }
        $pdfMerge->merge();

        $pdfMerge->save(storage_path(proppath($pid) . '/download.pdf'), 'download');

        Storage::delete('proposals/prop-' . $pid . '/combined.pdf');
    }
}
