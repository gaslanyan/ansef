<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResearchBoard extends Mailable
{
    use Queueable, SerializesModels;
    public $board;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($board)
    {
        $this->board = $board;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_USERNAME'))
            ->view('mails.board')
            ->text('mails.board_plain')
            ->with(
                [
                    'testVarOne' => '1',
                    'testVarTwo' => '2',
                ])->to('krist68@mail.ru');
    }
}
