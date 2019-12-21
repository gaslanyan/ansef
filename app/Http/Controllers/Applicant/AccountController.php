<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Book;
use App\Models\Country;
use App\Models\Email;
use App\Models\Honor;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = getUserID();
        $applicant_persons = Person::where('user_id', '=', $user_id)
            ->whereIn('type', ['support', 'participant'])
            ->get();

        return view("applicant.account.index", compact('applicant_persons'));
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
        return view('applicant.account.create', compact('countries', 'institutions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
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

        try {
            $person = new Person;
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            if (!empty($request->birthdate)) {
                $person->birthdate = formatDate($request->birthdate);
            }

            $person->birthplace = $request->birthplace;
            $person->nationality = $request->nationality;
            $person->sex = $request->sex;
            $person->state = $request->state;
            $person->type =$request->type;
            $person->specialization = $request->specialization;
            $person->user_id = $user_id;
            $person->save();

            return redirect()->action('Applicant\AccountController@index');
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
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
        $user_id = getUserID();
        $user = User::find($id);
        $person = Person::where('id', '=', $id)
                        ->where('user_id', '=', $user_id)
                        ->get()->toArray();
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
            $ip_add = Address::where('address.id', 24)
                ->
                join('countries', 'countries.id', '=', 'address.country_id')
                ->get()->toArray();
            $institution['addr'] = $ip_add;


            $books = Book::select('title', 'publisher', 'year')->where('person_id', $person_id)->get()->toArray();

            $degrees = [];
            //DegreePerson::select('text', 'year')->where('person_id', $person_id)->get()->toArray();
            $degrees = \DB::table('degrees_persons')
                ->select('degrees_persons.year','degrees.text','degrees_persons.id')
                ->join('degrees','degrees_persons.degree_id','=','degrees.id')
                ->where('degrees_persons.person_id','=',$id)->get()->toArray();

            $honors = Honor::select('description', 'year')->where('person_id', $person_id)->get()->toArray();
            $meetings = Meeting::select('description', 'year', 'ansef_supported', 'domestic')->where('person_id', $person_id)->get()->toArray();

            return view('applicant.account.show', compact('person',
                'phones', 'emails', 'books', 'degrees', 'honors', 'meetings', 'institution'));
        } else {
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
