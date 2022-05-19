<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentBookedCoach extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment instance.
     *
     * @var Appointment
     */
    protected $appointment;

    /**
     * The transaction instance.
     *
     * @var Transaction
     */
    protected $transaction;

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
    public function __construct(Appointment $appointment, Transaction $transaction, String $pdf_path)
    {
        $this->appointment = $appointment;
        $this->transaction = $transaction;
        $this->pdf_path    = $pdf_path;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.appointments.coach_booked')
                    ->attach($this->pdf_path, [
                        'as'   => 'Rechnung Kunde.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'appointment' => $this->appointment,
                        'transaction' => $this->transaction
                    ])
                    ->subject('Buchungsbestätigung bei himmlischberaten.de');
    }
}
