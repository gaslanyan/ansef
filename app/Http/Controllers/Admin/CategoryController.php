<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Proposal;
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
        $v = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'abbreviation' => 'required|max:20',
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
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
    }

    public function destroy($id)
    {
        if (Category::where('parent_id', $id)->exists()) {
            Category::where('parent_id', $id)->delete();
        }
        if (Category::find($id)->exists())
            Category::find($id)->delete();
        return redirect('admin/category')->with('delete', messageFromTemplate('deleted'));
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

    private function proposalcategories() {
        $cats = Proposal::select('categories')->get();
        $selected_cat = [];
        foreach ($cats as $index => $cat) {
            $j_c = json_decode($cat->categories, true);
            foreach ($j_c as $i => $item) {
                $selected_cat[] = $item[0];
            }
        }
        return $selected_cat;
    }

    public function deleteCats(Request $request)
    {
        DB::beginTransaction();
        try {
            $cat_ids = $request->id;
            $selected_cat = $this->proposalcategories();
            foreach ($cat_ids as $ii => $c) {
                if (!in_array($c, $selected_cat)) {
                    $cc = Category::where('id', $c)->first();
                    if($cc->parent_id == null) {
                        $subcats = Category::select('id')
                                            ->where('parent_id','=', $cc->id)->get()->toArray();
                        foreach ($subcats as $jj => $sc) {
                            if (!in_array($sc, $selected_cat)) {
                                Category::where('id', $sc)->delete();
                            }
                        }
                    }
                    Category::where('id', $c)->delete();
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
        DB::commit();
        return response()->json($response);
    }
}
