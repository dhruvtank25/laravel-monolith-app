<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class UserRegisteredNotification extends VerifyEmail
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
        $user            = $this->user;
        $verificationUrl = $this->verificationUrl($notifiable);
        if($user->roles->name=='coach') {
            return  (new MailMessage)
                        ->subject('Bestätige deine e-mail-Adresse')
                        ->markdown('emails.auth.coach_register', ['coach' => $user, 'verificationUrl' => $verificationUrl]);
        } else {
            return  (new MailMessage)
                        ->subject('Bestätige deine e-mail-Adresse')
                        ->markdown('emails.auth.user_register', ['user' => $user, 'verificationUrl' => $verificationUrl]);
        }
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
