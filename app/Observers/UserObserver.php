<?php

namespace App\Observers;

use App\Models\User;
use Webleit\RevisoApi\Reviso;
use App\Repositories\RevisoRepository;
use App\Repositories\MangoPayRepository;

class UserObserver
{

    public function __construct(MangoPayRepository $mangoPayRepo, RevisoRepository $revisoRepo)
    {
        $this->revisoRepo = $revisoRepo;
        $this->mangoPayRepo = $mangoPayRepo;
    }

    /**
     * Handle the user "saved" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function saved(User $user)
    {
        $user_type = $user->roles->name;
        if($user_type=='admin')
            return true;
        // Update Reviso User
        /*if($user_type=='coach')
            $this->revisoRepo->updateSupplier($user);
        else
            $this->revisoRepo->updateCustomer($user);*/
        if($user_type=='coach')
            $this->revisoRepo->updateCustomer($user);

        // Update MangoPay User
        if($user_type!='coach')
            $user  = $this->mangoPayRepo->updateNaturalUser($user);
        else {
            // If data is available and fields have changed
            if($user->street && $user->place && $user->country_code && $user->coach_company && (!$user->mango_user_id || $user->isDirty('first_name') || $user->isDirty('last_name') || $user->isDirty('email') || $user->isDirty('birth_date') || $user->isDirty('nationality') || $user->isDirty('work_street') || $user->isDirty('work_place') || $user->isDirty('street') || $user->isDirty('place') || $user->isDirty('coach_company') || $user->isDirty('company_number') || $user->isDirty('person_type'))) {
                $user  = $this->mangoPayRepo->updateNaturalUser($user);
            }

            // Save Coach's bank details on MangoPay
            /*if($user->iban && $user->bic && ($user->isDirty('iban') || $user->isDirty('bic'))) {
                $bank_result = $this->mangoPayRepo->createBankAccount($user);
                        
                if(!$bank_result['status']) {
                    $user->status = 'incomplete';
                    $user->save();
                    return response()->json(['success'=> 'false', 'message'=> 'Bank validation failed! '.$bank_result['message']], 200);
                }
                else
                    $user = $bank_result['user'];
            }*/

            // Update kyc status to pending for re-verification
            if($user->isDirty('id_doc') || $user->isDirty('ustid_doc') || $user->isDirty('commercial_doc')) {
                $user->kyc_status = 'pending';
                $user->saveQuietly();
            }
            
        }

    }

    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        //
    }

    /**
     * Handle the user "updated" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        //
    }

    /**
     * Handle the user "deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        //
    }

    /**
     * Handle the user "restored" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function restored(User $user)
    {
        //
    }

    /**
     * Handle the user "force deleted" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        //
    }
}
