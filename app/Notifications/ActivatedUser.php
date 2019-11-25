<?php

namespace App\Notifications;

use App\Models\Role;
use App\Models\Template;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ActivatedUser extends Notification
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
        $template = Template::all()->pluck('text','name');
        $user = $this->user;
        $role = Role::where('id', '=', $user->role_id)->first();
        $message = "";

        return (new MailMessage)
            ->from('webmaster@ansef.org')
            ->subject($template['activate_account'])
            ->greeting(sprintf('Hello %s,', $role->name))
            ->line($template['successfully_activated'])
            ->line(sprintf($message))
            ->line($template['thank']);}

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
