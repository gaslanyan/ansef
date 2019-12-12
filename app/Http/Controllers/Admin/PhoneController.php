<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Phone;
use Illuminate\Http\Request;
use Redirect;

class PhoneController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $pp_id = getPersonIdByRole('admin');
            $person_id = Person::where('id', $pp_id)->get()->toArray();
            $phones = [];
            if (!empty($person_id[0]['id'])) {
                $p_id = $person_id[0]['id'];
                $phones = Phone::where('person_id', $p_id)->get()->toArray();
            }
            return view('admin.phone.index', compact('phones'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/phone')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.phone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->isMethod('post'))
            return view('admin.phone.create');
        else {
            $validatedData = $request->validate([
//            'country_code.*' => 'required|max:4', /*Need to check validation not working*/
            'phone.*' => 'required'

        ]);
        try {
            $person_id = getPersonIdByRole('admin');
            foreach ($request->phone as $key => $item) {
                $phone = new Phone();
                $phone->person_id = $person_id;
                $phone->country_code = ($request->country_code)[$key];
                $phone->number = $item;
                $phone->save();
            }
            return redirect('admin/phone')->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/phone')->with('error', getMessage("wrong"));

        }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function show(Phone $phone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $phone = Phone::find($id);
            return view('admin.phone.edit',
                compact('phone', 'id'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/phone')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*Need to check validation not working*/
        if (!$request->isMethod('post'))
            return view('admin.phone.edit');
        else {
        $validatedData = $request->validate([
            /*'country_code' => 'required|numeric|phone_number|max:3',
            //'number' =>'required|size:11'*/

        ]);
        try {
//            $this->validate($request, [
//                'name' => 'required|numeric|phone_number|size:11',
//                'text' => 'required|numeric|phone_number|max:3'
//            ]);


            foreach ($request->phone as $key => $item) {
                $phones = Phone::find($id);
                $phones->country_code = ($request->country_code)[$key];

                $phones->number = $item;
                $phones->save();
            }
            return redirect('admin/phone')->with('success', getMessage("update"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/phone')->with('wrong', getMessage("wrong"));
        }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $phone = Phone::find($id);
            $phone->delete();
            return redirect('admin/phone')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/phone')->with('error', getMessage('wrong'));
        }
    }
}
