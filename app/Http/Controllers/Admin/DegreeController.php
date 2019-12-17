<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $degrees = Degree::all();
            return view('admin.degree.index', compact('degrees'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/degree')->with('error', messageFromTemplate("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.degree.create');
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
            return view('admin.degree.create');
        else {
            try {
                $val = Validator::make($request->all(), [
                    'text' => 'required|max:255|min:6',
                ]);
                if (!$val->fails()) {
                    $degrees = new Degree();
                    $degrees->text = $request->text;
                    $degrees->save();
                    return redirect('admin/degree')->with('success', messageFromTemplate("success"));
                } else
                    return redirect()->back()->withErrors($val->errors())->withInput();
            } catch (\Exception $exception) {
                logger()->error($exception);
                return redirect('admin/degree')->with('error', messageFromTemplate("wrong"));
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
            $degree = Degree::find($id);
            return view('admin.degree.edit', compact('degree', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/degree')->with('error', messageFromTemplate("wrong"));
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
            $val = Validator::make($request->all(), [
                'text' => 'required|max:255|min:6',
            ]);
            if (!$val->fails()) {
                $degrees = Degree::find($id);
                $degrees->text = $request->text;
                $degrees->save();
                return redirect('admin/degree')->with('success', messageFromTemplate("update"));
            } else
                return redirect()->back()->withErrors($val->errors())->withInput();

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/degree')->with('error', messageFromTemplate("wrong"));
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
            $degree = Degree::find($id);
            $degree->delete();
            return redirect('admin/degree')->with('delete', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/degree')->with('error', messageFromTemplate("wrong"));
        }
    }
}
