<?php

namespace App\Notifications;

use App\Models\Role;
use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreatedUserSuccessfully extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user,$password )
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        /** @var User $user */
        $template = Template::all();
        $user = $this->user;
        $role = Role::where('id', '=', $user->requested_role_id)->first();
        $message = "Your password: $this->password";

                  return (new MailMessage)
                ->from(env('MAIL_USERNAME'))
                ->subject($template->new_account)
                ->greeting(sprintf('Hello %s', $role->name))
                ->line($template->registered_by_admin)
                ->line(sprintf($message))
                ->action('Click Here', route('activate.user', $user->confirmation))
                ->line($template->thank);

    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
