<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use App\Models\User;

class CoachCredsMail extends Mailable
{

    /**
     * User Instance
     */
    protected $user;

    /**
     * Invoice pdf
     * @var String
     */
    protected $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, String $password)
    {
        $this->user              = $user;
        $this->password          = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Ihre Registrierung bei Himmlisch BERATEN')
                    ->markdown('emails.auth.coach_creds')
                    ->with([
                        'coach'      => $this->user,
                        'password'  => $this->password
                    ]);
    }
}
