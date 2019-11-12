<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BudgetCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $budgets = BudgetCategory::all();
            $competition = Competition::all()->pluck('title', 'id');
            return view('admin.budget.index', compact('budgets', 'competition'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/budget')->with('message', 'Unable to create new user.');
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
            $budgets = BudgetCategory::with('competition')->get();

            return view('admin.budget.create', compact('competition', 'budgets'));
        } catch (\Exception $exception) {
            dd($exception);
            logger()->error($exception);
//            return redirect('admin/budget')->with('message', 'Unable to create new user.');
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
        if (!$request->isMethod('post'))
            return view('admin.budget.create');
        else {
            try {
                $v = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'competition_id' => 'required|numeric',
                    'min' => 'required|numeric',
                    'max' => 'required|numeric|max:min',
                    'weight' => 'required|numeric',
                ]);
                if (!$v->fails()) {
                    BudgetCategory::create($request->all());
                    return redirect('admin/budget')->with('success', getMessage("success"));
                } else
                    return redirect()->back()->withErrors($v->errors());

            } catch (\Exception $exception) {
                dd($exception);
                logger()->error($exception);
                return redirect('admin/budget')->with('errors', getMessage("wrong"));
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

            $budget = BudgetCategory::find($id);
            $competition = Competition::all()->pluck('title', 'id');
            return view('admin.budget.edit', compact('budget', 'competition'));


        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/budget')->with('message', 'Unable to create new user.');
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
        if (!$request->isMethod('post'))
            return view('admin.budget.edit');
        else {
        try {
            $v = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'competition_id' => 'required|numeric',
                'min' => 'required|numeric',
                'max' => 'required|numeric|max:min',
                'weight' => 'required|numeric',
            ]);
            if (!$v->fails()) {
                $bcUpdate = BudgetCategory::findOrFail($id);
                $bcUpdate->fill($request->all())->save();
                return redirect('admin/budget')->with('success', getMessage("update"));
            } else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/budget')->with('errors', getMessage("wrong"));
        }
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
//        try {
        $b_items = BudgetItem::where('budget_cat_id', $id)->get();
        if (!empty($b_item)) {
            foreach ($b_items as $index => $b_item) {
                BudgetItem::where('id', $b_item->id)->delete();
            }

        }
        BudgetCategory::where('id', $id)->delete();
        return redirect('admin/budget')->with('delete', getMessage('deleted'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('admin/budget')->with('error', getMessage('wrong'));
//        }
    }
}
