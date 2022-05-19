<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\AppointmentRequest;

class AppmntRequestRejected extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(AppointmentRequest $appRequest)
    {
        //
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
        return $this->subject('Deine Terminanfrage bei himmlischberaten.de')
                    ->markdown('emails.appmnt_request.rejected')
                    ->with([
                            'user'      => $user
                        ]);
    }
}
