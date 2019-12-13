<?php

namespace App\Http\Controllers\Applicant;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class ResearchBoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
         if($type=='board') {
            return view('applicant.email.board');
         }
         else{
            return view('applicant.email.admin');
         }
    }


    public function send(Request $request)
    {
        if (!empty($request->message)) {
            $user_id = getUserIdByRole();
            $user = User::find($user_id);
            $email = $user->email;
            $name = $user->email;
            if(!empty($person->first_name) && !empty($person->last_name)) {
                $name = $person->first_name . " " . $person->last_name;
            }
            $data = ['email' => $email, 'name' => $name, 'content' => $request->message];
            $to = $request->target == "board" ? "dopplerthepom@gmail.com" : "vvsahakian@me.com";

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


    /***
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
