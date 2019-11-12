<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Discipline;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Redirect;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $disciplines = Discipline::all();
            return view('admin.discipline.index', compact('disciplines'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/discipline')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.discipline.create');
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
            return view('admin.discipline.create');
        else {
        try {
            $val = Validator::make($request->all(), [
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {
                Discipline::create($request->all());
                return redirect('admin/discipline')->with('success', getMessage("success"));
            } else
                return redirect()->back()->withErrors($val->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/discipline')->with('error', getMessage("wrong"));
        }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Discipline $discipline
     * @return \Illuminate\Http\Response
     */
    public function show(Discipline $discipline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Discipline $discipline
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        try {
            $discipline = Discipline::find($id);
            $persons = Person::select('first_name', 'last_name')
                ->where('id', '=', $discipline->person_id)->get()->toArray();

            return view('admin.discipline.edit', compact('discipline', 'id', 'persons'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/discipline')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Discipline $discipline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (!$request->isMethod('post'))
            return view('admin.discipline.edit');
        else {
        try {
            $val = Validator::make($request->all(), [
                'text' => 'required|max:511|min:6',
            ]);
            if (!$val->fails()) {
            $discipline = Discipline::find($id);
            $discipline->text = $request->text;
            $discipline->save();
            return Redirect::back()->with('success', getMessage("success"));
            } else
                return redirect()->back()->withErrors($val->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/discipline')->with('error', getMessage("wrong"));
        }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Discipline $discipline
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $discipline = Discipline::find($id);
            $discipline->delete();
            return redirect('admin/discipline')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/discipline')->with('error', getMessage("wrong"));
        }
    }
}
