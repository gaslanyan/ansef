<?php

namespace App\Http\Controllers\Viewer;

use App\Models\Address;
use App\Models\Country;
use App\Http\Controllers\Controller;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Notifications\UserRegisteredSuccessfully;
use App\Models\Person;
use App\Models\Role;
use App\Models\User;
use App\Models\Email;
use App\Models\Phone;
use App\Models\Book;
use App\Models\Honors;
use App\Models\Meeting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = chooseUser();
        $persons = Person::where('user_id', $user_id)->get()->toArray();
        if (empty($persons)) {
            return view('viewer.dashboard');
        } else {
            return view('viewer.person.index', compact('persons'));
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
        return view('viewer.person.create', compact('countries', 'institutions'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::guard('viewer')->user();
        $validatedData = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birthdate' => 'required',
            'birthplace' => 'required|min:3',
            'state' => 'required|not_in:Select state',
            'sex' => 'required|not_in:Select sex',
            'nationality' => 'required|not_in:Select country',
            //'nationality' => 'required|in:Armenia',
            'email.*' =>'required|email'
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
            $person->user_id = $user->id;
            $person->save();
            $person_id = $person->id;
        } catch (ValidationException $e) {
            // Rollback and then redirect back to form with errors
            DB::rollback();
            return redirect('viewer/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('viewer/person')->with('wrong', getMessage("wrong"));
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
            return redirect('viewer/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return getMessage("wrong");
        }


        DB::commit();
        return redirect('viewer/person')->with('success', getMessage("success"));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $person = Person::where('id', '=', $id)->get()->toArray();
        if (!empty($person)) {
            $person_id = $id;//$person[0]['id'];
            $emails = Email::where('person_id', $person_id)->get();
            $phones = Phone::where('person_id', $person_id)->get()->toArray();
            $institution = [];
            $ip = InstitutionPerson::with('iperson')
                ->select('title', 'start', 'end', 'type')
                ->where('person_id', '=', $person_id)
                ->get()->toArray();
            $institution['ip'] = $ip;
            $institution['addr'] = $ip_add;
            $books = Book::select('title', 'publsher', 'year')->where('person_id', $person_id)->get()->toArray();
            $disciplines = \DB::table('disciplines_persons')
                ->select('disciplines.text')
                ->join('disciplines','disciplines.id','=','disciplines_persons.discipline_id')
                ->where('disciplines_persons.person_id','=', $person_id)->get()->toArray();

            /*$disciplines = Discipline::select('text')->
                           join('disciplines_persons', )->where('person_id', $person_id)->get()->toArray();*/
            $degrees = [];
            //DegreePerson::select('text', 'year')->where('person_id', $person_id)->get()->toArray();
            $degrees = \DB::table('degrees_persons')
                ->select('degrees_persons.year','degrees.text','degrees_persons.id')
                ->join('degrees','degrees_persons.degree_id','=','degrees.id')
                ->where('degrees_persons.person_id','=',$id)->get()->toArray();
            $honors = Honors::select('description', 'year')->where('person_id', $person_id)->get()->toArray();
            $meetings = Meeting::select('description', 'year', 'ansef_supported', 'domestic')->where('person_id', $person_id)->get()->toArray();

            return view('viewer.person.show', compact('person',
                'phones', 'emails', 'books', 'degrees', 'honors', 'meetings', 'disciplines', 'institution'));
        } else {
            return back();
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
        $fulladddress = [];
        $getaddress = $person->addresses()->get()->toArray();

        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();
        foreach ($getaddress as $address_item) {
            $adddress['country'] = $address_item->country_name;
            $adddress['street'] = $address_item->street;
            $adddress['province'] = $address_item->province;
            array_push($fulladddress, $adddress);
        }
        return view('viewer.person.edit', compact('fulladddress', 'person', 'id', 'countries'));
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


        $user_id = chooseUser();
        /* $person = Person::where('user_id', $user_id)->get()->toArray();
        $p_id = $person[0]['id'];*/
        $validatedData = $request->validate([
            'first_name' => 'required|alpha|min:3',
            'last_name' => 'required|alpha|min:3',
            'birthdate' => 'required',
            'birthplace' => 'required|alpha|min:3',
            'state' => 'required|not_in:Select state',
            'sex' => 'required|not_in:Select sex',
            'nationality' => 'required|in:Armenia',
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
            $person->save();
            $person_id = $person->id;

        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect('viewer/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return getMessage("wrong");
        }

        /*  try {
              foreach ($request->institution as $key => $val) {
                  //$institution_name = Institution::where('id', '=', $val)->first();
                  $institution = new InstitutionsPersons();
                  $institution->person_id = $person_id;
                  $institution->institution_id =(int)$request->institution[$key];;
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
              return redirect('applicant/person')->with('wrong', getMessage("wrong"));
          } catch (\Exception $exception) {
              DB::rollBack();
              logger()->error($exception);
              return getMessage("wrong");
          }*/
        DB::commit();
        /*return redirect('applicant/person')->with('success', getMessage("success"));*/
        return redirect('viewer/account')->with('success', getMessage("success"));

    }

    public function changePassword()
    {

        return view('viewer.person.changepassword');
    }

    public function updatePassword(Request $request)
    {
        $user_id = chooseUser();
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
            $person = Person::find($id);
            $person->delete();
            return redirect('viewer/account')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }

    }

}
