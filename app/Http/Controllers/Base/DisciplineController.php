<?php

namespace App\Http\Controllers\Base;

use App\Models\Discipline;
use App\Models\Person;
use Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = getUserID();
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $disciplines = [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $disciplines = Discipline::where('person_id', $p_id)->get()->toArray();
        }

        //$disciplines = Discipline::all();
        return view('discipline.index', compact('disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('discipline.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'title' => 'required|min:3'
            ]);
            $user_id = \Auth::guard(get_Cookie())->user()->id;
            $person_id = Person::where('user_id', $user_id )->get()->toArray();
            $p_id  = $person_id[0]['id'];
            $discipline = new Discipline;
            $discipline->title = $request->title;
            $discipline->person_id =  $p_id;
            $discipline->save();

            return redirect('admin/discipline')->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function show(Discipline $discipline)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $discipline = Discipline::find($id);
        $persons = Person::select('first_name', 'last_name')
            ->where('id', '=', $discipline->person_id)->get()->toArray();

        return view('discipline.edit', compact('discipline', 'id', 'persons'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'title' => 'required|min:3'
            ]);
            $discipline = Discipline::find($id);
            $discipline->title = $request->title;
            $discipline->save();
            return Redirect::back()->with('success', getMessage("success"));
            //return redirect('admin/discipline')->with('success', getMessage("update"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
            //return getMessage("wrong");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $discipline = Discipline::find($id);
            $discipline->delete();
            return Redirect::back()->with('success', getMessage("success"));
            //return redirect('admin/discipline')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"));
            //return getMessage("wrong");
        }
    }
}
