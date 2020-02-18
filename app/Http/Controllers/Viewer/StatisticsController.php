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
use App\Models\ProposalReport;
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
    {  $bins = [];
        $stat_functions = [
            "proposalcount" => 'proposalcount',
            "avg_score" => "avgscore",
            'max_overall_score' =>"maxoverallscore",
            'min_overall_score'=>"minoverallscore",
            'participant_avg_age'=>"participantavgage",
            'participant_max_age' =>"participantmaxage",
            "participant_min_age" =>"participantminage",
            "pi_avg_age"=>"piavgage",
            "pi_max_age" => "pimaxage",
            "pi_min_age" =>"piminage",
            "average_participant_sex"=>"averageparticipantsex",
            "average_pi_sex"=>"averagepisex",
            "pi_publication_avg" => "pipublicationavg",
            "pi_publication_year_avg" => "publicationyearavg",
            "total_amounts_of_funds"=>"totalamountsoffunds",
            'avg_salaries' => "avgSalaries",
            'avg_travel' => "avgTravel",
            'avg_equipment'=>'avgEquipment'];

        $array_categories= $request->value_x;
        foreach($array_categories as $cat) {
            $filtered_props = Proposal::where('categories', 'REGEXP', $cat)->where('competition_id','=',$request->type)->get()->toArray();

            $bins[getCategoryNameById($cat)] = $stat_functions[$request->value_y]($filtered_props);
        }
        return response()->json($bins);
        echo json_encode($bins);
//      exit;
    }

    public function my_result(Request $request)
    {
        $bins = [];
        $stat_functions = [
            "proposalcount" => 'proposalcount',
            "avg_score" => "avgscore",
            'max_overall_score' => "maxoverallscore",
            'min_overall_score' => "minoverallscore",
            'participant_avg_age' => "participantavgage",
            'participant_max_age' => "participantmaxage",
            "participant_min_age" => "participantminage",
            "pi_avg_age" => "piavgage",
            "pi_max_age" => "pimaxage",
            "pi_min_age" => "piminage",
            "average_participant_sex" => "averageparticipantsex",
            "average_pi_sex" => "averagepisex",
            "pi_publication_avg" => "pipublicationavg",
            "pi_publication_year_avg" => "publicationyearavg",
            "total_amounts_of_funds" => "totalamountsoffunds",
            'avg_salaries' => "avgSalaries",
            'avg_travel' => "avgTravel",
            'avg_equipment' => 'avgEquipment'
        ];

        $array_categories = $request->value_cat;
        $array_competitions = $request->value_mx;

        foreach ($array_competitions as $competition) {
            foreach ($array_categories as $cat) {
                $filtered_props = Proposal::whereIn('state', $request->statistic_prop_state)
                                            ->where('categories', 'REGEXP', $cat)
                                            ->where('competition_id', '=', $competition)
                                            ->get()->toArray();

                $bins[getCompetitionNameById($competition)][getCategoryNameById($cat)] = $stat_functions[$request->value_my]($filtered_props);
            }
        }

        // \Debugbar::info("Hello: " . response()->json($bins));

        return response()->json($bins);
        echo json_encode($bins);
//      exit;
    }



}
