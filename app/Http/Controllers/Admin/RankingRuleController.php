<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\RankingRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Response;

class RankingRuleController extends Controller
{
    public function index()
    {
    }

    public function rankingslist() {
        $competitions = Competition::select('id', 'title')->get();
        return view('admin.ranking_rule.index', compact('competitions'));
    }

    public function listrankings($cid)
    {
        Cookie::queue('cid', $cid, 24 * 60);
        $d['data'] = [];
        if ($cid == -1) {
            $rules = RankingRule::with('user.person', 'competition')
                                ->get();
        } else {
            $rules = RankingRule::with('user.person', 'competition')
                                ->where('competition_id', '=', $cid)
                                ->get();
        }
        foreach ($rules as $index => $rr) {
            $d['data'][$index]['id'] = $rr->id;
            $d['data'][$index]['name'] = !empty($rr->user) ? truncate($rr->user->last_name, 7) . " " . $rr->user->first_name : '';
            $d['data'][$index]['value'] = $rr->value;
            $d['data'][$index]['sql'] = $rr->sql;
        }

        return Response::json($d);
    }

    public function stats(Request $request) {
        ini_set('memory_limit', '512M');

        $cid = $request->cid;
        $proposals = Proposal::select('id')->where('competition_id', '=', $cid)->get()->sortBy('id')->pluck('id');
        $proposals2 = Proposal::whereIn('state', ['approved 1', 'approved 2', 'awarded', 'finalist'])->where('competition_id', '=', $cid)->get()->sortBy('id')->pluck('id');

        $stats = "<p style='color:#666;margin-left:15px;'>";

        $stats .= ("Proposal count: " . count($proposals) . "; awards/finalists: " . count($proposals2) . "<br/><br/>");

        if($request->type == "pi") {
            $pidata = getPiData($proposals);
            $pidata2 = getPiData($proposals2);

            $stats .= statline('PI ages', $pidata, $pidata2, "ages");
            $stats .= statline('PI sexes', $pidata, $pidata2, "sexes");
            $stats .= statline('PI foreign publications', $pidata, $pidata2, "publications");
            $stats .= statline('PI foreign ANSEF publications', $pidata, $pidata2, "publications_ansef");
            $stats .= statline('PI domestic publications', $pidata, $pidata2, "publications_dom");
            $stats .= statline('PI domestic ANSEF publications', $pidata, $pidata2, "publications_ansef_dom");
        }
        else if($request->type == "participants") {
            $partdata = getParticipantData($proposals);
            $partdata2 = getParticipantData($proposals2);

            $stats .= statline('Participant ages', $partdata, $partdata2, "avgages");
            $stats .= statline('Participant sexes', $partdata, $partdata2, "avgsexes");
            $stats .= statline('Participant counts', $partdata, $partdata2, "counts");
            $stats .= statline('Participant juniors', $partdata, $partdata2, "juniorcounts");
            $stats .= statline('Participant foreign publications', $partdata, $partdata2, "part_publications");
            $stats .= statline('Participant foreign ANSEF publications', $partdata, $partdata2, "part_publications_ansef");
            $stats .= statline('Participant domestic publications', $partdata, $partdata2, "part_publications_dom");
            $stats .= statline('Participant domestic ANSEF publications', $partdata, $partdata2, "part_publications_ansef_dom");
        }
        else if ($request->type == "budget") {
            $budgdata = getBudgetData($proposals);
            $budgdata2 = getBudgetData($proposals2);
            $stats .= statline('Budgets', $budgdata, $budgdata2, "budgets");
            $stats .= statline('PI salaries', $budgdata, $budgdata2, "pisalaries");
            $stats .= statline('Collab salaries', $budgdata, $budgdata2, "collabsalaries");
            $stats .= statline('Salaries', $budgdata, $budgdata2, "avgsalaries");
            $stats .= statline('Deviation', $budgdata, $budgdata2, "devsalaries");
            $stats .= statline('Travel', $budgdata, $budgdata2, "travels");
            $stats .= statline('Equipment', $budgdata, $budgdata2, "equipments");
        }
        else if ($request->type == "score") {
            $scoredata = getScoreData($proposals);
            $scoredata2 = getScoreData($proposals2);
            $stats .= statline('Overall scores', $scoredata, $scoredata2, "overallscores");
        }
        else {
        }

        $stats .= '</p>';

        $response = [
            'success' => true,
            'content' => $stats
        ];
        return response()->json($response);
    }

    public function create()
    {
        try {
            $competition = Competition::all()->pluck('title', 'id');
            $users = Person::with('user')->whereIn('type', ['admin', 'superadmin'])
                ->get();
            $rr = RankingRule::with('competition')->get();

            return view('admin.ranking_rule.create', compact('competition', 'users', 'rr'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function store(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'sql' => 'required|max:1024',
                'competition_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'value' => 'required|numeric',
            ]);
            if (!$v->fails()) {
                RankingRule::create($request->all());

                return redirect('/admin/rankings')->with('success', messageFromTemplate("success"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {

            logger()->error($exception);
            return redirect('admin/rankings')->with('errors', messageFromTemplate("wrong"));
        }
    }

    public function show($id)
    {
        //
    }

    public function edit(Request $request)
    {
        try {
            $competition = Competition::all()->pluck('title', 'id');
            $users = Person::with('user')->whereIn('type', ['admin', 'superadmin'])
                ->get();
            $rank = RankingRule::where('id', $request->id)->first();
            return view('admin.ranking_rule.edit', compact('rank', 'competition', 'users'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rankings')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update($id, Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'sql' => 'required|max:1024',
                'competition_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'value' => 'required|numeric',
            ]);
            if (!$v->fails()) {
                $rank = RankingRule::findOrFail($id);
                $rank->fill($request->all())->save();
                return redirect('/admin/rankings')->with('success', messageFromTemplate("success"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('/admin/rankings')->with('error', messageFromTemplate("wrong"));
        }
    }


    public function execute(Request $request)
    {
        $content = "";
        $rr_ids = $request->id;
        $cid = $request->cid;
        $cleanup = $request->cleanup;

        if ($cleanup == 'true') {
            $proposals = Proposal::select('id')->where('competition_id', '=', $cid)->get()->sortBy('id')->pluck('id');
            $content .= ('* Cleaning up ranks for ' . count($proposals) . ' proposals');
            DB::beginTransaction();
            foreach ($proposals as $pid) {
                $p = Proposal::find($pid);
                $p->rank = 0;
                $p->save();
            }
            DB::commit();
        }

        foreach ($rr_ids as $rrid) {
            $rr = RankingRule::find($rrid);
            $proposals = Proposal::select('id')->where('competition_id', '=', $rr->competition_id)->get()->sortBy('id')->pluck('id');
            $rules = json_decode($rr->sql);
            $value = $rr->value;

            $content .= ('* Processing ' . count($proposals) . ' proposals with rule ' . $rules->name . '<br>');

            if (propertyInSet($rules, ['pi_age', 'pi_sex', 'pi_publications', 'pi_publications_dom', 'pi_publications_ansef', 'pi_publications_ansef_dom'])) {
                $content .= ('  Processing ' . count($proposals) . ' with PI rules.' . '<br>');
                $data = getPiData($proposals);

                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  inbetween($data["ages"][$value], $rules->pi_age) &&
                            inbetween($data["sexes"][$value], $rules->pi_sex) &&
                            inbetween($data["publications"][$value], $rules->pi_publications) &&
                            inbetween($data["publications_dom"][$value], $rules->pi_publications_dom) &&
                            inbetween($data["publications_ansef"][$value], $rules->pi_publications_ansef) &&
                            inbetween($data["publications_ansef_dom"][$value], $rules->pi_publications_ansef_dom);
                });
                $content .= ('  Count down to ' . count($proposals) . '<br>');
            }

            if (propertyInSet($rules, ['participants_sex', 'part_publications', 'part_publications_ansef', 'part_publications_dom', 'part_publications_ansef_dom', 'participants', 'avg_part_age', 'junior_participants'])) {
                $content .= ('  Processing ' . count($proposals) . ' with participants rules.' . '<br>');
                $data = getParticipantData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  inbetween($data["avgages"][$value], $rules->avg_part_age) &&
                        inbetween($data["avgsexes"][$value], $rules->participants_sex) &&
                        inbetween($data["counts"][$value], $rules->participants) &&
                        inbetween($data["juniorcounts"][$value], $rules->junior_participants) &&
                        inbetween($data["part_publications"][$value], $rules->part_publications) &&
                        inbetween($data["part_publications_dom"][$value], $rules->part_publications_dom) &&
                        inbetween($data["part_publications_ansef"][$value], $rules->part_publications_ansef) &&
                        inbetween($data["part_publications_ansef_dom"][$value], $rules->part_publications_ansef_dom);
                });
                $content .= ('  Count down to ' . count($proposals) . '<br>');
            }

            if (propertyInSet($rules, ['budget', 'pi_salary', 'collab_salary', 'avg_salary', 'salary_dev', 'travel', 'equipment'])) {
                $content .= ('  Processing ' . count($proposals) . ' with budget rules.' . '<br>');
                $data = getBudgetData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    // \Debugbar::error('Budget: ' . $data["budgets"][$value] . ", PI " . $data["pisalaries"][$value] . ", Coll. " . $data["collabsalaries"][$value] . ", " . $data["avgsalaries"][$value] . ", " . $data["devsalaries"][$value] . ", " . $data["travels"][$value] . ", " . $data["equipments"][$value]);
                    return inbetween($data["budgets"][$value], $rules->budget) &&
                        inbetween($data["pisalaries"][$value], $rules->pi_salary) &&
                        inbetween($data["collabsalaries"][$value], $rules->collab_salary) &&
                        inbetween($data["avgsalaries"][$value], $rules->avg_salary) &&
                        inbetween($data["devsalaries"][$value], $rules->salary_dev) &&
                        inbetween($data["travels"][$value], $rules->travel) &&
                        inbetween($data["equipments"][$value], $rules->equipment);
                });
                $content .= ('  Count down to ' . count($proposals) . '<br>');
            }

            if (propertyInSet($rules, ['category'])) {
                $content .= ('  Processing ' . count($proposals) . ' with category rules.' . '<br>');
                $data = getCategoryData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  collect($rules->category)->contains($data["catmembership"][$value]) ||
                        collect($rules->category)->contains($data["subcatmembership"][$value]);
                });
                $content .= ('  Count down to ' . count($proposals) . '<br>');
            }

            if (propertyInSet($rules, ['subscore', 'overall_score'])) {
                $content .= ('  Processing ' . count($proposals) . ' with score rules.' . '<br>');
                $data = getScoreData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  inbetween($data["overallscores"][$value], $rules->overall_score) &&
                        $data["subscores"][$value]->max() <= $rules->subscore[1] &&
                        $data["subscores"][$value]->min() >= $rules->subscore[0];
                });
                $content .= ('  Count down to ' . count($proposals) . '<br>');
            }

            DB::beginTransaction();
            $content .= ('* Applying rank value to ' . count($proposals) . ' proposals.' . '<br>');
            foreach ($proposals as $pid) {
                $p = Proposal::find($pid);
                $p->rank += $value;
                $p->save();
            }
            DB::commit();
        }

        $response = ['success' => -1, 'content' => $content];
        return response()->json($response);
    }

    public function remove(Request $request)
    {
        try {
            $rr = RankingRule::find($request->id);
            $rr->delete();
            return redirect('/admin/rankings')->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('/admin/rankings')->with('error', messageFromTemplate('wrong'));
        }
    }

    public function deleteRule(Request $request)
    {
        DB::beginTransaction();
        try {
            $rule_ids = $request->id;

            foreach ($rule_ids as $index => $cat) {
                RankingRule::where('id', $cat)->delete();
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

    public function getRR(Request $request)
    {
        try {
            $_id = $request->id;
            $rs = RankingRule::select('id', 'sql')->where('competition_id', $_id)->get();
            if (!empty($rs))
                $response = [
                    'rs' => json_encode($rs, true),
                    'success' => true
                ];
            else
                $response = [
                    'success' => false,
                    'count' => 0
                ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];

            logger()->error($exception);
        }
        return response()->json($response);
    }
}
