<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Person;
use App\Models\Proposal;
use App\Models\RefereeReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class SendEmailController extends Controller
{
    public function showEmail($id)
    {
        try {
            $p_id = RefereeReport::select('proposal_id')->where('id', $id)->first();
            $title = Proposal::select('title')->where('id', $p_id->proposal_id)->first();
            return view('referee.report.showEmail', compact('p_id', 'title'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/edit')->with('error', getMessage("wrong"));
        }
    }

    public function sendEmail(Request $request, $id)
    {
//        try {
        $title = Proposal::select('title')->where('id', $id)->first();
        $person_id = getUserId('referee');
        $full_name = Person::select('first_name', 'last_name')->where('id', $person_id)->first();
        $email = User::select('email')->where('id', Session::get('u_id'))->first();
        if (!empty($full_name->first_name) && !empty($full_name->first_name))
            $f_name = $full_name->first_name . " " . $full_name->last_name;
        else
            $f_name = 'Referee by ' . $email->email;
        $data =  ['name'=>$request->comment];

        Mail::send(['text' => 'referee.report.mail'],
           $data, function ($message) use ($title, $email, $f_name) {
                $message->to(env('MAIL_USERNAME'), 'Ansef')
                    ->subject('About ' . $title->title . ' proposal');
                $message->from($email->email, $f_name);
            });
//            return redirect()->back()->with('success', getMessage("send_email"));
        return redirect()->back()->with('success', "Basic Email Sent. Check your inbox.");
        echo "Basic Email Sent. Check your inbox.";
//        } catch (\Exception $exception) {
//            logger()->error($exception);)
//            return redirect()->back()->with('error', getMessage("wrong"));
//        }
    }
}

