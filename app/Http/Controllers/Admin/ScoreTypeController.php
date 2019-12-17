<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Score;
use App\Models\ScoreType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScoreTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $scoreTypes = ScoreType::all();
            $competition = Competition::all()->pluck('title', 'id');;
            return view('admin.scoreType.index', compact('scoreTypes', 'competition'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/score')->with('error', messageFromTemplate("wrong"));
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
            $st = ScoreType::with('competition')->get();
            return view('admin.scoreType.create',
                compact('competition', 'st'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/score')->with('error', messageFromTemplate("wrong"));
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
            return view('admin.scoreType.create');
        else {
            try {
                $v = Validator::make($request->all(), [
                    'name' => 'required|max:255',
                    'competition_id' => 'required|numeric',
                    'description' => 'required|max:1024',
                    'min' => 'required|numeric',
                    'max' => 'required|numeric|max:min',
                    'weight' => 'required|numeric',
                ]);
                if (!$v->fails()) {
                    $count = ScoreType::where('competition_id', $request->competition_id)->get()->count();
                    if ($count <= 7) {
                        ScoreType::create($request->all());
                        return redirect('admin/score')->with('success', messageFromTemplate("success"));
                    } else {
                        return redirect('admin/score')->with('error', messageFromTemplate("score_count"));
                    }
                } else
                    return redirect()->back()->withErrors($v->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/score')->with('errors', messageFromTemplate("wrong"));
            }
        }
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

            $scoreType = ScoreType::find($id);
            $competition = Competition::all()->pluck('title', 'id');
            return view('admin.scoreType.edit', compact('scoreType', 'competition'));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/score')->with('error', messageFromTemplate("wrong"));
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
                'description' => 'required|max:1024',
                'min' => 'required|numeric',
                'max' => 'required|numeric|max:min',
                'weight' => 'required|numeric',
            ]);
            if (!$v->fails()) {
                $scoreType = ScoreType::findOrFail($id);
                $scoreType->fill($request->all())->save();
                return redirect('admin/score')->with('success', messageFromTemplate("update"));
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();
        } catch (\Exception $exception) {

            logger()->error($exception);
            return redirect()->back()->with('errors', messageFromTemplate("wrong"));
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
        try {

            $score = Score::where('score_type_id', $id)->get()->toArray();
            if (empty($score)) {
                ScoreType::where('id', $id)->delete();
                return redirect('admin/score')->with('delete', messageFromTemplate('deleted'));
            } else {
                return redirect('admin/score')->with('error', messageFromTemplate('dont_deleted'));
            }

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/score')->with('error', messageFromTemplate('wrong'));
        }
    }
}
