<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\BudgetCategory;
use App\Models\Category;
use App\Models\Competition;
use App\Models\Degree;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\ScoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

    public function getCategoriesListForStatistics(Request $request)
    {
        $value = $request->value;
        if($value == 0) exit();

        $content = [];
        $content['comp'] = Competition::where('id','=',$value)->get();
        foreach ($content['comp'] as $comp) {
            $categories = json_decode($comp->categories);
            $cats = [];

            foreach ($categories as $index => $category) {
                $cat = Category::with('children')->where('abbreviation', $category)->get()->first();
                $cats[$cat->id]['parent'] = $cat['title'];
                $cats[$cat->id]['parentabb'] = $cat['abbreviation'];
                $sub = $cat['children'];
                foreach ($sub as $s) {
                    $cats[$cat->id]['sub'][$s->id]['abbreviation'] = $s->abbreviation;
                    $cats[$cat->id]['sub'][$s->id]['title'] = $s->title;
                    $cats[$cat->id]['sub'][$s->id]['id'] = $s->id;
                }
            }
        }

        // Log::debug('value is ' . print_r($cats, true));

        echo json_encode($cats);
        exit();
    }

    public function getCategoriesListForMultipleStatistics(Request $request)
    {
        $value = $request->value;
        if (count($value) == 0) exit();

        $content = [];
        $content['comp'] = Competition::whereIn('id', $value)->get();
        $full_cat = [];
        foreach ($content['comp'] as $comp) {
            $categories = json_decode($comp->categories);
            $cats = [];

            foreach ($categories as $index => $category) {
                $cat = Category::with('children')->where('abbreviation', $category)->get()->first();
                $cats[$cat->id]['parent'] = $cat['title'];
                $cats[$cat->id]['parentabb'] = $cat['abbreviation'];
                // Log::debug('Cat:' . $cat['abbreviation']);
            }

            array_push($full_cat, $cats);
        }
        $result = array();

        foreach ($full_cat as $key => $value) {
            if (!is_array($value)) {
                if (!in_array($value, $result))
                    $result[$key] = $value;
            }
            else {
                foreach ($value as $k => $v) {
                    if (!in_array($v, $result))
                       $result[$k] = $v;
                }
            }
        }

        echo json_encode($result);
        exit();
    }
}
