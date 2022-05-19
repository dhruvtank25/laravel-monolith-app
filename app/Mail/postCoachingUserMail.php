<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;

class postCoachingUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment)
    {
        $this->appointment       = $appointment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Deine Beratung bei himmlischberaten.de')
                    ->markdown('emails.appointments.post_coaching_user')
                    ->with([
                        'appointment'      => $this->appointment,
                    ]);
    }
}
