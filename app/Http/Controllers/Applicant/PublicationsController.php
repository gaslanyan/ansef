<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Publications;
use Illuminate\Http\Request;
use Redirect;

class PublicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        $person_id = Person::where('user_id', $user_id)->get()->toArray();
        $publications = [];
        if (!empty($person_id[0]['id'])) {
            $p_id = $person_id[0]['id'];
            $publications = Publications::where('person_id', $p_id)->get()->toArray();
        }
        return view('base.publications.index', compact('publications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $person_id = Person::where('id', $id)->get()->toArray();
        $publications = Publications::where('person_id', '=', $id)->orderBy('year', 'DESC')->get()->toArray();
        return view('base.publications.create', compact('id', 'publications','person_id'));
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
            'title' => 'required|min:3',
            'year' => 'required|date_format:Y',
            /* 'ansef' => 'accepted'*/
            /*'domestic' => 'accepted'*/
        ]);

        try {
            $user_id = \Auth::guard(get_Cookie())->user()->id;  /*Petq e ardyoq avelacnem Cookie-i stugum???*/
            /*$person_id = Person::where('user_id', $user_id )->get()->toArray();
            $p_id  = $person_id[0]['id'];*/

            $p_id = $request->publication_add_hidden_id;
            $publication = new Publications;
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
            return Redirect::back()->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $publication = Publications::find($id);
        return view('base.publications.edit', compact('publication', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user_id = \Auth::guard(get_Cookie())->user()->id;
        /*$person_id = Person::where('user_id', $user_id )->get()->toArray();
        $p_id  = $person_id[0]['id'];*/
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
                $publication = Publications::where("id", $request->publication_hidden_id[$i])->first();

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
            return \Redirect::back()->with('success', getMessage("success"));

        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"))->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        try {
            $publication = Publications::find($id);
            $publication->delete();
            return Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return getMessage("wrong");
        }
    }
}
