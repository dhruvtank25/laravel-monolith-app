<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Appointment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class notifyUpcommingSessionUserMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Appointment $appointment, String $day)
    {
        //
        $this->appointment = $appointment;
        $this->day         = $day;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Terminerinnerung')
        ->markdown('emails.appointments.reminder_user')
        ->with([
            'appointment'  => $this->appointment,
            'day'          => $this->day
        ]);
    }
}
