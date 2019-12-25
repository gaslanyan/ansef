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
    }

    public function create($id)
    {
        $user_id = getUserID();
        $persons_name = Person::where('id', $id)->where('user_id','=',$user_id)->first()->toArray();
        $email_list = Email::where('person_id', '=', $id)->where('user_id', '=', $user_id)->get()->toArray();
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
                $email->user_id = $user_id;
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
                if ($email->user_id != $user_id) continue;
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
            $email = Email::where('id','=',$id)
                            ->where('user_id','=',$user_id)
                            ->first();
            $email->delete();
            return Redirect::back()->with('delete', messageFromTemplate("deleted"));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return Redirect::back()->with('wrong', messageFromTemplate("wrong"));
        }
    }
}
