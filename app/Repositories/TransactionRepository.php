<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends EloquentRepository
{
    protected $model;

    function __construct(Transaction $transaction)
    {
        $this->model = $transaction;
    }

    public function getUserDataTable($user_id)
    {
        return $this->model->where('debited_user_id', $user_id)
                            ->orWhere(function($q) use ($user_id) {
                                $q->where('type', 'TRANSFER')
                                    ->where('credited_user_id', $user_id);
                            });
    }

    public function getAppointmentDataTable($appointment_id)
    {
        return $this->model->where('appointment_id', $appointment_id);
    }

    public function getByTransactionId($transaction_id, $type='')
    {
        return $this->model->where('transaction_id', $transaction_id)
                    ->when($type, function($q) use ($type) {
                        $q->where('type', $type);
                    })
                    ->with('appointment')
                    ->first();
    }

    public function getAppmntTrans($appointment_id, $type='PAYIN')
    {
        return $this->model->where('appointment_id', $appointment_id)
                            ->where('type', $type)
                            ->where('status', 'SUCCEEDED')
                            ->first();
    }

    /**
     * Save a new transaction record of type PAYIN/TRANSFER
     * @param  App\Models\Appointment $appointment     Appointment for which transaction is created
     * @param  Object                 $trans_response  MangoPay\Transfer or MagoPay\PayIn response
     * @return Integer                                 Newly created transaction Id
     */
    public function saveTransaction($appointment, $trans_response)
    {
        $user  = $appointment->user;
        $coach = $appointment->coach;
        $transaction_arr  = array(
                                'transaction_id'    => $trans_response->Id,
                                'type'              => $trans_response->Type,
                                'nature'            => $trans_response->Nature,
                                'appointment_id'    => $appointment->id,
                                //'debited_user_id'   => $appointment->user_id,
                                'debited_mango_id'  => $trans_response->AuthorId,
                                'debited_wallet_id' => $trans_response->DebitedWalletId,
                                'debited_amount'    => $trans_response->DebitedFunds->Amount,
                                //'credited_user_id'  => $appointment->coach_id,
                                'credited_mango_id' => $trans_response->CreditedUserId,
                                'credited_wallet_id'=> $trans_response->CreditedWalletId,
                                'credited_amount'   => $trans_response->CreditedFunds->Amount,
                                'fees'              => $trans_response->Fees->Amount,
                                'status'            => $trans_response->Status,
                                'result_code'       => $trans_response->ResultCode,
                                'result_message'    => $trans_response->ResultMessage,
                                'payment_response'  => json_encode($trans_response)
                            );
        if($trans_response->Type=='PAYIN') {
            $execDetails = $trans_response->ExecutionDetails;
            $transaction_arr['payment_type']        = $trans_response->PaymentType;
            $transaction_arr['execution_type']      = $trans_response->ExecutionType;
            $transaction_arr['payment_card_id']     = $trans_response->PaymentDetails->CardId;
            $transaction_arr['secure_mode_needed']  = $execDetails->SecureModeNeeded;
            $transaction_arr['secured_mode_url']    = $execDetails->SecureModeRedirectURL;
        }
        // Set proper user Ids
        $transaction_arr['debited_user_id'] = $transaction_arr['credited_user_id'] = NULL;
        if($transaction_arr['debited_mango_id']!=NULL)
            $transaction_arr['debited_user_id'] = $transaction_arr['debited_mango_id']==$user->mango_user_id?$user->id:$coach->id;
        if($transaction_arr['credited_mango_id']!=NULL)
            $transaction_arr['credited_user_id'] = $transaction_arr['credited_mango_id']==$user->mango_user_id?$user->id:$coach->id;
        $trans_id = $this->add($transaction_arr, false);
        return $trans_id;
    }
}