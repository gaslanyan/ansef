<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\Institution;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    public function index()
    {
        try {
            $user_id = getUserID();
            $persons = Person::where('user_id', $user_id)->get()->toArray();
            if (empty($persons)) {
                return view('referee.dashboard');
            } else {
                return view('referee.person.index', compact('persons'));
            }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function create()
    {
        $user_id = getUserID();
        try {
            $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();

            $institutions = Institution::all()->pluck('content', 'id')->toArray();
            return view('referee.person.create', compact('countries', 'institutions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function edit($id)
    {
        $user_id = getUserID();
        try {
            $person = Person::where('id', '=', $id)->first();
            $address = Address::firstOrCreate([
                'addressable_id' => $person->id,
                'addressable_type' => 'App\Models\Person'
            ], [
                'street' => '',
                'province' => '',
                'city' => ''
            ]);
            $countries = Country::all();
            $address->save();
            return view('referee.person.edit', compact('address', 'person', 'id', 'countries'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        try {
            $v = Validator::make($request->all(), [
                'first_name' => 'required|max:255',
                'last_name' => 'required|max:255',
                'birthdate' => 'required|date|date_format:Y-m-d',
                'nationality' => 'required|max:255',
                'sex' => 'required|max:15',
                'country_id' => 'required|max:255',
                'province' => 'required|max:255',
                'street' => 'required|max:255',
                'city' => 'required|max:255',
            ]);
            if (!$v->fails()) {
                $person = Person::find($id);
                $person->first_name = $request->first_name;
                $person->last_name = $request->last_name;
                $person->birthdate = $request->birthdate;
                $person->nationality = $request->nationality;
                $person->sex = $request->sex;
                $person->save();
                $address = $person->addresses()->first();
                $address->street = $request->street;
                $address->province = $request->province;
                $address->city = $request->city;
                $address->country_id = $request->country_id;
                $address->save();

                return redirect()->back()->withInput();
            }
            else
                return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->withErrors($v->errors());
        }
    }

    public function changePassword()
    {
        $user_id = getUserID();
        return view('referee.person.changepassword');
    }

    public function updatePassword(Request $request)
    {
        try {
                $user_id = getUserID();
                $this->validate($request, [
                    'oldpassword' => 'required',
                    'newpassword' => 'required|min:8',
                    'confirmpassword' => 'required|same:newpassword',
                ]);
                $user = User::find($user_id);

                if (!Hash::check($request->oldpassword, $user->password)) {
                    return back()
                        ->with('error', 'The specified password does not match the database password');
                } else {
                    $user->password = bcrypt($request->newpassword);
                    $user->save();
                    return \Redirect::to('logout')->with('success', messageFromTemplate("success"));
                }
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', messageFromTemplate("wrong"));
        }
    }
}
