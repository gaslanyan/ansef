<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Institution;
use App\Models\ProposalInstitution;
use App\Models\Proposal;
use App\Models\Address;
use App\Models\Country;
use App\Models\ProposalReports;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use PDF;

class StatisticsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = \DB::table('competitions')
            //->where('stockName','=','Infosys')
            //->orderBy('id', 'ASC')
            ->get()->toArray();

        return view('viewer.statistics.statistic', compact('result'));
    }

    public function show()
    {


    }

    public function store()
    {


    }

    public function chart()
    {
        $result = \DB::table('competitions')
            //->where('stockName','=','Infosys')
            //->orderBy('id', 'ASC')
            ->get()->toArray();

        return response()->json($result);
//        echo json_encode($result);
//        exit;
    }

    public function y_result(Request $request)
    {
        $content = [];
        //dd($request->value_x);
        if ($request->type == 'competition') {
            foreach ($request->value_x as $vx) {
                //  $np_count = Proposal::select('id', 'competition_id')->where('competition_id' , '=',$np->id)->count();//->get()->toArray();
                $query = DB::table('proposals')
                    ->select(array('competitions.title', DB::raw('COUNT(proposals.id) as pid')))
                    ->where('proposals.competition_id', '=', $vx)
                    ->join('competitions', 'competitions.id', '=', 'proposals.competition_id')
                    ->groupBy('competitions.title')
                    //  ->order_by('proposals.id', 'desc')
                    ->get()->toArray();
                array_push($content, $query);
            }
            //$content['numberofproposals'] = $np_count;
           // dd($content);

        }

        return response()->json($content);
       echo json_encode($result);
//        exit;
    }

}
