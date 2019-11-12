<?php

namespace App\Http\Controllers\Base;

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
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $phones= [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $phones = Phone::where('person_id', $p_id)->get()->toArray();
        }
        return view('base.phone.index', compact('phones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('base.phone.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'country_code.*' => 'required|max:4', /*Need to check validation not working*/
            'phone.*' =>'required'

        ]);
        try {
           /* $user = Auth::guard('admin')->user();
            $person = Person::where('user_id', '=', $user->id)->first();*/
            $user_id = \Auth::guard(get_Cookie())->user()->id;  /*Petq e ardyoq avelacnem Cookie-i stugum???*/
            $person = Person::where('user_id', '=', $user_id)->first();
            foreach ($request->phone as $key => $item) {
                $phone = new Phone();
                $phone->person_id = $person->id;
                $phone->country_code = ($request->country_code)[$key];
                $phone->number = $item;
                $phone->save();
            }
            return Redirect::back()->with('success', getMessage("success"));
            //return redirect('admin/phone')->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
            //return redirect('admin/phone')->with('error', getMessage("wrong"));

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function show(Phone $phone)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $phone = Phone::find($id);
        return view('base.phone.edit', compact('phone', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /*Need to check validation not working*/
        
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
            return Redirect::back()->with('success', getMessage("success"));
            //return redirect('admin/phone')->with('success', getMessage("update"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
            //return redirect('admin/phone')->with('error', getMessage("wrong"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Phone $phone
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $phone = Phone::find($id);
            $phone->delete();
            return Redirect::back()->with('deleted', getMessage("deleted"));
            //return redirect('admin/phone')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
            //return redirect('admin/phone')->with('error', getMessage('wrong'));
        }
    }
}
