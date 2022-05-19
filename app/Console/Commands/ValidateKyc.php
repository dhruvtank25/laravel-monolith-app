<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\UserRepository;
use App\Repositories\MangoPayRepository;

class ValidateKyc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'kyc:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Makes a kyc validation request for user on Mangopay';

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
        $coaches = $this->userRepo->getPendingKYCs(5);
                
        $success = $failed = 0;
        foreach ($coaches as $coach) {
            $mango_user_id = $coach->mango_user_id;
            if($mango_user_id=='' || $mango_user_id==NULL) {
                $coach = $this->mangoPayRepo->createNaturalUser($coach);
                $mango_user_id = $coach->mango_user_id;
            }

            // Validations
            if($coach->person_type=='business' && (!$coach->commercial_doc || !$coach->ustid_doc)) {
                $coach->status = 'incomplete';
                $coach->save();
                continue;
            } else if($coach->person_type=='soletrader' && $coach->is_commercial && !$coach->ustid_doc) {
                $coach->status = 'incomplete';
                $coach->save();
                continue;
            }

            $kyc_details = $coach->kyc_message;
            //$kyc_details = '';
            if($kyc_details=='') {
                $kyc_arr   = array();

                // Create empty objects for each type
                $kyc_obj = array();
                $kyc_obj['status']         = 'REFUSED';
                $kyc_obj['reason_type']    = null;
                $kyc_obj['reason_message'] = null;
                $kyc_obj['id']             = '';
                $kyc_obj['file_name']      = '';

                // ID PROOF
                $kyc_obj['type']           = 'IDENTITY_PROOF';
                $kyc_arr[] = (object) $kyc_obj;


                // ARTICLE OF ASSOCIATION PROOF
                if($coach->person_type=='business') {
                    $kyc_obj['type']           = 'ARTICLES_OF_ASSOCIATION';
                    $kyc_arr[] = (object) $kyc_obj;
                }

                // REGISTRATION PROOF
                if($coach->person_type=='business' || $coach->is_commercial) {
                    $kyc_obj['type']           = 'REGISTRATION_PROOF';
                    $kyc_arr[] = (object) $kyc_obj;
                }

                $kyc_details = json_encode($kyc_arr);
            }
            $kyc_details = json_decode($kyc_details);
            
            $changed = false;
            $kyc_result = true;                    
            foreach ($kyc_details as $kyc_detail) {
                $file_names = $file_type = '';
                switch ($kyc_detail->type) {
                    case 'IDENTITY_PROOF':
                        if($kyc_detail->file_name==$coach->id_doc && $kyc_detail->status!='REFUSED')
                            continue 2;
                        $file_names = $coach->id_doc;
                        $file_type  = 'id_doc';
                        break;

                    case 'REGISTRATION_PROOF':
                        if($kyc_detail->file_name==$coach->ustid_doc && $kyc_detail->status!='REFUSED')
                            continue 2;
                        $file_names = $coach->ustid_doc;
                        $file_type  = 'ustid_doc';
                        break;

                    case 'ARTICLES_OF_ASSOCIATION':
                        if($kyc_detail->file_name==$coach->commercial_doc && $kyc_detail->status!='REFUSED')
                            continue 2;
                        $file_names = $coach->commercial_doc;
                        $file_type  = 'commercial_doc';
                        break;
                    
                    default:
                        continue 2;
                        break;
                }
                if($file_names!='' && $file_type!='') {
                    $changed = true;
                    // Send new kyc validation request
                    $result = $this->mangoPayRepo->applyKyc($mango_user_id, $file_names, $file_type, $kyc_detail->type);
                    if($result['status']) {
                        $success++;
                        $kyc_detail->id        = $result['id'];
                        $kyc_detail->file_name = $file_names;
                        $kyc_detail->status    = 'VALIDATION_ASKED';
                    } else {
                        $failed++;
                        $kyc_result = false;
                        $kyc_detail->file_name      = $file_names;
                        $kyc_detail->status         = 'REFUSED';
                        $kyc_detail->reason_message = $result['message'];
                    }
                    $coach->kyc_message = json_encode($kyc_details);
                    $coach->save();
                }
            }

            if($changed) {
                if($kyc_result)
                    $coach->kyc_status  = 'asked';
                else
                    $coach->kyc_status  = 'failed';
                $coach->save();
            }
            $this->comment(date('Y-m-d H:i:s').' : '.$success.' kyc validation requested, '.$failed.' failed');
            $success = $failed = 0;
        }
    }

}
