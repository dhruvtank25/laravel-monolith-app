<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use App\Models\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AppointmentRequest;


class appointmentInquiryForCoach extends Mailable
{
    use Queueable, SerializesModels;

    protected $slots;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($slots,User $coach)
    {
        //assigning the 
        $this->slots = $slots;
        $this->user  = $coach;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Terminanfrage bei himmlischberaten.de')
                    ->markdown('emails.appmnt_request.inquiry')
                    ->with([
                        'user'  => $this->user,
                        'slots' => $this->slots
                    ]);
    }
}
