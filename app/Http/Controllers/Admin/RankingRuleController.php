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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $rules = RankingRule::with('user.person', 'competition')
                ->get();
//        dd($rules);
            return view('admin.ranking_rule.index',
                compact('rules'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', getMessage("wrong"));
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
            $competition = Competition::all()->pluck('title', 'id');

            $users = Person::with('user')->where('type', 'admin')
                ->orWhere('type', 'PI')
                ->get();
            $rr = RankingRule::with('competition')->get();

            return view('admin.ranking_rule.create',
                compact('competition', 'users', 'rr'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', getMessage("wrong"));
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
        try {
            $v = Validator::make($request->all(), [
                'sql' => 'required|max:1024',
                'competition_id' => 'required|numeric',
                'user_id' => 'required|numeric',
                'value' => 'required|numeric',
            ]);
            if (!$v->fails()) {
                RankingRule::create($request->all());

                return redirect('admin/rank')->with('success', getMessage("success"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {

            logger()->error($exception);
            return redirect('admin/rank')->with('errors', getMessage("wrong"));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $competition = Competition::all()->pluck('title', 'id');
            $users = Person::with('user')->where('type', 'admin')
                ->orWhere('type', 'PI')
                ->orWhere('type', 'referee')
                ->orWhere('type', 'viewer')->get();
            $rank = RankingRule::where('id', $id)->first();
            return view('admin.ranking_rule.edit', compact('rank', 'competition', 'users'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', getMessage("wrong"));
        }

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
                    'sql' => 'required|max:1024',
                    'competition_id' => 'required|numeric',
                    'user_id' => 'required|numeric',
                    'value' => 'required|numeric',
                ]);
                if (!$v->fails()) {
                    $rank = RankingRule::findOrFail($id);
                    $rank->fill($request->all())->save();
                    return redirect('admin/rank')->with('success', getMessage("success"));
                } else
                    return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/rank')->with('error', getMessage("wrong"));
            }
    }


    public function execute()
    {
        try {
            $competitions = Competition::all();

            return view('admin.ranking_rule.execute', compact('competitions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', getMessage('wrong'));
        }
    }

    public function executeQuery(Request $request)
    {

//        try {

        $queries = [];
        foreach ($request->rules as $index => $rule) {
            $g_r = RankingRule::where('id', $rule)->first();
            $q_v = RankingRule::select('value')
                ->where('id', $rule)
                ->where('competition_id', $request->competition_id)
                ->first();

            //have proposal_ides in array
            $queries['result'][$index] = DB::select($g_r->sql);
            $queries['value'][$index] = $q_v->value;
            $queries['summary'][$index] = $g_r->sql;
        }
        $results = [];

        foreach ($queries['result'] as $index => $query) {
            foreach ($query as $i => $item) {

                $rr = Proposal::find($item->id);
                if (!empty($rr)) {
                    $rr->rank = $queries['value'][$index];
                    $rr->save();
                }
                $results[$index]['ids'][$i] = $item->id;
            }
            $results[$index]['value'] = $queries['value'][$index];
            $results[$index]['summary'] = $queries['summary'][$index];

        }

        $tables = [];
        $resalt = $results[0]['ids'];

        $val = $queries['value'][0];
        for ($i = 1; $i < count($results); ++$i) {
            $resalt = array_intersect($resalt, $results[$i]['ids']);
            $val += $queries['value'][$i];
            foreach ($resalt as $index => $query) {
                $rr = Proposal::find($query);
                $rr->rank = $val;
                $rr->save();

            }


        }


        foreach ($queries['result'] as $index => $ids) {
            foreach ($ids as $i => $id) {

                $rr = Proposal::select('id', 'rank')->where('id', $id->id)->first();
                $tables['summary'][$id->id] = $rr;

            }
        }
        $tables = array_unique($tables);

        return view('admin.ranking_rule.result', compact('tables', 'results'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('admin/rank')->with('error', getMessage('wrong'));
//        }
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
            $scoreType = RankingRule::find($id);
            $scoreType->delete();
            return redirect('admin/rank')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/rank')->with('error', getMessage('wrong'));
        }
    }


}
