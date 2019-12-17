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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = getUserID();

        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        //$phones= [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            //$phones = Phone::where('person_id', $p_id)->get()->toArray();
        }

        $institutions = Institution::with('address')->get();
        $address = Country::with('address')->get();
        return view('institution.index', compact('institutions', 'address', 'cities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user_id = getUserID();
        $ins_array = [];
        $institutions_list = Institution::all()->toArray();
        $institution_person = InstitutionPerson::where('person_id','=', $id)->orderBy('start', 'DESC')->get()->sortBy('end');

        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $person = Person::where('id',$id )->get()->toArray();
        return view('applicant.institution.create', compact('id','institutions_list','person','ins_array','institution_person'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = getUserID();
        $institution = Institution::with('address')->find($id);
        $address = Country::with('address')->find($institution->address->country_id);

        return view('institution.view', compact('institution', 'address'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = getUserID();
        $institution = Institution::with('address')->find($id);
        $address = Country::with('address')->find($institution->address->country_id);

        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        return view('institution.edit', compact('institution', 'address', 'countries', 'id'));
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

    // public function update(Request $request, $id)
    // {
    //     try {
    //         $this->validate($request, [
    //             'content' => 'required|min:3',
    //         ]);

    //         $institutions = Institution::find($id);

    //         foreach ($request->institution as $key => $val) {
    //             $institution = new InstitutionPerson();
    //             $institution->person_id = $request->institution_creare_hidden;
    //             $institution->institution_id = (int) $request->institution[$key];;
    //             $institution->title = $request->i_title[$key];
    //             $institution->type = $request->i_type[$key];
    //             $institution->start = $request->start[$key];
    //             $institution->end = $request->end[$key];
    //             $institution->save();
    //         }

    //         return \Redirect::back()->with('success', messageFromTemplate("success"));
    //     } catch (\Exception $exception) {
    //         DB::rollBack();
    //         logger()->error($exception);
    //         //            throw $exception;
    //         return \Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $template = Institution::find($id);
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
            $degree = InstitutionPerson::find($id);
            if(!empty($degree)) $degree->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }

}
