<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;
use App\Models\User;

class MailResetPasswordNotification extends ResetPassword
{

    use Queueable;

    /**
     * The user instance.
     *
     * @var User
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $token;

    public function __construct(User $user, $token)
    {
        $this->user  = $user;
        $this->token = $token;
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
        $user = $this->user;
        $link = route('password.reset',['token' => $this->token]);
        return  (new MailMessage)
                        ->subject('Neues Passwort')
                        ->markdown('emails.auth.reset_password', ['user' => $user, 'link' => $link]);
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
