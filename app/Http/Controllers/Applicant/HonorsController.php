<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Honor;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class HonorsController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $person_id = Person::where('id',$id )
                            ->where('user_id','=',$user_id)
                            ->get()->toArray();
        $honors = Honor::where('person_id','=',$id)->orderBy('year', 'DESC')->get()->toArray();
        return view('applicant.honors.create', compact('id','honors','person_id'));
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
            $honors = new Honor;
            $honors->person_id = $request->honor_hidden_id[0];
            $honors->description = $request->description;
            $honors->year =$request->year;
            $honor->user_id = $user_id;
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
                 $honor = Honor::find(($request->honor_hidden_id)[$i]);
                 if ($honor->user_id != $user_id) continue;
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
            $honor = Honor::where('id','=',$id)
                            ->where('user_id','=',$user_id)
                            ->first();
            $honor->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
