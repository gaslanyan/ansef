<?php

namespace App\Http\Controllers\Base;

use App\Models\Honors;
use App\Models\Person;
use Illuminate\Http\Request;
use Redirect;
use App\Http\Controllers\Controller;

class HonorsController extends Controller
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
        dd( $person_id);
        $honors= [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $honors = Honors::where('person_id', $p_id)->get()->toArray();
        }
        return view('base.honors.index', compact('honors','person_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $person_id = Person::where('id',$id )->get()->toArray();
        $honors = Honors::where('person_id','=',$id)->get()->toArray();
        return view('base.honors.create', compact('id','honors','person_id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        //dd($request->honor_hidden_id);
        $validatedData = $request->validate([
            'description' => 'required|min:3',
            'year' => 'required|date_format:Y'
             ]);

        try {
            $user_id = \Auth::guard(get_Cookie())->user()->id;  /*Petq e ardyoq avelacnem Cookie-i(ka te chka) stugum???*/
            //$person_id = Person::where('user_id', $user_id )->get()->toArray();
          //  $p_id  = $person_id[0]['id'];
            $honors = new Honors;
            $honors->person_id = $request->honor_hidden_id[0];
            $honors->description = $request->description;
            $honors->year =$request->year;
            $honors->save();
            return Redirect::back()->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $honor = Honors::find($id);
        return view('base.honors.edit', compact('honor', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $p_id  = $person_id[0]['id'];

        $validatedData = $request->validate([
            'description.*' => 'required|min:3',
            'year.*' => 'required',
        ]);
        try {

             for($i=0; $i <= count($request->honor_hidden_id)-1; $i++) {
                 $honor = Honors::find(($request->honor_hidden_id)[$i]);
                 $honor->description = ($request->description)[$i];
                 $honor->year = ($request->year)[$i];
                 $honor->person_id = $id;
                 $honor->save();
             }

            return \Redirect::back()->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $honor = Honors::find($id);
            $honor->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
