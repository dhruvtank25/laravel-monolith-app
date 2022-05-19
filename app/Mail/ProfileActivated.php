<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProfileActivated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user       = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $agb_url        = public_path().'/frontend/pdf/AGB.pdf';
        $withdrawal_url = public_path().'/frontend/pdf/Widerrufsbelehrung_Berater.pdf';
        return $this->subject('Freischaltung Profil')
                    ->markdown('emails.auth.coach_activated')
                    ->attach($agb_url, [
                        'as'   => 'AGB.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->attach($withdrawal_url, [
                        'as'   => 'Widerrufserklärung.pdf',
                        'mime' => 'application/pdf',
                    ])
                    ->with(['user'      => $this->user]);
    }
    
}
