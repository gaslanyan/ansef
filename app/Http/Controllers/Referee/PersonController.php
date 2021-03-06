<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Country;
use App\Models\Person;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PersonController extends Controller
{
    public function index()
    {
        try {
            $user_id = getUserID();
            $persons = Person::where('user_id', $user_id)
                                // ->where('type','=','referee')
                                ->get()->toArray();
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
    }

    public function edit($id)
    {
        $user_id = getUserID();
        try {
            $person = Person::where('user_id', '=', $user_id)
                            ->where('type','=','referee')
                            ->first();
            $address = Address::firstOrCreate([
                'addressable_id' => $person->id,
                'addressable_type' => 'App\Models\Person',
                'user_id' => $user_id
            ], [
                'street' => '',
                'province' => '',
                'city' => ''
            ]);
            $countries = Country::all()->sortBy('country_name')->keyBy('id');
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
                'nationality' => 'required|max:255',
                'country_id' => 'required|max:255',
            ]);
            if (!$v->fails()) {
                $person = Person::where('user_id', '=', $user_id)
                                ->where('type', '=', 'referee')
                                ->first();
                $person->first_name = $request->first_name;
                $person->last_name = $request->last_name;
                $person->birthplace = $request->birthplace;
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
            } else
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
