<?php

namespace App\Listeners;

use App\Events\AppointmentBookedEvent;
use App\Mail\AppointmentBookedUser;
use App\Mail\AppointmentBookedCoach;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\RevisoRepository;
use App\Repositories\AppointmentRepository;
use Mail;

class SendAppointmentBookedEMail
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
     * @param  AppointmentBooked  $event
     * @return void
     */
    public function handle(AppointmentBookedEvent $event)
    {
        ini_set('max_execution_time', '60');
        $appointment = $event->appointment;
        
        // Create Invoice on Reviso
        $user_pdf_url  = url($this->appointmentRepo->getInvoice($appointment, 'user'));
        //$coach_pdf_url = url($this->appointmentRepo->getInvoice($appointment, 'coach'));
        /*if(!$appointment->invoice_id)
            $invoice = $this->revisoRepo->createInvoice($appointment);*/

        // Send Appointment booking details to User
        Mail::to($appointment->user->email)->send(
            new AppointmentBookedUser($appointment, $event->transaction, $user_pdf_url)
        );

        // Notify Coach about new Appointment
        Mail::to($appointment->coach->email)->send(
            new AppointmentBookedCoach($appointment, $event->transaction, $user_pdf_url)
        );
    }

}
