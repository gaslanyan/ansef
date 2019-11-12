<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\City;
use App\Models\Country;
use App\Models\Institution;
use App\Models\InstitutionPerson;
use App\Models\Person;
use App\Models\PersonAddress;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

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
            return redirect('applicant/person')->with('wrong', getMessage("wrong"));
        } catch (\Exception $exception) {
            DB::rollBack();
            logger()->error($exception);
            return redirect('applicant/person')->with('wrong', getMessage("wrong"));
        }

        try {
            foreach ($request->countries as $key => $val) {

                $country = Country::where('cc_fips', '=', $request->countries[$key])->first();
                $address = new Address();
                $address->country_id = (int)$country->id;
                if ((int)$request->city_id[$key] === -1) {
                    $city = new City();
                    $city->name = $request->city[$key];
                    $city->cc_fips = $request->countries[$key];
                    $city->save();
                    $city_id = $city->id;
                    $address->city_id = $city_id;
                } else
                    $address->city_id = (int)$request->city_id[$key];
                $address->province = $request->provence[$key];
                $address->street = $request->street[$key];
                $address->save();
                $address_id = $address->id;
                if ($address_id) {
                    $pa = new PersonAddress();
                    $pa->person_id = $person_id;
                    $pa->address_id = $address_id;
                    $pa->save();
                }
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
        $user_id = chooseUser();
        if($person->user_id != $user_id || $person->type == null) {
            // Permission denied
            return redirect('applicant/account')->with('wrong', 'Permission denied');
        }
        else {
            $emails = $person->emails;
            $addresses = $person->addresses;
            $institutions = $person->institutions;
            $degrees = $person->degrees;
            $honors = $person->honors;
            $books = $person->books;
            $meetings = $person->meetings;
            $publications = $person->publications;
            $disciplines = $person->disciplines;
            
            return view('applicant.person.show', compact('person', 'addresses', 'emails', 'institutions', 'honors', 'degrees', 'meetings', 'books', 'disciplines', 'publications'));
        }
        // $person = Person::where('id', $pid)
        //                 ->where('user_id', $user_id)
        //                 ->where('persons.type', '!=', null)
        //                 ->first();

    }
    
    public function download(Person $person)
    {
        $person = null;
        return view('applicant.person.show', compact('person'));
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

        $getaddress = \DB::table('person_address')
            ->select('cities.id as cid', 'address.province', 'address.street', 'countries.country_name', 'cities.name')
            ->join('address', 'address.id', '=', 'person_address.address_id')
            ->join('countries', 'countries.id', '=', 'address.country_id')
            ->join('cities', 'cities.id', '=', 'address.city_id')
            ->where('person_address.person_id', '=', $person->id)
            ->get()->toArray();
        $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();

        // $cites = City::where('cc_fips', '=', $request['cc_fips'])->pluck('name', 'id');
        foreach ($getaddress as $address_item) {
            $adddress['country'] = $address_item->country_name;
            $adddress['city'] = $address_item->name;
            $adddress['city_id'] = $address_item->cid;
            $adddress['street'] = $address_item->street;
            $adddress['province'] = $address_item->province;
            array_push($fulladddress, $adddress);
        }
        return view('applicant.person.edit', compact('fulladddress', 'person', 'id', 'countries'));
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
            $person->save();
            $person_id = $person->id;
            if (!empty($request->countries)) {
                // $pa = PersonAddress::where('person_id', '=', $id)->get()->toArray();
                // foreach ($pa as $item) {
                //     $personAd = PersonAddress::find($item['id']);
                //     $addr = Address::where('id', '=', $item['address_id'])->get()->toArray();
                //     foreach ($addr as $i) {
                //         $a = Address::find($i['id']);
                //         $a->delete();
                //     }
                //     $personAd->delete();
                // }
//                 foreach ($request->countries as $key => $val) {

//                     $country = Country::where('cc_fips', '=', $request->countries[$key])->first();
// //                    $city = City::where('name', '=', $request->city[$key])->first();

//                     $address = new Address();
//                     $address->country_id = (int)$country->id;
//                     if ((int)$request->city_id[$key] === -1) {
//                         $city = new City();
//                         $city->name = $request->city[$key];
//                         $city->cc_fips = $request->countries[$key];
//                         $city->save();
//                         $city_id = $city->id;
//                         $address->city_id = $city_id;
//                     } else
//                         $address->city_id = (int)$request->city_id[$key];
//                     $address->province = $request->provence[$key];
//                     $address->street = $request->street[$key];
//                     $address->save();
//                     $address_id = $address->id;
//                     if ($address_id) {
//                         $pa = new PersonAddress();
//                         $pa->person_id = $person_id;
//                         $pa->address_id = $address_id;
//                         $pa->save();
//                     }

//                 }
            }
            /*
                     foreach ($pa  as $item) {
                         $address = Address::find($item['address_id']);
                         foreach ($request->countries as $key => $val) {
                             $country = Country::where('cc_fips', '=', $request->countries[$key])->first();

                             $address->country_id = (int)$country->id;
                             $address->city_id = (int)$request->city[$key];
                             $address->province = $request->provence[$key];
                             $address->street = $request->street[$key];
                             $address->save();
                         }*/
            /*$address_id = $address->id;
            if ($address_id) {
                $pa = new PersonAddress();
                $pa->person_id = $person_id;
                $pa->address_id = $address_id;
                $pa->save();
            }
        }*/
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
        $user_id = chooseUser();
        $this->validate($request, [
            'oldpassword' => 'required',
            'newpassword' => 'required|min:6',
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
            return redirect('applicant/account')->with('delete', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }

    }

}
