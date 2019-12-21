<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Country;
use App\Models\Email;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Message;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReports;
use App\Models\RankingRule;
use App\Models\Recommendations;
use App\Models\RefereeReport;
use App\Models\Score;
use App\Models\ScoreType;
use App\Models\User;
use App\Notifications\ActivatedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;

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

    /* VIEWER ajax requests */

    public function getCompetitionsListForStatistics(Request $request)
    {
        $value = $request->value;
        $content = [];
        if ($value == 'competition') {
            $content['comp'] = Competition::all();
            foreach ($content['comp'] as $np) {
                $np_count = Proposal::select('id', 'competition_id')->where('competition_id', '=', $np->id)->count();//->get()->toArray();
                $content['numberofproposals'] = $np_count;
            }

        } else if ($value == 'pi') {
            $content['pi'] = Person::all();
        }

        echo json_encode($content);
        exit();
    }

}
