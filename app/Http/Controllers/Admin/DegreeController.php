<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Degree;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DegreeController extends Controller
{
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

    public function create()
    {
        return view('admin.degree.create');
    }

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

    public function show($id)
    {
    }

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
