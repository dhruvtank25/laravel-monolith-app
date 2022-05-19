<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class postCoachingCoachMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Invoice pdf
     * @var String
     */
    protected $pdf_path;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->subject('Rechnung für deine Beratung bei himmlischberaten.de')
                    ->markdown('emails.appointments.post_coaching_coach')
                    ->attach($this->pdf_path, [
                        'as'   => 'Rechnung Berater.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'appointment'      => $this->appointment,
                    ]);
    }
}
