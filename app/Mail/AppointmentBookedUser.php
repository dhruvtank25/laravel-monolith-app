<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentBookedUser extends Mailable
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
        $this->appointment       = $appointment;
        $this->transaction       = $transaction;
        $this->pdf_path          = $pdf_path;
        $this->cost_calculation  = $appointment->formatted_cost_calculation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $agb_url        = public_path().'/frontend/pdf/AGB.pdf';
        $withdrawal_url = public_path().'/frontend/pdf/Widerrufsbelehrung.pdf';
        return $this->subject('Buchungsbestätigung bei himmlischberaten.de')
                    ->markdown('emails.appointments.user_booked')
                    ->attach($this->pdf_path, [
                        'as'   => 'Rechnung.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach($agb_url, [
                        'as'   => 'AGB.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach($withdrawal_url, [
                        'as'   => 'Widerrufsformular.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with([
                        'appointment'      => $this->appointment,
                        'transaction'      => $this->transaction,
                        'cost_calculation' => $this->cost_calculation
                    ]);
    }
}
