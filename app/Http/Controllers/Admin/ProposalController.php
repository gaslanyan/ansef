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

            $referees = $referee->persons;
            $admins = $admin->persons;
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

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        try{
        $proposal = Proposal::with('competition')->where('id', $id)->first();

        $categories = json_decode($proposal->categories);
        $referees = json_decode($proposal->proposal_referees);

        if (!empty($referees)) {
            $referee_info = [];
            $referee_id = [];
            foreach ($referees as $index => $referee) {
                $person = getPerson($referee);
                $rr = RefereeReport::with('proposal')->where('referee_id', $referee)->first();
                $referee_id[] = $rr->id;
                if (!empty($rr)) {
                    $referee_info[$referee]['public_comment'] = $rr->public_comment;
                    $referee_info[$referee]['private_comment'] = $rr->private_comment;
                    $referee_info[$referee]['due_date'] = $rr->due_date;
                    $referee_info[$referee]['overall_score'] = $rr->overall_score;
                }
                $referee_info[$referee]['id'] = $person->id;
                $referee_info[$referee]['first_name'] = $person->first_name;
                $referee_info[$referee]['last_name'] = $person->last_name;
            }

        }
        foreach ($referee_id as $index => $item) {
            $scores = DB::table('scores')
                ->join('score_types', 'score_types.id', '=', 'scores.score_type_id')
                ->select('score_types.name', 'scores.value')
                ->get();

        }
        $cats = [];
        foreach ($categories as $index => $category) {
            if ($category != 0) {
                $cat = Category::with('children')->where('id', $category)->get()->first();

                if (empty($cat->parent_id))
                    $cats[$cat->id]['parent'] = $cat->title;
                else {
//                    dd(in_array($cat->parent_id, (array)$categories));
                    if (in_array($cat->parent_id, (array)$categories))
                        $cats[$cat->parent]['sub'] = $cat->title;
                }
            }
        }

        return view('admin.proposal.show', compact('proposal', 'cats', 'referee_info', 'scores'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('admin/proposal')->with('error', getMessage("wrong"));
//        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
            $d['data'][$index]['title'] = truncate($pr->title, 20);
            $d['data'][$index]['state'] = abbreviate($pr->state);
            $pi = $pr->pi();
            $d['data'][$index]['pi'] = !empty($pi) ? truncate($pi->last_name,5) . ", " . $pi->first_name : '';
            $refs = $pr->refereesasstring();
            $d['data'][$index]['refs'] = !empty($refs) ? $refs : '';
            $a = $pr->admin()->first();
            $d['data'][$index]['admin'] = !empty($a) ? substr($a->last_name,0,4) : 'None';
        }

        return Response::json($d);
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
}
