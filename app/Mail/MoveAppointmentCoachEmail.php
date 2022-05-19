<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Appointment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoveAppointmentCoachEmail extends Mailable
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
        $appointment = $this->appointment;
        $user        = $appointment->user;
        $coach       = $appointment->coach;
        return $this->subject('Terminverschiebung Buchungsnummer: '.$appointment->id)
                    ->markdown('emails.appointments.move_appointment_coach')
                    ->with([
                        'appointment' => $appointment,
                        'user'        => $user,
                        'coach'       => $coach,
                    ]);
    }
}
