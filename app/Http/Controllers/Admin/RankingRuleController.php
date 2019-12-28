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


class RankingRuleController extends Controller
{
    public function index()
    {
    }

    public function list($cid)
    {
        try {
            $competitions = Competition::select('id', 'title')->get();

            $rules = RankingRule::with('user.person', 'competition')
                ->where('competition_id', '=', $cid)
                ->get();
            return view(
                'admin.ranking_rule.index',
                compact('rules', 'competitions', 'cid')
            );
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', messageFromTemplate("wrong"));
        }
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

                return redirect('/admin/rankings/competition/' . $request->competition_id)->with('success', messageFromTemplate("success"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {

            logger()->error($exception);
            return redirect('admin/rank')->with('errors', messageFromTemplate("wrong"));
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        try {
            $competition = Competition::all()->pluck('title', 'id');
            $users = Person::with('user')->whereIn('type', ['admin', 'superadmin'])
                ->get();
            $rank = RankingRule::where('id', $id)->first();
            return view('admin.ranking_rule.edit', compact('rank', 'competition', 'users'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update(Request $request, $id)
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
                return redirect('/admin/rankings/competition/' . $request->competition_id)->with('success', messageFromTemplate("success"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('/admin/rankings/competition/' . $request->competition_id)->with('error', messageFromTemplate("wrong"));
        }
    }


    public function execute(Request $request)
    {
        $rr_ids = $request->id;
        $cid = $request->cid;
        $cleanup = $request->cleanup;

        if ($cleanup == 'true') {
            $proposals = Proposal::select('id')->where('competition_id', '=', $cid)->get()->sortBy('id')->pluck('id');
            \Debugbar::error('* Cleaning up ranks for ' . count($proposals) . ' proposals');
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

            \Debugbar::error('* Processing ' . count($proposals) . ' proposals with rule ' . $rules->name);

            if (propertyInSet($rules, ['pi_age', 'pi_sex'])) {
                \Debugbar::error('  Processing ' . count($proposals) . ' with PI rules.');
                $data = getPiData($proposals);

                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  inbetween($data["ages"][$value], $rules->pi_age) &&
                        inbetween($data["sexes"][$value], $rules->pi_sex);
                });
                \Debugbar::error('  Count down to ' . count($proposals));
            }

            if (propertyInSet($rules, ['participants_sex', 'participants', 'avg_part_age', 'junior_participants'])) {
                \Debugbar::error('  Processing ' . count($proposals) . ' with participants rules.');
                $data = getParticipantData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  inbetween($data["avgages"][$value], $rules->avg_part_age) &&
                        inbetween($data["avgsexes"][$value], $rules->participants_sex) &&
                        inbetween($data["counts"][$value], $rules->participants) &&
                        inbetween($data["juniorcounts"][$value], $rules->junior_participants);
                });
                \Debugbar::error('  Count down to ' . count($proposals));
            }

            if (propertyInSet($rules, ['budget', 'pi_salary', 'collab_salary', 'avg_salary', 'salary_dev', 'travel', 'equipment'])) {
                \Debugbar::error('  Processing ' . count($proposals) . ' with budget rules.');
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
                \Debugbar::error('  Count down to ' . count($proposals));
            }

            if (propertyInSet($rules, ['category'])) {
                \Debugbar::error('  Processing ' . count($proposals) . ' with category rules.');
                $data = getCategoryData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    return  collect($rules->category)->contains($data["catmembership"][$value]) ||
                        collect($rules->category)->contains($data["subcatmembership"][$value]);
                });
                \Debugbar::error('  Count down to ' . count($proposals));
            }

            if (propertyInSet($rules, ['subscore', 'overall_score'])) {
                \Debugbar::error('  Processing ' . count($proposals) . ' with score rules.');
                $data = getScoreData($proposals);
                $proposals = $proposals->filter(function ($value, $key) use ($data, $rules) {
                    // \Debugbar::error('Max: ' . $data["subscores"][$value]->max() . ", min: " . $data["subscores"][$value]->min() . ", " . $data["overallscores"][$value]);
                    return  inbetween($data["overallscores"][$value], $rules->overall_score) &&
                        $data["subscores"][$value]->max() <= $rules->subscore[1] &&
                        $data["subscores"][$value]->min() >= $rules->subscore[0];
                });
                \Debugbar::error('  Count down to ' . count($proposals));
            }

            DB::beginTransaction();
            \Debugbar::error('* Applying rank value to ' . count($proposals) . ' proposals.');
            foreach ($proposals as $pid) {
                $p = Proposal::find($pid);
                $p->rank += $value;
                $p->save();
            }
            DB::commit();
        }
        $response = ['success' => -1, 'count' => count($rr_ids)];
        return response()->json($response);
    }

    public function destroy($id)
    {
        try {
            $rr = RankingRule::find($id);
            $cid = $rr->competition_id;
            $rr->delete();
            return redirect('/admin/rankings/competition/' . $cid)->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('/admin/rankings/competition/' . $cid)->with('error', messageFromTemplate('wrong'));
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
