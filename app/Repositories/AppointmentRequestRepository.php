<?php

namespace App\Repositories;

use App\Models\AppointmentRequest;
use App\Models\RequestSlot;

class AppointmentRequestRepository extends EloquentRepository
{

    protected $model;
    protected $requestSlot;

    function __construct(AppointmentRequest $appointmentRequest, RequestSlot $requestSlot)
    {
        $this->model       = $appointmentRequest;
        $this->requestSlot = $requestSlot;
    }

    public function getAccepted($request_id, $appmnt_pending=false)
    {
        return $this->model->where('id', $request_id)
                    ->whereIn('status', ['user_accepted', 'coach_accepted'])
                    ->when($appmnt_pending, function($q) {
                        $q->where('appointment_id', 0);
                    })
                    ->with(['slots' => function($q) {
                        $q->where('status', 'accepted');
                    }])
                    ->first();
    }

    /**
     * Bulk insert of slots
     * @param Array $slot_arr Slot Array
     *
     * @return Integer  count of rows inserted
     */
    public function addSlots($slot_arr)
    {
        return  $this->requestSlot->insert($slot_arr);
    }

    public function getRequestForCoach($coach_id)
    {
        return  $this->model->where('status', 'user_requested')
                    ->where('coach_id', $coach_id)
                    ->with(['categories', 'user', 'slots'])
                    ->get();
    }

    public function getRequestForUser($user_id)
    {
        return $this->model->where('status', 'coach_suggested')
                        ->where('user_id', $user_id)
                        ->with([
                            'categories', 
                            'coach', 
                            'slots' => function($q) {
                                $q->whereNull('status');
                            }
                        ])
                        ->get();
    }

    public function updateRequest($id, $update_by, $updater_id, $status, $accepted_id=0)
    {
        $this->updateSlotStatuses($id, $accepted_id);
        return  $this->model->where('id', $id)
                    ->when($update_by=='coach', function($q) use ($updater_id) {
                        $q->where('coach_id', $updater_id);
                    }, function ($q) use ($updater_id) {
                        $q->where('user_id', $updater_id);
                    })
                    ->update(['status' => $status]);
    }

    public function updateSlotStatuses($request_id, $accepted_id=0)
    {
        if($accepted_id!=0)
            $this->requestSlot->where('id', $accepted_id)->update(['status' => 'accepted']);
        return  $this->requestSlot->where('appointment_request_id', $request_id)
                                ->when($accepted_id!=0, function($q) use ($accepted_id) {
                                    $q->where('id', '!=', $accepted_id);
                                })
                                ->update(['status' => 'rejected']);
    }

}