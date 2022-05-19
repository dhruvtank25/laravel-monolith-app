<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\AppointmentRepository;
use App\Repositories\MangoPayRepository;

class ProcessPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process completed appointment payment';

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
        $appointments = $this->appointmentRepo->getPendingPayments();
        $success = $failed = 0;
        foreach ($appointments as $appointment) {
            try {
                $result = $this->mangoPayRepo->makeTransfer($appointment);
                if($result)
                    $success++;
                else
                    $failed++;
            } catch (\Exception $e) {
                $failed++;
            }
        }
        $this->comment(date('Y-m-d H:i:s').' : '.$success.' transferred, '.$failed.' failed');
    }
}
