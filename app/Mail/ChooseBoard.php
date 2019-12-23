<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ChooseBoard extends Mailable
{
    use Queueable, SerializesModels;
    public $member;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($member)
    {
        $this->member = $member;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('emails.webmaster'))
            ->view('mails.member')
            ->text('mails.member_plain')
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ]);
    }
}
