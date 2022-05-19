<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use App\Repositories\MangoPayRepository;

class DeclareUbo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'declare:ubo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a UBO declaration validation request';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo, MangoPayRepository $mangoPayRepo)
    {
        parent::__construct();
        $this->userRepo = $userRepo;
        $this->mangoPayRepo    = $mangoPayRepo;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $coaches = $this->userRepo->getPendingUbos(5);
                                
        $success = $failed = 0;
        foreach ($coaches as $coach) {
            $mango_user_id = $coach->mango_user_id;
            if($mango_user_id=='' || $mango_user_id==NULL) {
                $coach = $this->mangoPayRepo->createNaturalUser($coach);
                $mango_user_id = $coach->mango_user_id;
            }

            $ubo_details = $coach->ubo_message;
            //$ubo_details = '';
            if($ubo_details=='') {
                $ubo_obj   = (object) [
                                        'id' => null,
                                        'reason_type' => null, 
                                        'reason_message' => null, 
                                    ];
                $ubo_details = json_encode($ubo_obj);
            }
            $ubo_details = json_decode($ubo_details);

            $ubo_arr = array();
            foreach ($coach->shareholders as $shareholder) {
                $ubo_arr[] = $shareholder;
            }

            // Make UBO request
            $result = $this->mangoPayRepo->applyUBO($mango_user_id, $ubo_arr);

            // Update ubo status
            if($result['status']) {
                $ubo_details->id    = $result['id'];
                $coach->ubo_status  = 'asked';
                $success++;
            }
            else {
                $ubo_details->reason_message = $result['message'];
                $coach->ubo_status           = 'failed';
                $failed++;
            }
            $coach->ubo_message = json_encode($ubo_details);
            $coach->save();

            $this->comment(date('Y-m-d H:i:s').' : '.$success.' ubo declaration requested, '.$failed.' failed');
            $success = $failed = 0;
        }
    }

}
