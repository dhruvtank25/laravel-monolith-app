<?php

namespace App\Listeners;

use App\Events\AppointmentMovedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\MoveAppointmentUserEmail;
use App\Mail\MoveAppointmentCoachEmail;
use Mail;

class SendAppointmentMovedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  AppointmentMovedEvent  $event
     * @return void
     */
    public function handle(AppointmentMovedEvent $event)
    {
        $appointment = $event->appointment;

        // Send Appointment Moved email to User
        Mail::to($appointment->user->email)->send(
            new MoveAppointmentUserEmail($appointment)
        );

        // Notify Coach about Appointment shift
        Mail::to($appointment->coach->email)->send(
            new MoveAppointmentCoachEmail($appointment)
        );

    }
}
