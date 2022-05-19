<?php

namespace App\Repositories;

use App\Models\CallLog;

class CallLogRepository extends EloquentRepository
{
    
    protected $model;

    function __construct(CallLog $callLog)
    {
        $this->model = $callLog;
    }

    public function addUpdateLog($data_arr)
    {
        $conv_id = $data_arr['conversation_id'];
        $conversation = $this->getByConversationId($conv_id);
        if($conversation) {
            if($conversation->status!='CALL_STATUS_ENDED' && $conversation->duration<=$data_arr['duration']) {
                $conversation->duration = $data_arr['duration'];
                $conversation->status   = $data_arr['status'];
                $conversation->save();
            }
            $log_id = $conversation->id;
        } else {
            $log_id = $this->add($data_arr, false);
        }
        return $log_id;
    }

    public function getByConversationId($conv_id)
    {
        return $this->model->where('conversation_id', $conv_id)->first();
    }

}