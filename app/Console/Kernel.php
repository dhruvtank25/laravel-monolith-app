<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('appointment:releaseSlots')
                ->hourly()
                ->appendOutputTo(storage_path('logs/appointment_slot_released.log'));
        $schedule->command('appointment:setCompleted')
                ->everyFifteenMinutes()
                ->appendOutputTo(storage_path('logs/set_completed_appointment.log'));
        $schedule->command('payment:process')
                ->daily()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/payment_processed.log'));
        $schedule->command('refund:process')
                ->hourly()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/refund_processed.log'));
        $schedule->command('kyc:validate')
                ->everyThirtyMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/kyc_processed.log'));
        $schedule->command('declare:ubo')
                ->everyThirtyMinutes()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/kyc_processed.log'));
        $schedule->command('run:notifyupcomingsession')
                 ->cron('*/15 * * * *')
                 ->appendOutputTo(storage_path('logs/notify_upcoming_session.log'));
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
