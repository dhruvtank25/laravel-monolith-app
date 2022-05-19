<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AppointmentRepository;
use App\Repositories\MangoPayRepository;

class ProcessRefunds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'refund:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process cancelled appointment refund';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(AppointmentRepository $appointmentRepo, MangoPayRepository $mangoPayRepo)
    {
        parent::__construct();
        $this->appointmentRepo = $appointmentRepo;
        $this->mangoPayRepo    = $mangoPayRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $appointments = $this->appointmentRepo->getPendingRefunds();
        $success = $failed = 0;
        foreach ($appointments as $appointment) {
            $result = $this->mangoPayRepo->payInRefund($appointment);
            if($result['status'])
                $success++;
            else
                $failed++;
        }
        $this->comment(date('Y-m-d H:i:s').' : '.$success.' refunded, '.$failed.' failed');
    }
}
