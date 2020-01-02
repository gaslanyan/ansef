<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Barryvdh\DomPDF\Facade as PDF;

class PersonController extends Controller
{
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

    public function create()
    {
        $user_id = getUserID();
        $countries = Country::all()->sortBy('country_name')->pluck('country_name', 'cc_fips')->toArray();
        $institutions = Institution::all()->sortBy('content')->pluck('content', 'id')->toArray();
        return view('applicant.person.create', compact('countries', 'institutions'));
    }


    public function store(Request $request)
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
            $person->user_id = $user_id;
            $person->save();
            $person_id = $person->id;
        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"));
        }

        try {
            foreach ($request->institution as $key => $val) {
                //$institution_name = Institution::where('id', '=', $val)->first();
                $institution = new InstitutionPerson();
                $institution->person_id = $person_id;
                $institution->institution_id = (int) $request->institution[$key];;
                $institution->title = $request->i_title[$key];
                $institution->type = $request->i_type[$key];
                $institution->start = $request->start[$key];
                $institution->end = $request->end[$key];
                $institution->user_id = $user_id;
                $institution->save();
            }
        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"))->withInput();
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }


        DB::commit();
        return redirect('applicant/account')->with('success', messageFromTemplate("success"));
    }

    public function show(Person $person)
    {
        $user_id = getUserID();
        if ($person->user_id != $user_id || $person->type == null) {
            return redirect('applicant/account')->with('wrong', 'Permission denied');
        } else {
            $emails = $person->emails;
            $phonenums = $person->phones;
            $addresses = $person->addresses;
            $institutions = \App\Models\InstitutionPerson::where('person_id', '=', $person->id)
                ->get()->sortBy('start');
            $institutionslist = \App\Models\Institution::all()->sortBy('content')->keyBy('id');;
            $degrees = \App\Models\DegreePerson::where('person_id', '=', $person->id)
                ->join('degrees', 'degree_id', '=', 'degrees.id')->get()->sortBy('year');
            $honors = $person->honors->sortBy('year');
            $books = $person->books->sortBy('year');
            $meetings = $person->meetings->sortBy('year');
            $publications = $person->publications->sortBy('year');

            return view('applicant.person.show', compact('person', 'addresses', 'emails', 'phonenums', 'institutions', 'honors', 'degrees', 'meetings', 'books', 'publications', 'institutionslist'));
        }
    }

    public function download($id)
    {
        $user_id = getUserID();
        $person = Person::find($id);

        if ($person->user_id != $user_id || $person->type == null) {
            return redirect('applicant/account')->with('wrong', 'Permission denied');
        } else {
            $emails = $person->emails;
            $phones = $person->phones;
            $addresses = $person->addresses;
            $institutions = \App\Models\InstitutionPerson::where('person_id', '=', $person->id)
                ->get()->sortBy('start');
            $institutionslist = \App\Models\Institution::all()->sortBy('content')->keyBy('id');;
            $degrees = \App\Models\DegreePerson::where('person_id', '=', $person->id)
                ->join('degrees', 'degree_id', '=', 'degrees.id')->get()->sortBy('year');
            $honors = $person->honors->sortBy('year');
            $books = $person->books->sortBy('year');
            $meetings = $person->meetings->sortBy('year');
            $publications = $person->publications->sortBy('year');

            $pdf = PDF::loadView('applicant.person.pdf', compact('person', 'addresses', 'emails', 'phones', 'institutions', 'honors', 'degrees', 'meetings', 'books', 'publications', 'institutionslist'));

            return $pdf->download('person-profile.pdf');
            // return $pdf->stream('person-profile.pdf');
        }
    }

    public function edit($id)
    {
        $user_id = getUserID();

        $person = Person::where('id', '=', $id)->where('user_id', '=', $user_id)->first();
        $fulladdress = [];
        $getaddress = $person->addresses()->get();
        $countries = Country::all()->sortBy('country_name')->pluck('country_name', 'cc_fips')->toArray();

        foreach ($getaddress as $address_item) {
            $address['country'] = $address_item->country->country_name;

            $address['street'] = $address_item->street;
            $address['province'] = $address_item->province;
            array_push($fulladdress, $address);
        }
        return view('applicant.person.edit', compact('fulladdress', 'person', 'id', 'countries'));
    }

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
            if ($person->user_id != $user_id) return messageFromTemplate("wrong");
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
            $person->specialization = $request->specialization;
            $person->save();
            DB::commit();
        } catch (ValidationException $e) {
            DB::rollback();
            return redirect('applicant/person')->with('wrong', messageFromTemplate("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }

        return redirect('applicant/account')->with('success', messageFromTemplate("success"));
    }

    public function changePassword()
    {
        $user_id = getUserID();

        try {
            return view('applicant.person.changepassword');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
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

    public function destroy($id)
    {
        try {
            $user_id = getUserID();
            $proposals = User::find($user_id)->proposals();
            $flag = true;
            if ($flag) {
                $person = Person::where('id', '=', $id)
                    ->where('user_id', '=', $user_id)
                    ->first();
                $person->delete();
                return redirect('applicant/account')->with('delete', messageFromTemplate('deleted'));
            } else {
                return redirect('applicant/account')->with('wrong', "Person is member of a project and cannot be deleted.");
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
