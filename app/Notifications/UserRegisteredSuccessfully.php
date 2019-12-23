<?php

namespace App\Notifications;

use App\Models\Role;
use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserRegisteredSuccessfully extends Notification
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
    public function __construct(User $user)
    {
        $this->user = $user;
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
        /** @var User $user */

        $template = Template::all()->pluck('text','name');
        $user = $this->user;
        $role = Role::where('id', '=', $user->requested_role_id)->first();
        $message = "";

        return (new MailMessage)->from(config('emails.webmaster'))
                                ->subject($template['new_account'])
                                ->greeting(sprintf('Dear ANSEF %s,', $role->name))
                                ->line($template['successfully_registered'])
                                ->line(sprintf($message))
                                ->action('Click here to activate your account', route('activate.user', ['email'=>$user->email, 'code'=>$user->confirmation]))
                                ->line($template['thank']);

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
