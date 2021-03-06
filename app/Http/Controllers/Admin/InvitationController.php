<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class InvitationController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {
            $messages = Message::all();
            return view('admin.invitation.create', compact('messages'));
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect('admin/invitation')->with('error', messageFromTemplate("wrong"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $val = Validator::make(
                $request->all(),
                [
                    'template' => 'required|numeric',
                    'email.*' => 'required|email',
                ],
                [
                    'template.required' => ' The template field is required.',
                ]
            );
            if (!$val->fails()) {
                $message = Message::where('id', '=', $request->template)->first();
                foreach ($request->email as $index => $item) {
                    $objSend = new \stdClass();
                    $objSend->message = $message->text;
                    $objSend->subject = $message->subject;
                    $objSend->sender = 'ANSEF Research Board';
                    $objSend->receiver = $item;
                    Mail::to($item)
                        ->send(new \App\Mail\Invitation($objSend));
                }
                return redirect()->back()->with('success', htmlspecialchars_decode(messageFromTemplate("send_email")));
            } else
                return redirect()->back()->withErrors($val->errors())->withInput();
        } catch (\Exception $exception) {
            logger()->error($exception);
            return redirect()->back()->with('error', messageFromTemplate("wrong"));
        }
    }

    public function send()
    {
        $messages = Message::all();

        $users = User::where('users.state', 'active')
            ->join('persons', 'persons.user_id', '=', 'users.id')
            ->whereIn('persons.type', ['applicant', 'referee'])
            ->get();

        return view('admin.invitation.send', compact('messages', 'users'));
    }
}
