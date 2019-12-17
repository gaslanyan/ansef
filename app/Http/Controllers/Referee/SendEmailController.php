<?php

namespace App\Http\Controllers\Referee;

use App\Http\Controllers\Controller;
use App\Models\Email;
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
        $user_id = getUserID();
        try {
            $pid = RefereeReport::select('proposal_id')->where('id', $id)->first()->proposal_id;
            $tag = getProposalTag($pid);
            $rejected = null;
            return view('referee.report.showEmail', compact('pid', 'tag', 'id', 'rejected'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/edit')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function showRejectedEmail($id)
    {
        $user_id = getUserID();
        try {
            $pid = RefereeReport::select('proposal_id')->where('id', $id)->first()->proposal_id;
            $tag = getProposalTag($pid);
            $rejected = 'rejected';
            return view('referee.report.showEmail', compact('pid', 'tag', 'id', 'rejected'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('referee/edit')->with('error', messageFromTemplate("wrong"));
        }
    }

    public function sendEmail(Request $request, $id)
    {
       try {
        $user_id = getUserID();
        $person_id = getPersonIdByRole('referee');
        $person = Person::where('id', $person_id)->first();
        $email = User::where('id','=', $person->user_id)->first()->email;

        $f_name = 'Referee ' . $email;
        if (!empty($person->first_name) && !empty($person->last_name))
            $f_name = 'Referee ' . $person->first_name . " " . $person->last_name;
        $tag = getProposalTag($id);
        $data = ["content" => $request->comment, "referee" => $f_name, "tag" => $tag];

        Mail::send(['text' => 'referee.report.mail'],
                    $data,
                    function ($message) use ($email, $f_name, $tag) {
                        $message->to('dopplerthepom@gmail.com')
                                ->subject('Referee communication about proposal ' . $tag);
                        $message->from($email, $f_name);
            });
        if($request->rejected == 'rejected')
            return redirect('/referee/report/in-progress')->with('success', "Email sent to ANSEF Program Officer.");
        else
            return redirect()->back()->with('success', "Email sent to ANSEF Program Officer.");
       } catch (\Exception $exception) {
           logger()->error($exception);
           return redirect()->back()->with('error', messageFromTemplate("wrong"));
       }
    }
}

