<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Appointment;

class AppointmentCancelledUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment instance.
     *
     * @var Appointment
     */
    protected $appointment;

    /**
     * Invoice pdf
     * @var String
     */
    protected $pdf_path;

    public function __construct(Appointment $appointment, String $pdf_path)
    {
        $this->appointment       = $appointment;
        $this->pdf_path          = $pdf_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $appointment = $this->appointment;
        $user = $appointment->user;
        $subject = $appointment->cancelled_by=='coach'?'Stornierung deiner Buchung:':'Stornobestätigung Buchungsnummer:';
        $subject .= $appointment->id;
        return $this->subject($subject)
                    ->markdown('emails.appointments.cancel_appointment_user')
                    ->attach($this->pdf_path, [
                        'as'   => 'Gutschrift.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'appointment'   => $appointment,
                        'user'          => $user,
                    ]);
    }
}
