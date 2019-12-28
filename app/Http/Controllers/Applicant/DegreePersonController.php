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
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $persons_name = Person::where('id', $id)->where('user_id', '=', $user_id)->where('type', '!=', null)->first();
        $degreesperson = DegreePerson::where('person_id', '=', $id)->where('user_id', '=', $user_id)->orderBy('year', 'DESC')->get();
        $degrees_list = Degree::all();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();
        return view('applicant.degree.create', compact('id', 'degrees_list', 'degreesperson', 'persons_name', 'institutions'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
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
            $degrees->user_id = $user_id;

            if (!empty($request->institution)) {
                $degrees->institution = $request->institution;
            } elseif (!empty($request->institution_id)) {
                $degrees->institution_id = (int) $request->institution_id;
            } else {
                $degrees->institution = '';
                $degrees->institution_id = 0;
            }

            $degrees->save();
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'description.*' => 'required|numeric|min:0|not_in:0',
            'year.*' => 'required|numeric|min:1900|max:2030'
        ]);

        try {
            for ($i = 0; $i <= count($request->year) - 1; $i++) {
                $degreeperson = DegreePerson::find($request->degree_hidden_id[$i]);
                if ($degreeperson->user_id != $user_id) continue;
                $degreeperson->year = ($request->year)[$i];
                $degreeperson->degree_id = ($request->description)[$i];

                if (!empty(($request->institution)[$i]) && ($request->institution)[$i] != "") {
                    $degreeperson->institution = ($request->institution)[$i];
                    $degreeperson->institution_id = 0;
                } elseif (!empty(($request->institution_id)[$i])) {
                    $degreeperson->institution_id = (int) ($request->institution_id)[$i];
                    $degreeperson->institution = "";
                } else {
                    $degreeperson->institution = "";
                }
                $degreeperson->save();
            }

            return \Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $degree = DegreePerson::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
            $degree->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
