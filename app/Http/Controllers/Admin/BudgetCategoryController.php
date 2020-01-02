<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\BudgetItem;
use App\Models\Competition;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
            try {
                $v = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'competition_id' => 'required|numeric',
                    'min' => 'required|numeric',
                    'max' => 'required|numeric|max:min',
                    'weight' => 'required|numeric'
                ]);
                if (!$v->fails()) {
                    BudgetCategory::create($request->all());
                    return redirect('admin/budget')->with('success', messageFromTemplate("success"));
                } else
                    return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/budget')->with('errors', messageFromTemplate("wrong"));
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
                return redirect('admin/budget')->with('success', messageFromTemplate("update"));
            } else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/budget')->with('errors', messageFromTemplate("wrong"));
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
        return redirect('admin/budget')->with('delete', messageFromTemplate('deleted'));
        //        } catch (\Exception $exception) {
        //            logger()->error($exception);
        //            return redirect('admin/budget')->with('error', messageFromTemplate('wrong'));
        //        }
    }

    public function deleteBudgets(Request $request)
    {
        DB::beginTransaction();
        try {
            //        if (isset($request->_token)) {
            $budget_ids = $request->id;
            $items = BudgetItem::select('budget_cat_id')->groupBy('budget_cat_id')->get()->toArray();
            $items = array_column($items, 'budget_cat_id');
            foreach ($budget_ids as $index => $item) {
                if (!in_array($item, $items)) {
                    BudgetCategory::where('id', $item)->delete();
                }
            }
            $response = [
                'success' => true
            ];
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

    public function duplicateCats(Request $request)
    {

        DB::beginTransaction();
        try {
            $cats = Category::select('*')->whereIn(
                'id',
                (array) ($request->id)
            )->get()->toArray();
            $_cats = [];
            foreach ($cats as $key => $cat) {
                //            unset($cat['id']);
                if (empty($cat['parent_id'])) {
                    $cat['parent_id'] = null;
                }
                $_cats[$key]['abbreviation'] = $cat['abbreviation'];
                $_cats[$key]['title'] = $cat['title'];
                $_cats[$key]['parent_id'] = $cat['parent_id'];
                $_cats[$key]['created_at'] = $cat['created_at'];
                $_cats[$key]['updated_at'] = $cat['updated_at'];
            }
            Category::insert($_cats);
            $response = [
                'success' => true
            ];
        } catch (\Exception $exception) {
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
            DB::rollBack();
            logger()->error($exception);
        }
        DB::commit();
        return response()->json($response);
        exit();
    }

    public function getBudgetByCategory(Request $request)
    {
        $_id = $request->id;
        $bi = BudgetItem::where('budget_cat_id', $_id)->first();
        if (!empty($bi)) {
            $response = [
                'success' => true,
            ];
        } else {
            $response = [
                'success' => false
            ];
        }
        return response()->json($response);
    }
}
