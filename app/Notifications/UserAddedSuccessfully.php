<?php

namespace App\Notifications;

use App\Models\Role;
use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class UserAddedSuccessfully extends Notification
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

        // $template = Template::all()->pluck('text','name');
        $user = $this->user;
        $role = Role::where('id', '=', $user->requested_role_id)->first();

        return (new MailMessage)->from(config('emails.webmaster'))
                                ->subject('New ANSEF account created for you')
                                ->greeting(sprintf('Dear ANSEF %s,', $role->name))
                                ->line('The ANSEF administrator registered you to the ANSEF portal as a ' . $role->name . '.')
                                ->line('You will need to confirm the registration and choose a password so that you can log into the ANSEF portal.')
                                ->action('Click here to choose a password', route('activate.addeduser', ['email'=>$user->email, 'code'=>$user->confirmation, 'admin' => 'false']))
                                ->line('Thank you on behald of the ANSEF Research Board.');

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
