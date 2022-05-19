<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AppointmentRequest;

class AppmntRequestAccepted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The appointment request instance.
     *
     * @var AppointmentRequest
     */
    protected $appRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AppointmentRequest $appRequest)
    {
        $this->appRequest = $appRequest;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user  = $this->appRequest->user;
        $coach = $this->appRequest->coach;
        $slot  = $this->appRequest->getAcceptedSlot()->first();
                
        return $this->subject('Bestätigung deiner Terminanfrage')
                    ->markdown('emails.appmnt_request.accepted')
                    ->with([
                            'appRequest' => $this->appRequest,
                            'slot'       => $slot,
                            'user'       => $user,
                            'coach'      => $coach,
                        ]);
    }
}
