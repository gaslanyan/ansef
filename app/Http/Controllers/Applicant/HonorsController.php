<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Honors;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class HonorsController extends Controller
{
    public function index()
    {
        // $user_id = getUserID();
        // $person_id = Person::where('user_id', $user_id )->get()->toArray();
        // dd( $person_id);
        // $honors= [];
        // if(!empty($person_id[0]['id'])) {
        //     $p_id  = $person_id[0]['id'];
        //     $honors = Honors::where('person_id', $p_id)->get()->toArray();
        // }
        // return view('base.honors.index', compact('honors','person_id'));
    }

    public function create($id)
    {
        $user_id = getUserID();
        $person_id = Person::where('id',$id )->get()->toArray();
        $honors = Honors::where('person_id','=',$id)->orderBy('year', 'DESC')->get()->toArray();
        return view('base.honors.create', compact('id','honors','person_id'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'description' => 'required|min:3',
            'year' => 'required|date_format:Y'
             ]);

        try {
            $user_id = \Auth::guard(get_role_cookie())->user()->id;  /*Petq e ardyoq avelacnem Cookie-i(ka te chka) stugum???*/
            //$person_id = Person::where('user_id', $user_id )->get()->toArray();
          //  $p_id  = $person_id[0]['id'];
            $honors = new Honors;
            $honors->person_id = $request->honor_hidden_id[0];
            $honors->description = $request->description;
            $honors->year =$request->year;
            $honors->save();
            return Redirect::back()->with('success', messageFromTemplate("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        // $user_id = getUserID();
        // $honor = Honors::find($id);
        // return view('base.honors.edit', compact('honor', 'id'));
    }


    public function update(Request $request, $id)
    {
        $user_id = getUserID();
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

            return \Redirect::back()->with('success', messageFromTemplate("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $honor = Honors::find($id);
            $honor->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
