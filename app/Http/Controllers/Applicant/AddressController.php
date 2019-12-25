<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Person;
use App\Models\Country;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $person = Person::where('id', $id)->where('user_id','=',$user_id)->first();
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
            $address->user_id = $user_id;
            $address->save();
            $person->addresses()->save($address);
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    { }

    public function edit($id)
    {
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
                if ($address->user_id != $user_id) continue;
                $address->street = $request->street_list[$i];
                $address->city = $request->city_list[$i];
                $address->province = $request->province_list[$i];
                $address->country_id = $request->country_list[$i];
                $address->save();
            }
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $address = Address::where('id','=',$id)->where('user_id','=',$user_id)->first();
            if(!empty($address)) $address->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
