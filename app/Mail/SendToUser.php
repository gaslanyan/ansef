<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendToUser extends Mailable
{
    use Queueable, SerializesModels;
    public $sendtoadmin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($sendtoadmin)
    {
        $this->sendtoadmin = $sendtoadmin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('dopplerthepom@gmail.com')
                    ->view('mails.sendtouser')
                    ->attach(public_path('/images') . '/logos/send_logo.png',
                        [
                            'as' => 'logo.png',
                            'mime' => 'image/jpeg',
                        ]
                    );
    }
}
