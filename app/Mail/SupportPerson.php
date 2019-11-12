<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SupportPerson extends Mailable
{
    use Queueable, SerializesModels;
    public $support;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($support)
    {
        $this->support = $support;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->view('mails.support')
            ->text('mails.support_plain')
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ])->to('krist68@mail.ru');
    }
}
