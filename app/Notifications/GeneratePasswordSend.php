<?php

namespace App\Notifications;

use App\Models\User;
use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class GeneratePasswordSend extends Notification
{
    use Queueable;

    /**
     * @var User
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param User $user
     */
    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = $this->user;
        $person = Person::where('user_id', '=', $user->id)->first();
        if(!empty($person))
        $name = $person->first_name . " " .$person->last_name;
        else
            $name = getMessage('account');

        $message = '';
        if (!empty(Auth::guard('superadmin')->user()) ||
            !empty(Auth::guard('admin')->user()))
            $message = 'Your password: ' . $this->password;
        return (new MailMessage)
            ->from(env('MAIL_USERNAME'))
            ->subject('Successfully created new password')
            ->greeting(sprintf('Hello %s', $name))
            ->line('You have the new generated password. Please login again.')
            ->line($message)
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
