<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Phone;
use Illuminate\Http\Request;
use Redirect;

class PhoneController extends Controller
{
    public function index()
    {
        // $user_id = getUserID();
        // $person_id = Person::where('user_id', $user_id)->first()->toArray();
        // $phones = [];
        // if (!empty($person_id[0]['id'])) {
        //     $p_id = $person_id[0]['id'];
        //     $phones = Phone::where('person_id', $p_id)->get()->toArray();
        // }
        // return view('base.phone.index', compact('phones'));
    }

    public function create($id)
    {
        $user_id = getUserID();
        $persons_name = Person::where('id', $id)->whereIn('type', ['participant', 'support'])->first();
        $phone_list = Phone::where('person_id', '=', $id)->get()->toArray();
        return view('applicant.phone.create', compact('persons_name', 'phone_list', 'id'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_code.*' => 'required|max:4',
            'phone.*' => 'required'
        ]);
        try {
            $user_id = getUserID();
            $person = Person::where('user_id', '=', $user_id)->first();
            foreach ($request->phone as $key => $item) {
                $phone = new Phone();
                $phone->person_id = $request->phone_create_hidden;
                $phone->country_code = ($request->country_code)[$key];
                $phone->number = $item;
                $phone->save();
            }
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show(Phone $phone)
    {
        //
    }

    public function edit($id)
    {
        // $user_id = getUserID();
        // $phone = Phone::find($id);
        // return view('base.phone.edit', compact('phone', 'id'));
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'country_code.*' => 'required|max:4',
            'phone.*' => 'required'
        ]);
        try {


            for ($i = 0; $i <= count($request->phone_list) - 1; $i++) {
                $phones = Phone::find($request->phone_list_hidden[$i]);
                $phones->country_code = ($request->country_code)[$i];
                $phones->number = ($request->phone_list)[$i];
                $phones->save();
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
            $phone = Phone::find($id);
            $phone->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
