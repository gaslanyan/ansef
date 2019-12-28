<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\Category;
use App\Models\Competition;
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

    public function getCompetitionContentByID(Request $request)
    {
        if (isset($request->_token)) {
            $comp_id = $request->id;

            $content = [];
            $com = Competition::select('recommendations', 'allow_foreign', 'categories', 'additional')->where('id', '=', $comp_id)->first();
            $categories = json_decode($com->categories);
            foreach ($categories as $index => $category) {
                if ($category != 0) {
                    $cat = Category::with('children')->where('id', $category)->get()->first();

                    if (empty($cat->parent_id))
                        $content['cats'][$cat->id]['parent'] = $cat->title;
                    else {
                        if (in_array($cat->parent_id, $categories))
                            $content['cats'][$cat->parent_id]['sub'] = $cat->title;
                    }
                }
            }

            $content['bc'] = BudgetCategory::where('competition_id', '=', $comp_id)->get()->toArray();
            $content['st'] = ScoreType::where('competition_id', '=', $comp_id)->get()->toArray();
            $content['recommendition'] = $com->recommendations;
            $content['allowforeign'] = $com->allow_foreign;
            $content['additional'] = json_decode($com->additional);

            echo json_encode($content);
            exit;
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
