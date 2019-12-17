<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Meeting;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class MeetingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = getUserID();
        $person_id = Person::where('user_id', $user_id)->get()->toArray();
        $meetings = [];
        if (!empty($person_id[0]['id'])) {
            $p_id  = $person_id[0]['id'];
            $meetings = Meeting::where('person_id', $p_id)->get()->toArray();
        }
        return view('base.meeting.index', compact('meetings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $user_id = getUserID();
        $meetings = Meeting::where('person_id', '=', $id)->orderBy('year', 'DESC')->get()->toArray();
        $person = Person::where('id', $id)->get()->toArray();
        return view('base.meeting.create', compact('id', 'meetings', 'person'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'description' => 'required|min:3',
            'year' => 'required|numeric|min:1900|max:2030',
        ]);
        try {
            $user_id = getUserID();

            $p_id = $request->meeting_add_hidden_id;
            $meeting = new Meeting;
            $meeting->person_id = $p_id;
            $meeting->description = $request->description;
            $meeting->year = $request->year;
            if ($request->has('ansef_add') == true) {
                $meeting->ansef_supported = '1';
            } else {
                $meeting->ansef_supported = '0';
            }
            if ($request->has('domestic_add') == true) {
                $meeting->domestic = '1';
            } else {
                $meeting->domestic = '0';
            }
            $meeting->save();
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
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
        $user_id = getUserID();
        $meeting = Meeting::find($id);
        return view('base.meeting.edit', compact('meeting', 'id'));
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
        $user_id = getUserID();
        $validatedData = $request->validate([
            'description.*' => 'required|min:3',
            'year.*' => 'required|numeric|min:1900|max:2030',
        ]);
        try {
            $count = count($request->meeting_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $ansef_supported = '0';
                $domestic = '0';
                $meeting = Meeting::where("id", $request->meeting_hidden_id[$i])->first();
                $meeting->description = $request->meeting_description[$i];
                $meeting->year = $request->meeting_year[$i];

                if (isset($request->ansef_edit[$i])) $ansef_supported = '1';

                if (isset($request->domestic[$i])) $domestic = '1';

                $meeting->ansef_supported = $ansef_supported;
                $meeting->domestic = $domestic;
                $meeting->save();
            }
            return \Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
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
        $user_id = getUserID();
        try {
            $meeting = Meeting::find($id);
            $meeting->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
