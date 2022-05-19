<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AppointmentRequest;

class appmntAlternativeRequest extends Mailable
{
    use Queueable, SerializesModels;

    protected $slots;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($slots,AppointmentRequest $appRequest)
    {
        $this->appRequest = $appRequest;
        $this->slots = $slots;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user  = $this->appRequest->user;                
        return $this->subject('Bestätigung deiner Terminanfrage')
                    ->markdown('emails.appmnt_request.alternative')
                    ->with([
                            'appRequest'  => $this->appRequest,
                            'slots'       => $this->slots,
                            'user'        => $user,
                        ]);
    }
}
