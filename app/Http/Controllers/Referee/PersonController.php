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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $user_id = chooseUser();
            $persons = Person::where('user_id', $user_id)->get()->toArray();
            if (empty($persons)) {
                return view('referee.dashboard');
            } else {
                return view('referee.person.index', compact('persons'));
            }

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $countries = Country::all()->pluck('country_name', 'cc_fips')->sort()->toArray();

            $institutions = Institution::all()->pluck('content', 'id')->toArray();
            return view('referee.person.create', compact('countries', 'institutions'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_type = get_Cookie();
        $user = Auth::guard($user_type)->user();

        $v = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'nationality' => 'required|max:255',
            'sex' => 'required|max:15',
            'countries.*' => 'required|max:255',
            'provence.*' => 'required|max:255',
            'street.*' => 'required|max:255',
        ]);
        if (!$v->fails()) {

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
//            $person->state = $request->state;
//            $person->type = $request->type;
                $person->user_id = $user->id;
                $person->save();
                $person_id = $person->id;
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $exception) {
                DB::rollBack();
                logger()->error($exception);
                return redirect()->back()->with('error', getMessage("wrong"));
            }

        } else
            return redirect()->back()->withErrors($v->errors());

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function show(Person $person)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
//        try {
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

        return view('referee.person.edit', compact('fulladddress', 'person', 'id', 'countries'));
//        } catch (\Exception $exception) {
//            logger()->error($exception);
//            return redirect('referee/person')->with('error', getMessage("wrong"));
//        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
        $v = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'nationality' => 'required|max:255',
            'sex' => 'required|max:15',
            'countries.*' => 'required|max:255',
            'provence.*' => 'required|max:255',
            'street.*' => 'required|max:255',
        ]);
        if (!$v->fails()) {
            $user = Person::where('id', '=', $id)->first();
            $person = Person::find($user->id);
            $person->first_name = $request->first_name;
            $person->last_name = $request->last_name;
            $person->birthdate = $request->birthdate;
            $person->nationality = $request->nationality;
            $person->sex = $request->sex;
            $person->save();


            // return redirect('referee/person')->with('success', getMessage("success"));
            return redirect('referee/person/' . $id . '/edit')->with('success', getMessage("success"));
        } else
            return redirect()->back()->withErrors($v->errors());
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person/' . $id . '/edit')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Person $person
     * @return \Illuminate\Http\Response
     */
    public function destroy(Person $person)
    {
        //
    }

    public function changePassword()
    {

        return view('referee.person.changepassword');
    }

    public function updatePassword(Request $request)
    {
        try {
            $v = Validator::make($request->all(), [
                'oldpassword' => 'required',
                'newpassword' => 'required|8',
                'confirmpassword' => 'required|same:newpassword',
            ]);
            if (!$v->fails()) {
                $user_id = \Auth::guard('referee')->user()->id;
                $this->validate($request, [
                    'oldpassword' => 'required',
                    'newpassword' => 'required|min:8',
                    'confirmpassword' => 'required|same:newpassword',
                ]);
                $data = $request->all();
                $user = User::find($user_id);
                //var_dump(Hash::check('222222', $user->password));die;
                if (!Hash::check($request->oldpassword, $user->password)) {

                    return back()
                        ->with('error', 'The specified password does not match the database password');
                } else {

                    $user->password = bcrypt($request->newpassword);
                    $user->save();
                    return \Redirect::to('logout')->with('success', getMessage("success"));
                }
            } else
                return redirect()->back()->withErrors($v->errors())->withInput();

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/person')->with('error', getMessage("wrong"));
        }
    }
}
