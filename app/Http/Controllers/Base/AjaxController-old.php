<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Degree;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ScoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function copyItems(Request $request)
    {
        if (isset($request->_token)) {
            $id = $request->id;
            $response = DB::select('select * from ' . $request->table . ' where id = :id', ['id' => $id]);
            return response()->json($response[0]);
        }
    }

    public function getCompetitionsListForStatistics(Request $request)
    {

        $value = $request->value;
        $content = [];
        if ($value == 'competition') {
            $content['comp'] = Competition::all();
            foreach ($content['comp'] as $np) {
                $np_count = Proposal::select('id', 'competition_id')->where('competition_id', '=', $np->id)->count(); //->get()->toArray();
                $content['numberofproposals'] = $np_count;
            }
        } else if ($value == 'pi') {
            $content['pi'] = Person::all();
        }

        echo json_encode($content);
        exit();
    }
}
