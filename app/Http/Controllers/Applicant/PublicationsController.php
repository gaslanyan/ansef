<?php

namespace App\Http\Controllers\Applicant;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Publication;
use Illuminate\Http\Request;
use Redirect;

class PublicationsController extends Controller
{
    public function index()
    {
    }

    public function create($id)
    {
        $user_id = getUserID();
        $person_id = Person::where('id', $id)->get()->toArray();
        $publications = Publication::where('person_id', '=', $id)->orderBy('year', 'DESC')->get()->toArray();
        return view('applicant.publications.create', compact('id', 'publications','person_id'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $validatedData = $request->validate([
            'title' => 'required|min:3',
            'year' => 'required|date_format:Y',
            /* 'ansef' => 'accepted'*/
            /*'domestic' => 'accepted'*/
        ]);

        try {
            $p_id = $request->publication_add_hidden_id;
            $publication = new Publication;
            $publication->person_id = $p_id;
            $publication->title = $request->title;
            $publication->journal = $request->journal;
            $publication->year = $request->year;
            if ($request->has('ansef_add') == true) {
                $publication->ansef_supported = '1';
            } else {
                $publication->ansef_supported = '0';
            }
            if ($request->has('domestic_add') == true) {
                $publication->domestic = '1';
            } else {
                $publication->domestic = '0';
            }
            $publication->save();
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
            'title.*' => 'required|min:3',
            'year.*' => 'required|date_format:Y',
            /* 'ansef' => 'accepted'*/
            /*'domestic' => 'accepted'*/
        ]);
        try {

            $count = count($request->publication_hidden_id);
            for ($i = 0; $i < $count; $i++) {
                $ansef_supported = '0';
                $domestic = '0';
                $publication = Publication::where("id", $request->publication_hidden_id[$i])->first();

                $publication->title = $request->title[$i];
                $publication->journal = $request->journal[$i];
                $publication->year = $request->year[$i];

                if (isset($request->ansef_edit[$i]))
                    $ansef_supported = '1';

                if (isset($request->domestic[$i]))
                    $domestic = '1';

                $publication->ansef_supported = $ansef_supported;
                $publication->domestic = $domestic;

                $publication->save();
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
            $publication = Publication::find($id);
            $publication->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return messageFromTemplate("wrong");
        }
    }
}
