<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Invitation extends Mailable
{
    use Queueable, SerializesModels;
    public $invent;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($invent)
    {
        $this->invent = $invent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->view('mails.invitation')
            ->text('mails.invitation_plain')
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ]);
//            ->attach(public_path('/images') . '/demo.jpg',
//                [
//                    'as' => 'demo.jpg',
//                    'mime' => 'image/jpeg',
//                ]
//            );
    }
}
