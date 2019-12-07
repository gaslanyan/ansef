<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade as PDF;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = getUserID();
        $persons = Person::where('user_id', $user_id)
                        ->where('persons.type', '!=', null)
                        ->get()->toArray();
        if (empty($persons)) {
            return view('applicant.dashboard');
        } else {
            return view('applicant.person.index', compact('persons'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        $institutions = Institution::all()->pluck('content', 'id')->toArray();
        return view('applicant.person.create', compact('countries', 'institutions'));

    }

// VVS
    // public function addresses($pid)
    // {
    //     $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
    //     $institutions = Institution::all()->pluck('content', 'id')->toArray();
    //     return view('applicant.person.create', compact('countries', 'institutions'));
    // }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('applicant')->user();
        $validatedData = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birthdate' => 'required',
            'birthplace' => 'required|min:3',
            'state' => 'required|not_in:Select state',
            'sex' => 'required|not_in:Select sex',
            'nationality' => 'required|not_in:Select country',
        ]);
        DB::beginTransaction();
        try {
            $person = new Person;
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            if (!empty($request->birthdate)) {
                $time = strtotime($request->birthdate);
                $newformat = date('Y-m-d', $time);
                $person->birthdate = $newformat;
            }

            $person->birthplace = $request->birthplace;
            $person->nationality = $request->nationality;
            $person->sex = $request->sex;
            $person->state = $request->state;
            $person->type = $request->type;
            $person->specialization = $request->specialization;
            $person->user_id = $user->id;
            $person->save();
            $person_id = $person->id;
        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('applicant/person')->with('wrong', getMessage("wrong"));
        }



        try {
            foreach ($request->institution as $key => $val) {
                //$institution_name = Institution::where('id', '=', $val)->first();
                $institution = new InstitutionPerson();
                $institution->person_id = $person_id;
                $institution->institution_id = (int)$request->institution[$key];;
                $institution->title = $request->i_title[$key];
                $institution->type = $request->i_type[$key];
                $institution->start = $request->start[$key];
                $institution->end = $request->end[$key];
                $institution->save();
            }
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', getMessage("wrong"))->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return getMessage("wrong");
        }


        DB::commit();
        return redirect('applicant/account')->with('success', getMessage("success"));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        $user_id = getUserID();
        if($person->user_id != $user_id || $person->type == null) {
            // Permission denied
            return redirect('applicant/account')->with('wrong', 'Permission denied');
        }
        else {
            $emails = $person->emails;
            $addresses = $person->addresses;
            $institutions = \App\Models\InstitutionPerson::where('person_id','=',$person->id)
                            ->get()->sortBy('start');
            $institutionslist = \App\Models\Institution::all()->keyBy('id');;
            $degrees = \App\Models\DegreePerson::where('person_id','=',$person->id)
                        ->join('degrees', 'degree_id', '=', 'degrees.id')->get();
            $honors = $person->honors->sortBy('year');
            $books = $person->books->sortBy('year');
            $meetings = $person->meetings->sortBy('year');
            $publications = $person->publications->sortBy('year');

            return view('applicant.person.show', compact('person', 'addresses', 'emails', 'institutions', 'honors', 'degrees', 'meetings', 'books', 'publications', 'institutionslist'));
        }
    }

    public function download($id) {
        $user_id = getUserID();
        $person = Person::find($id);

        if($person->user_id != $user_id || $person->type == null) {
            return redirect('applicant/account')->with('wrong', 'Permission denied');
        }
        else {
            $emails = $person->emails;
            $addresses = $person->addresses;
            $institutions = \App\Models\InstitutionPerson::where('person_id','=',$person->id)
                            ->get()->sortBy('start');
            $institutionslist = \App\Models\Institution::all()->keyBy('id');;
            $degrees = \App\Models\DegreePerson::where('person_id','=',$person->id)
                        ->join('degrees', 'degree_id', '=', 'degrees.id')->get();
            $honors = $person->honors->sortBy('year');
            $books = $person->books->sortBy('year');
            $meetings = $person->meetings->sortBy('year');
            $publications = $person->publications->sortBy('year');

            $pdf = PDF::loadView('applicant.person.pdf', compact('person', 'addresses', 'emails', 'institutions', 'honors', 'degrees', 'meetings', 'books', 'publications', 'institutionslist'));

            return $pdf->download('person-profile.pdf');
            // return $pdf->stream('person-profile.pdf');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $person = Person::where('id', '=', $id)->first();
        $fulladdress = [];
        $getaddress = $person->addresses()->get();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();

        foreach ($getaddress as $address_item) {
            $address['country'] = $address_item->country->country_name;

            $address['street'] = $address_item->street;
            $address['province'] = $address_item->province;
            array_push($fulladdress, $address);
        }
        return view('applicant.person.edit', compact('fulladdress', 'person', 'id', 'countries'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birthdate' => 'required',
            'birthplace' => 'required|min:3',
            'state' => 'required|not_in:Select state',
            'sex' => 'required|not_in:Select sex',
            'nationality' => 'required|not_in:Select country',
            //'email' =>'required|email'
        ]);
        DB::beginTransaction();
        try {
            $person = Person::find($id);
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            if (!empty($request->birthdate)) {
                $time = strtotime($request->birthdate);
                $newformat = date('Y-m-d', $time);
                $person->birthdate = $newformat;
            }

            $person->birthplace = $request->birthplace;
            $person->nationality = $request->nationality;
            $person->sex = $request->sex;
            $person->state = $request->state;
            $person->user_id = $user_id;
            $person->specialization = $request->specialization;
            $person->save();
            $person_id = $person->id;
        DB::commit();

        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return getMessage("wrong");
        }

        /*return redirect('applicant/person')->with('success', getMessage("success"));*/
        return redirect('applicant/account')->with('success', getMessage("success"));

    }

    public function changePassword()
    {

        try {
            return view('applicant.person.changepassword');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }

    public function updatePassword(Request $request)
    {
        $user_id = getUserID();
        $this->validate($request, [
            'oldpassword' => 'required',
            'newpassword' => 'required|min:8',
            'confirmpassword' => 'required|same:newpassword',
        ]);
        $data = $request->all();
        $user = User::find($user_id);
        if (!Hash::check($request->oldpassword, $user->password)) {

            return back()
                ->with('error', 'The specified password does not match the database password');
        } else {

            $user->password = bcrypt($request->newpassword);
            $user->save();
            return \Redirect::to('logout');
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user_id = getUserID();
            $proposals = User::find($user_id)->proposals();
            $flag = true;
            if($flag) {
                $person = Person::find($id);
                $person->delete();
                return redirect('applicant/account')->with('delete', getMessage('deleted'));
            }
            else {
                return redirect('applicant/account')->with('wrong', "Person is member of a project and cannot be deleted.");
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }

    }

}
