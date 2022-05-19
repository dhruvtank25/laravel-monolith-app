<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\CallLogRepository;
use App\Helpers\CommonHelper;
use Carbon\Carbon;

class CallController extends Controller
{

    function __construct(AppointmentRepository $appointmentRepo, CallLogRepository $callLogRepo, UserRepository $userRepo)
    {
    	$this->appointmentRepo  = $appointmentRepo;
        $this->callLogRepo      = $callLogRepo;
        $this->userRepo         = $userRepo;
    }

    public function show($appointment_id, Request $request)
    {
        if($appointment_id==0) {
            $user_id   = $request->user_id;
            $client_id = 0;
        } else {
            // Get logged in user's data
            $user_data = CommonHelper::checkUserType();
            $user_type = $user_data['type'];
            $user_id   = $user_data['data']['id'];

            if($user_id==0)
                abort(401);

            $appointment = $this->appointmentRepo->get($appointment_id);
            if(!$appointment)
                abort(404);

            // Make sure logged in user is authorize for this call
            if($user_id!=$appointment->user_id && $user_id!=$appointment->coach_id)
                abort(401);

            if(!Carbon::now('Europe/Berlin')->isBetween($appointment->start->format('Y-m-d H:i:s'), $appointment->end->format('Y-m-d H:i:s')) || $appointment->status!='scheduled' || $appointment->mode!='online')
                abort(404);

            $user_id   = $user_data['data']['id'];
            $client_id = $user_type == 'user' || $user_type=='guest_user'?$appointment->coach_id:$appointment->user_id;
        }
        return view('videocall', compact('user_id', 'client_id', 'appointment_id'));
    }

    public function storeLog(Request $request)
    {
        $inputs          = $request->all();
        if($inputs['appointment_id']=='0')
            return response()->json(['success' => true, 'message' => 'Test calls'], 200);
        $log_id          = $this->callLogRepo->addUpdateLog($inputs);
        return response()->json(['success' => true], 200);
    }

    public function summary($appointment_id)
    {
        // Get logged in user's data
        $user_data = CommonHelper::checkUserType();
        $user_type = $user_data['type'];
        $user_id   = $user_data['data']['id'];
        if($user_id==0 || $appointment_id==0)
            abort(401);
        else if($user_type=='user')
            return redirect()->route('user.rate-coach', ['appointment_id'=>$appointment_id]);
        else if($user_type=='guest_user')
            return redirect()->route('guest_user.rate-coach', ['appointment_id'=>$appointment_id]);

        $appointment       = $this->appointmentRepo->get($appointment_id);
        $appointment_user  = $appointment->user;
        $appointment_coach = $appointment->coach;
        return view('coach/call_summary', compact('appointment', 'appointment_user', 'appointment_coach'));
    }

}
