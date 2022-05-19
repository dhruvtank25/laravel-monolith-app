<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Appointment;

class AppointmentCancelledCoach extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment instance.
     *
     * @var Appointment
     */
    public $appointment;

    /**
     * User Invoice pdf
     * @var String
     */
    protected $user_pdf_path;
    
    /**
     * Coach Invoice pdf
     * @var String
     */
    protected $coach_pdf_path;

    public function __construct(Appointment $appointment, String $user_pdf_path, String $coach_pdf_path)
    {
        $this->appointment       = $appointment;
        $this->user_pdf_path     = $user_pdf_path;
        $this->coach_pdf_path    = $coach_pdf_path;
    }
  
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user          = $this->appointment->user;
        $coach         = $this->appointment->coach;
        $this_mailable = $this
                            ->subject('Stornobestätigung Buchungsnummer:'.$this->appointment->id)
                            ->markdown('emails.appointments.cancel_appointment_coach');
        if($this->appointment->cancel_fee_percent>0) {
            $this_mailable = $this_mailable->attach($this->coach_pdf_path, [
                                'as'   => 'Rechnung Berater.pdf',
                                'mime' => 'application/pdf',
                            ]);
            $this_mailable = $this_mailable->attach($this->user_pdf_path, [
                                'as'   => 'Gutschrift Kunde.pdf',
                                'mime' => 'application/pdf',
                            ]);
        } else {
            $this_mailable = $this_mailable->attach($this->user_pdf_path, [
                                'as'   => 'Gutschrift Kunde.pdf',
                                'mime' => 'application/pdf',
                            ]);
        }
        $this_mailable = $this_mailable->with([
                                            'appointment' => $this->appointment,
                                            'user'        => $user,
                                            'coach'       => $coach,
                                        ]);
        return  $this_mailable;
    }

}
