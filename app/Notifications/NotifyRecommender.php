<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyRecommender extends Notification implements ShouldQueue
{
    use Queueable;
    public $email, $name, $pi, $rid, $confirmation;

    public function __construct($email, $name, $pi, $rid, $confirmation)
    {
        $this->email = $email;
        $this->name = $name;
        $this->pi = $pi;
        $this->rid = $rid;
        $this->confirmation = $confirmation;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                ->from(config('emails.webmaster'))
                ->subject('Recommendation letter for ' . $this->pi)
                ->greeting(sprintf('Dear %s,', $this->name))
                ->line($this->pi . ' is applying for a grant from the Armenian National Science and Education Fund (ANSEF) and has listed you as a person who can provide a letter of support for his application.')
                ->line('Please prepare your signed letter in PDF format -- preferably on an official letterhead of your institution or organization.')
                ->line('When ready, please click on the button below to upload your letter to the ANSEF web portal.')
                ->action('Click here to upload your letter in PDF format', route('submit-letter', 'email=' . $this->email . '&confirmation=' . $this->confirmation . '&rid=' . $this->rid))
                ->line('Thank you, and best regards,')
                ->line('ANSEF Research Board');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
