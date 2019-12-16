<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Person;
use App\Models\Country;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class AddressController extends Controller
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

        return view('applicant.address.index', compact('persons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $person = Person::where('id', $id)->first();
        $address_list = $person->addresses()->get()->toArray();
        $country_list = Country::all();
        return view('applicant.address.create', compact('person', 'address_list', 'id', 'country_list'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $request->validate([
            'street' => 'required|max:255',
            'province' => 'required|max:255',
            'city' => 'required|max:255',
        ]);
        try {
            $person = Person::where('id','=',$request->hidden_person_id)
                            ->where('user_id','=', $user_id)->first();
            $address = new Address;
            $address->street = $request->street;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->country_id = $request->country;
            $address->save();
            $person->addresses()->save($address);
            return Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    public function show($id)
    { }

    public function edit($id)
    {
        $user_id = getUserID();
        $person = Person::where('id','=',$id)
                        ->where('user_id','=', $user_id)->first();
        $addresses = $person->addresses()->toArray();
        return view('applicant.address.edit', compact('addresses', 'person'));
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        try {
            $this->validate($request, [
                'street.*' => 'required|max:255',
                'province.*' => 'required|max:255',
                'city.*' => 'required|max:255',
            ]);
            for ($i = 0; $i <= count($request->address_list_hidden) - 1; $i++) {
                $address = Address::find($request->address_list_hidden[$i]);
                $address->street = $request->street_list[$i];
                $address->city = $request->city_list[$i];
                $address->province = $request->province_list[$i];
                $address->country_id = $request->country_list[$i];
                $address->save();
            }
            return Redirect::back()->with('success', getMessage("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $address = Address::find($id);
            $address->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }
}
