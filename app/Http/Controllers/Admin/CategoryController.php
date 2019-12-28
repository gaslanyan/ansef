<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $categories = Category::with('children')->get()->toArray();
            $parents = Category::whereNull('parent_id')->get();
            return view('admin.category.index', compact('categories', 'parents'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/category')->with('error', messageFromTemplate('wrong'));
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
            $parents = Category::whereNull('parent_id')->get();
            return view('admin.category.create', compact('parents'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/category')->with('error', messageFromTemplate('wrong'));
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
            return view('admin.category.create');
        else {
            try {
                $v = Validator::make($request->all(), [
                    'title' => 'required|max:255',
                    'abbreviation' => 'required|max:255',
                ]);
                if (!$v->fails()) {
                    $category = new Category();
                    if ($request->parent_id == 0)
                        $category->parent_id = null;
                    else
                        $category->parent_id = $request->parent_id;
                    $category->abbreviation = $request->abbreviation;
                    $category->title = $request->title;
                    $category->save();
                    return redirect('admin/category')->with('success', messageFromTemplate("success"));
                } else
                    return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/category')->with('error', messageFromTemplate('wrong'));
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
        //
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
        //
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
        if (Category::where('parent_id', $id)->exists()) {
            Category::where('parent_id', $id)->delete();
        }
        if (Category::find($id)->exists())
            Category::find($id)->delete();
        //            $category = Category::find($id);
        //            $cats = Proposal::select('categories')->get();
        //            $selected_cat = [];
        //            foreach ($cats as $index => $cat) {
        //                $j_c = json_decode($cat->categories, true);
        //                foreach ($j_c as $i => $item) {
        //                    $selected_cat[] = $item[0];
        //                }
        //            }
        ////            dd(in_array($category->id, $selected_cat));
        //            if (!in_array($category->id, $selected_cat)) {
        //
        //                Category::where('id', $category->id)->delete();
        return redirect('admin/category')->with('delete', messageFromTemplate('deleted'));
        //            } else {
        //
        //            }

        //        } catch
        //        (\Exception $exception) {
        //            logger()->error($exception);
        //            return redirect('admin/category')->with('error', messageFromTemplate('wrong'));
        //        }
    }

    public function updateCategory(Request $request)
    {
        $items = json_decode($request->form);
        $category = Category::where('id', '=', $items->id)->first();
        $category->id = $items->id;
        if ($items->parent_id == 0)
            $category->parent_id = null;
        else
            $category->parent_id = $items->parent_id;
        $category->abbreviation = $items->abbreviation;
        $category->title = $items->title;
        if ($category->save()) {
            $response = [
                'success' => true
            ];
        } else
            $response = [
                'success' => false,
                'error' => 'Do not save'
            ];
        return response()->json($response);
    }

    public function deleteCats(Request $request)
    {
        DB::beginTransaction();
        try {
            //        if (isset($request->_token)) {
            $cat_ids = $request->id;
            $cats = Proposal::select('categories')->get();
            $selected_cat = [];
            foreach ($cats as $index => $cat) {
                $j_c = json_decode($cat->categories, true);
                foreach ($j_c as $i => $item) {
                    $selected_cat[] = $item[0];
                }
            }
            foreach ($cat_ids as $ii => $c) {
                if (!in_array($c, $selected_cat))
                    Category::where('id', $c)->delete();
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
}
