<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Events\AppointmentCancelledEvent;
use App\Events\AppointmentMovedEvent;
use App\Helpers\CommonHelper;
use Auth;
use Mail;

trait AppointmentTrait
{

    public function moveAppointment(Request $request)
    {
        $inputs         = $request->all();
        $appointment_id = $request->appointment_id;
        $user_id        = $this->guard()->id();
        $data           = array(
                            'start'               => $inputs['start'], 
                            'end'                 => $inputs['end'],
                            'mode'                => $inputs['mode'], 
                            'user_updated_on'     => date('Y-m-d H:i:s'),
                            'user_update_comment' => $inputs['comment']
                        );
                
        $result = $this->appointmentRepo->updateUserAppointment($appointment_id, $user_id, $data);
        if($result) {
            // Fire Appointment Cancelled Event
            $appointment = $this->appointmentRepo->get($appointment_id);
            event(new AppointmentMovedEvent($appointment));
            return response()->json(['success'=> 'true', 'message'=> 'Session updated.'], 200);
        }
        else
            return response()->json(['success'=> 'false', 'message'=> 'Unable to update selected session.'], 200);
    }

    public function cancelAppointment(Request $request)
    {
        $appointment_id = $request->appointment_id;
        $user_id = $this->guard()->id();
        $user = $this->userRepo->get($user_id);
        $result = $this->appointmentRepo->cancelAppointment($appointment_id, '', $user_id);
        if($result) {
            // Fire Appointment Cancelled Event
            $appointment = $this->appointmentRepo->get($appointment_id);
            event(new AppointmentCancelledEvent($appointment));
            return response()->json(['success'=> 'true', 'message'=> 'Session cancelled.'], 200);
        }
        else
            return response()->json(['success'=> 'false', 'message'=> 'Unable to cancel selected session.'], 200);
    }

    public function rateCoach($appointment_id, Request $request)
    {
        $appointment = $this->appointmentRepo->get($appointment_id);
        $user_id = $this->guard()->id();
        /*if( $appointment->user_id!=$user_id)
            return response()->json(['success' => 'false', 'message' => 'Unauthorized request'], 200);
        elseif($appointment->review)
            return view('user.call_summary');*/
        $coach           = $appointment->coach;
        $coach_companies = $coach->companies;
        $title = 'Rate Coach';
        $guard_name      = CommonHelper::getGuardName($this->guard());
        $summary_url     = route($guard_name.'.call-summary');
        return view('user.rate_coach', compact('title', 'appointment', 'coach', 'coach_companies', 'summary_url'));
    }

    public function addRating(Request $request)
    {
        $appointment_id = $request->appointment_id;
        $appointment = $this->appointmentRepo->get($appointment_id);
        $user_id = $this->guard()->id();
        if($appointment->review || $appointment->user_id!=$user_id)
            return response()->json(['success' => 'false', 'message' => 'Unauthorized request'], 200);
        $review = array(
                        'user_id'        => $user_id,
                        'coach_id'       => $appointment->coach_id,
                        'comment'        => $request->comment,
                        'rating'         => $request->rating
                    );
        $appointment->review()->create($review);
        return response()->json(['success'=> 'true', 'message'=> 'Session cancelled.'], 200);
    }

    /**
     * Get the guard to be used.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

}