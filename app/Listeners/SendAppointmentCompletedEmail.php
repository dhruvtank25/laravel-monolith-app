<?php

namespace App\Listeners;

use App\Events\AppointmentCompletedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\RevisoRepository;
use App\Repositories\AppointmentRepository;
use App\Mail\postCoachingCoachMail;
use App\Mail\postCoachingUserMail;
use Mail;

class SendAppointmentCompletedEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo, RevisoRepository $revisoRepo)
    {
        $this->appointmentRepo  = $appointmentRepo;
        $this->revisoRepo       = $revisoRepo;
    }

    /**
     * Handle the event.
     *
     * @param  AppointmentCompletedEvent  $event
     * @return void
     */
    public function handle(AppointmentCompletedEvent $event)
    {
        ini_set('max_execution_time', '60');
        $appointment = $event->appointment;

        // Create Invoice on Reviso
        if(!$appointment->invoice_id)
            $invoice = $this->revisoRepo->createInvoice($appointment);
        $coach_pdf_url = url($this->appointmentRepo->getInvoice($appointment, 'coach'));

        // Send Appointment cancelled email to User
        Mail::to($appointment->user->email)->send(
            new postCoachingUserMail($appointment)
        );

        // Notify Coach about Appointment cancellation
        Mail::to($appointment->coach->email)->send(
            new postCoachingCoachMail($appointment, $coach_pdf_url)
        );
    }
}
