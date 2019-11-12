<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Book;
use App\Models\City;
use App\Models\Country;
use App\Models\Discipline;
use App\Models\Email;
use App\Models\Honors;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Meeting;
use App\Models\Person;
use App\Models\Person_groups;
use App\Models\PersonAddress;
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

        $user_id = chooseUser();
        $person = Person::where('user_id', '=', $user_id)->first();

        $applicant_persons = \DB::table('persons')
            ->select('*')
            //  ->join('person_address', 'person_address.person_id', '=', 'persons.id')
            //  ->join('address', 'address.id', '=', 'person_address.address_id')
            //  ->join('institutions_persons', 'institutions_persons.person_id', '=', 'persons.id')
            ->where('persons.user_id', '=', $user_id)
            ->where('persons.type', '!=', null)
            ->get()->toArray();

        return view("applicant.account.index", compact('applicant_persons', 'applicant_persons'));
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
        $user_id = chooseUser();
        // $person_id = Person::where('user_id', $user_id)->first();
        $person_id = getUserId(null);
        $validatedData = $request->validate([
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'birthdate' => 'required',
            'birthplace' => 'required|min:3',
            'state' => 'required|not_in:Select state',
            'sex' => 'required|not_in:Select sex',
            'nationality' => 'required|not_in:Select country',
        ]);

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
        $person->user_id = $user_id;
        $person->save();
        
        $person_group_id = $person->id;
        if (!empty($person_group_id)) {
            $person_groups = new Person_groups();
            $person_groups->parent_person_id = $person_id;
            $person_groups->group_person_id = $person_group_id;
            $person_groups->save();
        }
        
        // // Add at least one address
        // $address = new Address();
        // $address->save();
        // $address_id = $address->id;
        //     if ($address_id) {
        //         $pa = new PersonAddress();
        //         $pa->person_id = $person_group_id;
        //         $pa->address_id = $address_id;
        //         $pa->save();
        //     }


        // foreach ($request->countries as $key => $val) {
        //     if($val == 0) break;
        //     $address = new Address();
        //     $country = Country::where('cc_fips', '=', $request->countries[$key])->first();
        //     $address->country_id = (int)$country->id;
        //     if ((int)$request->city_id[$key] === -1) {
        //         $city = new City();
        //         $city->name = $request->city[$key];
        //         $city->cc_fips = $request->countries[$key];
        //         $city->save();
        //         $city_id = $city->id;
        //         $address->city_id = $city_id;
        //     } else
        //         $address->city_id = (int)$request->city_id[$key];
        //     $address->province = $request->provence[$key];
        //     $address->street = $request->street[$key];
        //     $address->save();
        //     $address_id = $address->id;

        //     if ($address_id) {
        //         $pa = new PersonAddress();
        //         $pa->person_id = $person_group_id;
        //         $pa->address_id = $address_id;
        //         $pa->save();
        //     }
        // }
        
        // foreach ($request->institution as $key => $val) {
        //     //$institution_name = Institution::where('id', '=', $val)->first();
        //     $institution = new InstitutionPerson();
        //     $institution->person_id = $person_group_id;
        //     $institution->institution_id = (int)$request->institution[$key];;
        //     $institution->title = $request->i_title[$key] ?? '';
        //     $institution->start = formatDate($request->start[$key]);
        //     $institution->end = formatDate($request->end[$key]);
        //     // $institution->end = $request->end[$key];
        //     $institution->type = $request->i_type[$key];
        //     $institution->save();
        // }

      return redirect()->action('Applicant\InfoController@index');

       // return redirect()->action('Applicant\EmailController@index');
//        return Redirect::back()->with('success', getMessage("success"));

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
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
            $addr = PersonAddress::where('person_id', $person_id)->get()->toArray();
            $address_array = [];
            foreach ($addr as $key => $value) {
                $country = Country::with('address')->find($value['address_id'])->toArray();
                $address = Address::find($value['address_id'])->toArray();
                $address_array[$key]['country'] = $country['country_name'];
                $address_array[$key]['province'] = $address['province'];
                $address_array[$key]['city'] = City::with('address')->find($country['cc_fips']);
                $address_array[$key]['street'] = $address['street'];
            }
            $institution = [];
            $ip = InstitutionPerson::with('iperson')
                ->select('title', 'start', 'end', 'type')
                ->where('person_id', '=', $person_id)
                ->get()->toArray();
            $institution['ip'] = $ip;
            $ip_add = Address::where('address.id', 24)
                ->
                join('countries', 'countries.id', '=', 'address.country_id')
                ->
                join('cities', 'cities.id', '=', 'address.city_id')
                ->get()->toArray();
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

            return view('applicant.account.show', compact('person',
                'phones', 'emails', 'address_array', 'books', 'degrees', 'honors', 'meetings', 'disciplines', 'institution'));
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
