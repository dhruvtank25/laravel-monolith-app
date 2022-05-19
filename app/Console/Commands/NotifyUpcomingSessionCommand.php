<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AppointmentRepository;
use App\Repositories\UserRepository;
use App\Mail\notifyUpcommingSessionUserMail;
use App\Mail\notifyUpcommingSessionCoachMail;
use Carbon\Carbon;
use Mail;
use App\Helpers\ExotelHelper;

class NotifyUpcomingSessionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:notifyupcomingsession';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notifies user about session 24 hrs and 1 hrs before session start';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo, AppointmentRepository $AppointmentRepo)
    {
        parent::__construct();
        $this->userRepo        = $userRepo;
        $this->AppointmentRepo = $AppointmentRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment('Command Started.');
        $start = Carbon::now()->format('Y-m-d H:i:s');
        $notify_before_arr = array('24','1');
        $this->comment('Nofifying users at '.date('Y-m-d H:i:s'));
        $total_notified = 0;
        foreach ($notify_before_arr as $before_time) {
            $day      = $before_time=='24'?'tommorrow':'today';
            $appointments = $this->AppointmentRepo->getNotifyBooking($before_time);
            foreach ($appointments as $appointment) {
                Mail::to($appointment->Coach->email)->send(
                    new notifyUpcommingSessionCoachMail($appointment,$day)
                );
                Mail::to($appointment->User->email)->send(
                    new notifyUpcommingSessionUserMail($appointment,$day)
                );
                $total_notified++;
            }
        }
        $this->comment($total_notified.' notifications sent about sessions');
        $this->comment('Command executed successfully.');
    }

}
