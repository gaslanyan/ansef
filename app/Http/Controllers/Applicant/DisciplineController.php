<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Discipline;
use App\Models\DisciplinePerson;
use App\Models\Person;
use Illuminate\Support\Facades\Redirect;
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
        $user_id = chooseUser();
        $person_id = Person::where('user_id', $user_id )->get()->toArray();
        $disciplines = [];
        if(!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $disciplines = Discipline::where('person_id', $p_id)->get()->toArray();
        }

        //$disciplines = Discipline::all();
        return view('applicant.discipline.index', compact('disciplines'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
      $disciplines_person = \DB::table('disciplines_persons')
            ->select('disciplines.text','disciplines_persons.id')
            ->join('disciplines','disciplines.id','=','disciplines_persons.discipline_id')
            ->where('disciplines_persons.person_id','=', $id)->get()->toArray();
        $disciplines = Discipline::all();
        $person = Person::where('id', $id )->get()->toArray();
        return view('applicant.discipline.create', compact('disciplines', 'id','person', 'disciplines_person' ));
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
               // 'title' => 'required|min:3'
            ]);
            $user_id = chooseUser();
            $person_id = Person::where('user_id', $user_id )->get()->toArray();
            $p_id  = $request->discipline_add_hidden_id;
            $discipline = new DisciplinePerson();
            $discipline->discipline_id = $request->discipline;
            $discipline->person_id =  $p_id;
            $discipline->save();

            return \Redirect::back()->with('success', getMessage("success"));

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

        return view('applicant.discipline.edit', compact('discipline', 'id', 'persons'));
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
            $count = count($request->discipline_edit_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $discipline = Discipline::find($request->discipline_edit_hidden_id[$i]);
                $discipline->title = $request->title[$i];
                $discipline->save();
            }
            return Redirect::back()->with('delete', getMessage("deleted"));


        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();

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
            $discipline = DisciplinePerson::find($id);
            $discipline->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
            //return redirect('admin/discipline')->with('success', getMessage('deleted'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
            //return getMessage("wrong");
        }
    }
}
