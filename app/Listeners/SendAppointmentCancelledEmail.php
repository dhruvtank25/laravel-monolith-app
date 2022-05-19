<?php

namespace App\Listeners;

use App\Events\AppointmentCancelledEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\RevisoRepository;
use App\Repositories\AppointmentRepository;
use App\Mail\AppointmentCancelledUser;
use App\Mail\AppointmentCancelledCoach;
use Mail;

class SendAppointmentCancelledEmail
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
     * @param  AppointmentCancelledEvent  $event
     * @return void
     */
    public function handle(AppointmentCancelledEvent $event)
    {
        ini_set('max_execution_time', '60');
        $appointment = $event->appointment;

        // Generate Invoices
        $user_pdf_url  = url($this->appointmentRepo->getInvoice($appointment, 'user'));
        $coach_pdf_url = url($this->appointmentRepo->getInvoice($appointment, 'coach'));
        
        // Create Invoice on Reviso
        if(!$appointment->invoice_id)
            $invoice = $this->revisoRepo->createInvoice($appointment);

        // Send Appointment cancelled email to User
        Mail::to($appointment->user->email)->send(
            new AppointmentCancelledUser($appointment, $user_pdf_url)
        );

        // Notify Coach about Appointment cancellation
        Mail::to($appointment->coach->email)->send(
            new AppointmentCancelledCoach($appointment, $user_pdf_url, $coach_pdf_url)
        );
    }

}
