<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Message;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReports;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $proposals = Proposal::with('competition')->get();
            $referee_role = Role::where('name', '=', 'referee')->first();
            $admin_role = Role::where('name', '=', 'admin')->first();

            $referee = Role::find($referee_role->id);

            $referees = $referee->persons;

            $admin = Role::find($admin_role->id);
            $admins = $admin->persons;
            $messages = Message::all();
            return view('admin.proposal.index', compact('proposals', 'referees', 'admins', 'messages'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', getMessage("wrong"));
        }
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
                    $referee_info[$referee]['dur_date'] = $rr->dur_date;
                    $referee_info[$referee]['overall_scope'] = $rr->overall_scope;
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


}
