<?php

namespace App\Http\Controllers\Applicant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ResearchBoardController extends Controller
{
    public function index($type)
    {
        $user_id = getUserID();
        if ($type == 'board') {
            return view('applicant.email.board');
        } else {
            return view('applicant.email.admin');
        }
    }


    public function send(Request $request)
    {
        if (!empty($request->message)) {
            $user_id = getUserID();
            $user = User::find($user_id);
            $email = $user->email;
            $name = $user->email;
            if (!empty($person->first_name) && !empty($person->last_name)) {
                $name = $person->first_name . " " . $person->last_name;
            }
            $data = ['email' => $email, 'name' => $name, 'content' => $request->message];
            $to = $request->target == "board" ? config('emails.RB') : config('emails.webmaster');

            Mail::send(
                ['text' => 'applicant.email.emailtemplate'],
                $data,
                function ($message) use ($email, $name, $to) {
                    $message->to($to)
                        ->subject('Applicant communication to ' . $to);
                    $message->from($email, $name);
                }
            );

            return redirect()->back()->with('success', 'Email sent');
        } else
            return back()->withInput()->with('error', 'Could not send email');
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
