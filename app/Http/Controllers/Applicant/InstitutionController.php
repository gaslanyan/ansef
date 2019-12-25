<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;

class InstitutionController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $ins_array = [];
        $institutions_list = Institution::all()->toArray();
        $institution_person = InstitutionPerson::where('person_id','=', $id)
                                                ->where('user_id','=',$user_id)
                                                ->orderBy('start', 'DESC')->get()->sortBy('end');

        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $person = Person::where('id',$id )->where('user_id','=',$user_id)->get()->toArray();
        return view('applicant.institution.create', compact('id','institutions_list','person','ins_array','institution_person'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $this->validate($request, [
            'i_title' => 'required',
            'i_type' => 'required',
            'start' => 'required',
        ]);

        try {
            $institution = new InstitutionPerson();
            $institution->person_id = $request->institution_create_hidden;
            $institution->title = $request->i_title;
            $institution->type = $request->i_type;
            $institution->start = $request->start;
            $institution->end = $request->end;
            $institution->user_id = $user_id;

            if (!empty($request->institution)) {
                $institution->institution = $request->institution;
            } elseif (!empty($request->institution_id)) {
                $institution->institution_id = (int) $request->institution_id;
            } else {
                $institution->institution = '';
                $institution->institution_id = 0;
            }

            $institution->save();
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"))->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"))->withInput();
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
        $this->validate($request, [
            'i_title.*' => 'required',
            'i_type.*' => 'required',
            'start.*' => 'required',
        ]);

        try {

            $count = count($request->inst_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $inspers = InstitutionPerson::find(($request->inst_hidden_id)[$i]);
                if ($inspers->user_id != $user_id) continue;
                $inspers->title = ($request->i_title)[$i];
                $inspers->start = ($request->start)[$i];
                $inspers->end = ($request->end)[$i];
                $inspers->type = ($request->i_type)[$i];

                if (!empty(($request->institution)[$i]) && ($request->institution)[$i] != "") {
                    $inspers->institution = ($request->institution)[$i];
                    $inspers->institution_id = 0;
                } elseif (!empty(($request->institution_id)[$i])) {
                    $inspers->institution_id = (int) ($request->institution_id)[$i];
                    $inspers->institution = "";
                } else {
                    $inspers->institution = "";
                    $inspers->institution_id = 0;
                }

                $inspers->save();
            }

            return \Redirect::back()->with('success', messageFromTemplate("success"));

        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            //            throw $exception;
            return \Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();

        }
    }


    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $template = Institution::where('id','=',$id)
                                    ->where('user_id','=',$user_id)
                                    ->first();

            $template->delete();
            return redirect('admin/institution')->with('success', messageFromTemplate('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }

    public function destroyemployment($id)
    {
        $user_id = getUserID();
        try {
            $degree = InstitutionPerson::where('id','=',$id)
                                        ->where('user_id','=',$user_id)
                                        ->first();
            if(!empty($degree)) $degree->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }

}
