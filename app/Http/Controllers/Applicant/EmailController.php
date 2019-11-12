<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Email;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;


class EmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = chooseUser();
        $persons = Person::where('user_id', $user_id)
                            ->where('persons.type', '!=', null)
                            ->get()->toArray();
        $persons_email = [];
        foreach ($persons as $item) {
            if (!empty($item['id'])) {
                $p_id = $item['id'];

            }
            //$persons_email = Person::where();
        }
        return view('applicant.email.index', compact('persons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create($id)
    {
        $user_id = chooseUser();
        $persons_name = Person::where('id', $id)->first()->toArray();
        $email_list = Email::where('person_id', '=', $id)->get()->toArray();
        return view('applicant.email.create', compact('persons_name', 'email_list', 'id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'email.*' => 'email|max:255',
            /*'person_name' => 'required|not_in:Choose person'*/

        ]);
        try {
            $user_id = chooseUser();
            $person_id = Person::where('user_id', $user_id)->get()->toArray();
            foreach ($request->email as $item) {
                $email = new Email;
                $email->person_id = $request->email_creare_hidden;
                $email->email = $item;
                $email->save();
            }
            return Redirect::back()->with('success', getMessage("success"));


        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', getMessage("wrong"))->withInput();


        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $email = Email::where('person_id', '=', $id)->get()->toArray();
        return view('applicant.email.edit', compact('email', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'email.*' => 'required|email|max:255',
                //|unique:users
            ]);
            for ($i = 0; $i <= count($request->email_list) - 1; $i++) {
                $email = Email::find($request->email_list_hidden[$i]);
                $email->email = $request->email_list[$i];
                $email->save();
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
     * @param  \App\Email $email
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $email = Email::find($id);
            $email->delete();
            return \Redirect::back()->with('delete', getMessage("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return \Redirect::back()->with('wrong', getMessage("wrong"));
        }
    }
}
