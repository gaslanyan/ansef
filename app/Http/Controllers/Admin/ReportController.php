<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalReports;
use App\Models\RefereeReport;
use App\Models\Competition;
use App\Models\Proposal;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($cid)
    {
        try {

        $score_json = [];
        $competitions = Competition::select('id', 'title')
            ->orderBy('submission_end_date', 'desc')
            ->get()->toArray();


        return view('admin.report.index', compact('score_json', 'competitions', 'cid'));
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/report')->with('error', getMessage("wrong"));
            }
    }

    public function index() {

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $report = RefereeReport::find($id);
            $cid = $report->proposal()->first()->competition()->first()->id;
            $scores = Score::where('report_id','=',$id)->get();
            $scores->delete();
            $report->delete();
            return redirect('admin/report/list/' . $cid)->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/report/list/' . $cid)->with('error', getMessage('wrong'));
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function approve()
    {
        try {
            $reports = ProposalReports::with(['proposal' => function ($query) {
                $query->select('id', 'title');
            }])->get();
//dd($reports);
            return view('admin.report.approve', compact('reports'));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('admin/proposal')->with('error', getMessage('wrong'));
        }

    }

    public function listreports($cid, Request $request)
    {
        $d['data'] = [];
        if ($cid == -1) {
            $reports = RefereeReport::with([
                'proposal' => function ($query) {
                    $query->select('id', 'title', 'proposal_admin', 'comment');
                }, 'person' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])
                ->orderBy('id', 'asc')
                ->get();
        } else {
            $reports = RefereeReport::with([
                'proposal' => function ($query) {
                    $query->select('id', 'title', 'proposal_admin', 'comment');
                }, 'person' => function ($query) {
                    $query->select('id', 'first_name', 'last_name');
                }
            ])
                ->where('competition_id', '=', $cid)
                ->orderBy('id', 'asc')
                ->get();
        }

        foreach ($reports as $index => $report) {
            $scores = Score::with(['scoreType' => function ($query) {
                $query->select('name', 'id');
            }])
                ->where('report_id', '=', $report['id'])
                ->get();
            $s = [];
            foreach ($scores as $i => $score) {
                $s[$i]['name'] = $score->scoreType->name;
                $s[$i]['value'] = $score->value;
            }
            $comments = '';
            $d['data'][$index]['public'] = $report->public_comment ?? 'No comments';
            $d['data'][$index]['private'] = $report->private_comment ?? 'No comments';
            if (!empty($report->public_comment) || !empty($report->private_comment)) $comments = ' *';
            $pr = Proposal::find($report['proposal']['id']);
            $d['data'][$index]['id'] = $report['id'];
            $d['data'][$index]['tag'] = getProposalTag($pr->id);
            $d['data'][$index]['title'] = truncate($report['proposal']['title'], 20);
            $d['data'][$index]['referee'] = truncate($report['person']['last_name'],5);
            $a = $pr->admin()->first();
            $d['data'][$index]['admin'] = !empty($a) ? substr($a->last_name, 0, 4) . '.' : 'None';
            $d['data'][$index]['due_date'] = $report['due_date'];
            $d['data'][$index]['overall_score'] = $report['overall_score'] . "% " . $comments . "";
            $d['data'][$index]['state'] = abbreviate($report['state']);

            if (!empty($scores)) {
                $d['data'][$index]['scores'] = json_encode($s);
            }
        }

        return Response::json($d);
    }

    public function deleteReport(Request $request)
    {
        DB::beginTransaction();
        try {
            //        if (isset($request->_token)) {
            $report_ids = $request->id;

            foreach ($report_ids as $index => $id) {
                $pid = RefereeReport::find($id)->proposal_id;
                RefereeReport::where('id', $id)->delete();
                Score::where('report_id', '=', $id)->delete();
                updateProposalState($pid);
                updateProposalScore($pid);

                $response = [
                    'success' => true
                ];
            }
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
}
