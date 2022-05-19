<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use App\Events\AppointmentBookedEvent;
use App\Listeners\SendAppointmentBookedEMail;
use App\Events\AppointmentCancelledEvent;
use App\Listeners\SendAppointmentCancelledEmail;
use App\Events\AppointmentCompletedEvent;
use App\Listeners\SendAppointmentCompletedEmail;
use App\Events\AppointmentMovedEvent;
use App\Listeners\SendAppointmentMovedEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        AppointmentBookedEvent::class => [
            SendAppointmentBookedEMail::class
        ],
        AppointmentCancelledEvent::class => [
            SendAppointmentCancelledEmail::class
        ],
        AppointmentCompletedEvent::class => [
            SendAppointmentCompletedEmail::class
        ],
        AppointmentMovedEvent::class => [
            SendAppointmentMovedEmail::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
