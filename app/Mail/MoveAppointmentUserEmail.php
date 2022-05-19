<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use Illuminate\Contracts\Queue\ShouldQueue;

class MoveAppointmentUserEmail extends Mailable
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
        $agb_url          = public_path().'/frontend/pdf/AGB.pdf';
        $withdrawal_url   = public_path().'/frontend/pdf/Widerrufsbelehrung.pdf';
        $appointment      = $this->appointment;
        $user             = $appointment->user;
        $coach            = $appointment->coach;
        $cost_calculation = $appointment->formatted_cost_calculation;
        return $this->subject('Terminverschiebung Buchungsnummer: '.$appointment->id)
                    ->markdown('emails.appointments.move_appointment_user')
                    ->attach($agb_url, [
                        'as'   => 'AGB.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach($withdrawal_url, [
                        'as'   => 'Widerrufsformular.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'appointment'      => $appointment,
                        'user'             => $user,
                        'coach'            => $coach,
                        'cost_calculation' => $cost_calculation
                    ]);
    }
}
