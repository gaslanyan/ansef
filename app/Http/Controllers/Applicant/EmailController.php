<?php

namespace App\Http\Controllers\Applicant;

use App\Models\Email;
use App\Http\Controllers\Controller;
use App\Models\Person;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;


class EmailController extends Controller
{
    public function index()
    {
        // $user_id = getUserID();
        // $persons = Person::where('user_id', $user_id)
        //                     ->where('persons.type', '!=', null)
        //                     ->get()->toArray();
        // $persons_email = [];
        // foreach ($persons as $item) {
        //     if (!empty($item['id'])) {
        //         $p_id = $item['id'];

        //     }
        //     //$persons_email = Person::where();
        // }
        // return view('applicant.email.index', compact('persons'));
    }

    public function create($id)
    {
        $user_id = getUserID();
        $persons_name = Person::where('id', $id)->first()->toArray();
        $email_list = Email::where('person_id', '=', $id)->get()->toArray();
        return view('applicant.email.create', compact('persons_name', 'email_list', 'id'));
    }

    public function store(Request $request)
    {
        $user_id = getUserID();
        $request->validate([
            'email.*' => 'email|max:255'
        ]);
        try {
            foreach ($request->email as $item) {
                $email = new Email;
                $email->person_id = $request->email_creare_hidden;
                $email->email = $item;
                $email->save();
            }
            return Redirect::back()->with('success', messageFromTemplate("success"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"))->withInput();
        }
    }

    public function show($id)
    {
    }

    public function edit($id)
    {
        // $user_id = getUserID();

        // $email = Email::where('person_id', '=', $id)->get()->toArray();
        // return view('applicant.email.edit', compact('email', 'id'));
    }

    public function update(Request $request, $id)
    {
        $user_id = getUserID();
        try {
            $this->validate($request, [
                'email_list.*' => 'required|email|max:255'
            ]);
            for ($i = 0; $i <= count($request->email_list) - 1; $i++) {
                $email = Email::find($request->email_list_hidden[$i]);
                $email->email = $request->email_list[$i];
                $email->save();
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
            $email = Email::find($id);
            $email->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
