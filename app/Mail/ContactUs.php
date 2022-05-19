<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var String
     */
    protected $name;

    /**
     * @var String
     */
    protected $email;

    /**
     * @var String
     */
    protected $description;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $email, $description)
    {
        $this->name         = $name;
        $this->email        = $email;
        $this->description  = $description;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New contact request from '.$this->name)
                    ->markdown('emails.contactus')
                    ->with([
                        'name'        => $this->name,
                        'email'       => $this->email,
                        'description' => $this->description,
                    ]);;
    }
}
