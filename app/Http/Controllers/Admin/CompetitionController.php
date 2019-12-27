<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Degree;
use App\Models\Proposal;
use App\Models\ProposalInstitution;
use App\Models\ProposalReport;
use App\Models\RankingRule;
use App\Models\RefereeReport;
use App\Models\ScoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CompetitionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $coms = Competition::all();

            $categories = Category::whereNull('parent_id')->get();
            return view("admin.competition.index",
                compact('coms', 'categories'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/competition')->with('error', messageFromTemplate("wrong"));
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
            $degrees = Degree::all();
            $categories = Category::whereNull('parent_id')->get();
            return view("admin.competition.create", compact('degrees', 'categories'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/competition')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function updateCompetition(Request $request)
    {
        //        if ($request->_token === Session::token()) {
        $items = json_decode($request->form);
        if (Competition::find((int) $items->id)->update((array) $items))
            $response = [
                'success' => true
            ];
        else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);
        //        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->isMethod('post'))
            return view('admin.competition.create');
        else {
            try {
                $v = Validator::make($request->all(), [
                    'title' => 'required|max:255',
                    'description' => 'required|max:255',
                    'announcement_date' => 'required|date|date_format:Y-m-d',
                    'submission_start_date' => 'required|date|date_format:Y-m-d|after:announcement_date',
                    'submission_end_date' => 'required|date|date_format:Y-m-d|after:submission_start_date',
                    'results_date' => 'required|date|date_format:Y-m-d|after:submission_end_date|before:project_start_date',
                    'project_start_date' => 'required|date|date_format:Y-m-d|after:submission_end_date',
                    'first_report' => 'required|date|date_format:Y-m-d',
                    'second_report' => 'required|date|date_format:Y-m-d|after:first_report',
                    'duration' => 'required|numeric',
                    'min_budget' => 'required|numeric',
                    'max_budget' => 'required|numeric|max:min_budget',
                    'min_age' => 'required|numeric',
                    'max_age' => 'required|numeric|max:min_age',
                    'state' => 'required|max:255',
                    'categories.*' => 'required|max:255',
                ]);
                if (!$v->fails()) {
                    $additional = [];
                    $additional['additional_charge_name'] = $request->additional_charge_name;
                    $additional['additional_charge'] = $request->additional_charge;
                    $additional['additional_percentage_name'] = $request->additional_percentage_name;
                    $additional['additional_percentage'] = $request->additional_percentage;
                    $allow_foreign = '0';
                    if (isset($request->allow_foreign)) $allow_foreign = '1';
                    $c = Competition::create([
                        'title' => $request->title,
                        'description' => $request->description,
                        'submission_start_date' => $request->submission_start_date,
                        'submission_end_date' => $request->submission_end_date,
                        'results_date' => $request->results_date,
                        'announcement_date' => $request->announcement_date,
                        'project_start_date' => $request->project_start_date,
                        'duration' => $request->duration,
                        'min_budget' => $request->min_budget,
                        'max_budget' => $request->max_budget,
                        'min_level_deg_id' => $request->min_level_deg_id,
                        'max_level_deg_id' => $request->max_level_deg_id,
                        'min_age' => $request->min_age,
                        'max_age' => $request->max_age,
                        'allow_foreign' => $allow_foreign,
                        'comments' => $request->comments,
                        'first_report' => $request->first_report,
                        'second_report' => $request->second_report,
                        'state' => $request->state,
                        'recommendations' => $request->recommendations,
                        'categories' => json_encode($request->category),
                        'additional' => json_encode($additional),
                        'instructions' => $request->instructions
                    ]);

                    BudgetCategory::create([
                        'name' => 'PI Salary',
                        'min' => 0,
                        'max' => 5000,
                        'weight' => 1,
                        'comments' => 'Monthly amount, number of months',
                        'competition_id' => $c->id
                    ]);
                    BudgetCategory::create([
                        'name' => 'Travel',
                        'min' => 0,
                        'max' => 5000,
                        'weight' => 1,
                        'comments' => 'Destination, number of months',
                        'competition_id' => $c->id
                    ]);
                    BudgetCategory::create([
                        'name' => 'Material or equipment',
                        'min' => 0,
                        'max' => 5000,
                        'weight' => 1,
                        'comments' => 'Describe material or equipment',
                        'competition_id' => $c->id
                    ]);

                    return redirect('admin/competition')->with('success', messageFromTemplate("success"));
                } else
                    return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/competition')->with('error', messageFromTemplate("wrong"));
            }
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
        try {
            $com = Competition::with('min_degree')
                ->with('max_degree')
                ->where('id', $id)->first();

            $categories = json_decode($com->categories);

            dd($com->categories);
            $cats = [];
            foreach ($categories as $index => $category) {
                if ($category != 0) {
                    $cat = Category::with('children')->where('id', $category)->get()->first();

                    if (empty($cat->parent_id))
                        $cats[$cat->id]['parent'] = $cat->title;
                    else {
                        if (in_array($cat->parent_id, $categories))
                            $cats[$cat->parent_id]['sub'] = $cat->title;
                    }
                }
            }
            $st = ScoreType::where('competition_id', '=', $id)->get();
            $bc = BudgetCategory::where('competition_id', '=', $id)->get();
            $rr = RankingRule::with('user')->where('competition_id', '=', $id)->get();
            return view("admin.competition.show", compact('com', 'cats',
                'st', 'rr', 'bc'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/competition')->with('error', messageFromTemplate("wrong"));
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            ScoreType::where('competition_id', '=', $id)->delete();
            RankingRule::where('competition_id', '=', $id)->delete();
            BudgetCategory::where('competition_id', '=', $id)->delete();
            $p_isd = Proposal::select('id')->where('competition_id', '=', $id)->get();

            foreach ($p_isd as $index => $item) {
                ProposalInstitution::where('proposal_id', '=', $item)->delete();
                ProposalReport::where('proposal_id', '=', $item)->delete();
                BudgetItem::where('proposal_id', '=', $item)->delete();
                RefereeReport::where('proposal_id', '=', $item)->delete();
//                Recomendation::where('proposal_id','=',$item)->delete();
            }

            Proposal::destroy(collect($p_isd));
            Competition::destroy($id);

            DB::commit();
            return redirect('admin/competition')->with('delete', messageFromTemplate('deleted'));
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return Redirect::to('/form')
                ->withErrors($e->getErrors())
                ->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('admin/competition')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function __construct()
    {
        // $this->except('logout');
    }
}
