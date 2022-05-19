<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AppointmentRepository;

class ReleaseSlots extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointment:releaseSlots';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Releases slots that where intiatiated 3 hours before but failed to complete payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo)
    {
        parent::__construct();
        $this->appointmentRepo = $appointmentRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Set total call duration of appointments and update statuses
        $affected_rows = $this->appointmentRepo->releaseSlots();

        $this->comment(date('Y-m-d H:i:s').' : '.$affected_rows.' appointments released');
    }

}
