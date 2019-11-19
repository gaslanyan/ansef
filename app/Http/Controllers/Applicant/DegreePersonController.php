<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Degree;
use App\Models\DegreePerson;
use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Institution;
use Illuminate\Http\Request;
use Redirect;

class DegreePersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id)->get()->toArray();
        $dp = DegreePerson::with('degree')->find();


        $degrees = [];
        if (!empty($person_id[0]['id'])) {
            $p_id = $person_id[0]['id'];
            $degrees = DegreePerson::where('person_id', $p_id)->get()->toArray();
        }
        return view('applicant.degree.index', compact('degrees'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $persons_name = Person::where('id', $id)->where('type', '!=', null)->first();
        $degreesperson = DegreePerson::where('person_id','=',$id)->get();
        $degrees_list = Degree::all();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();
        return view('applicant.degree.create', compact('id', 'degrees_list', 'degreesperson', 'persons_name', 'institutions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|numeric|min:0|not_in:0',
            'year' => 'required|numeric|min:1900|max:2030'
        ]);

        try {
            $p_id = $request->degrees_add_hidden_id;
            $degrees = new DegreePerson;
            $degrees->person_id = $p_id;
            $degrees->degree_id = $request->description;
            $degrees->year = $request->year;

            if (!empty($request->institution)) {
                $degrees->institution = $request->institution;
            } elseif (!empty($request->institution_id)) {
                $degrees->institution_id = (int) $request->institution_id;
            } else {
                $degrees->institution = '';
                $degrees->institution_id = 0;
            }

            $degrees->save();
            return Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        /* $person_id = Person::where('user_id', $id)->get()->toArray();
        $dp = DegreePerson::with('degree')->find($person_id['id']);
        dd($dp);*/ }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $degree = DegreePerson::find($id);
        return view('applicant.degree.edit', compact('degree', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'description.*' => 'required|numeric|min:0|not_in:0',
            'year.*' => 'required|numeric|min:1900|max:2030'
        ]);

        try {
            for ($i = 0; $i <= count($request->year) - 1; $i++) {
                $degreeperson = DegreePerson::find($request->degree_hidden_id[$i]);
                $degreeperson->year = ($request->year)[$i];
                $degreeperson->degree_id = ($request->description)[$i];

                if (!empty(($request->institution)[$i]) && ($request->institution)[$i] != "") {
                    $degreeperson->institution = ($request->institution)[$i];
                    $degreeperson->institution_id = 0;
                }
                elseif (!empty(($request->institution_id)[$i])) {
                    $degreeperson->institution_id = (int) ($request->institution_id)[$i];
                    $degreeperson->institution = "";
                }
                else {
                    $degreeperson->institution = "";
                }

                $degreeperson->save();
            }

            return \Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $degree = DegreePerson::find($id);
            $degree->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
