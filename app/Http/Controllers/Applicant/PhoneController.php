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
    }

    public function create($id)
    {
        $user_id = getUserID();
        $persons_name = Person::where('id', $id)->whereIn('type', ['participant', 'support'])
            ->where('user_id', '=', $user_id)
            ->first();
        $phone_list = Phone::where('person_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->get()->toArray();
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
                $phone->user = $user_id;
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
                if ($phones->user_id != $user_id) continue;
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
            $phone = Phone::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
            $phone->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
