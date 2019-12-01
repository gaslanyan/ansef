<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProposalReports;
use App\Models\RefereeReport;
use App\Models\Competition;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nexmo\Response;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list($cid)
    {
        {
            try {

            $score_json = [];
                //                foreach ($scores as $index => $score) {
                //                    $score_json[$score->report->id]['name'][] = $score->scoreType->name;
                //                    $score_json[$score->report->id]['value'][] = $score->value;
                //                }
            $competitions = Competition::select('id', 'title')
                ->orderBy('submission_end_date', 'desc')
                ->get()->toArray();


            return view('admin.report.index', compact('score_json', 'competitions', 'cid'));
             } catch (\Exception $exception) {
                 logger()->error($exception);
                 return redirect('admin/report')->with('error', getMessage("wrong"));
             }

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
}
