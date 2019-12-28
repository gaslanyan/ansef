<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Meeting;
use App\Models\Person;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Redirect;

class MeetingController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $meetings = Meeting::where('person_id', '=', $id)
            ->where('user_id', '=', $user_id)
            ->orderBy('year', 'DESC')->get()->toArray();
        $person = Person::where('id', $id)->get()->toArray();
        return view('applicant.meeting.create', compact('id', 'meetings', 'person'));
    }

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
            $meeting->user_id = $user_id;
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
                if ($meeting->user_id != $user_id) continue;
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

    public function destroy($id)
    {
        $user_id = getUserID();
        try {
            $meeting = Meeting::where('id', '=', $id)
                ->where('user_id', '=', $user_id)
                ->first();
            $meeting->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
