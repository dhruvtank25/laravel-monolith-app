<?php

namespace App\Http\Controllers\Coach;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRequestRepository;
use App\Mail\AppmntRequestAccepted;
use App\Mail\AppmntRequestRejected;
use App\Events\AppointmentCancelledEvent;
use Mail;
use DataTables;
use Auth;

class AppointmentController extends Controller
{

    function __construct(AppointmentRepository $appointmentRepo, AppointmentRequestRepository $appRequestRepo,UserRepository $userRepo)
    {
        $this->userRepo         = $userRepo;
        $this->appointmentRepo = $appointmentRepo;
        $this->appRequestRepo  = $appRequestRepo;
    }

    public function index()
    {
        $page_title = 'Bookings';
        $app_requests = $this->appRequestRepo->getRequestForCoach(Auth::guard('coach')->id());
        return view('coach.bookings', compact('page_title', 'app_requests'));
    }

    /**
     * Returns 
     * @param  Request $request
     * @param  String  $type      future/past
     * @return Yajra/Datatable
     */
    public function getDatatables(Request $request, $type)
    {
        $inputs             = $request->all();
        $coach_id           = Auth::guard('coach')->id();
        $appointments       = $this->appointmentRepo->getDataTableCoach($coach_id, $type);
        return DataTables::of($appointments)
                        ->addColumn('actual_start', function($appointment) {
                            return $appointment->start;
                        })
                        ->addColumn('call_active_start', function($appointment) {
                            return $appointment->start->subMinutes(5);
                        })
                        ->addColumn('actual_end', function($appointment) {
                            return $appointment->end;
                        })
                        ->addColumn('start_time', function ($appointment) {
                          return $appointment->start->format('H:i A');
                        })
                        ->addColumn('duration', function ($appointment) {
                          return $appointment->end->diffInMinutes($appointment->start);
                        })
                        ->editColumn('start', function ($appointment) {
                            return $appointment->start->format('Y-m-d');
                        })
                        ->editColumn('end', function ($appointment) {
                            return $appointment->end->format('H:i A');
                        })
                        ->filterColumn('user.first_name', function($query, $keyword) {
                            $query->whereHas("user", function($query) use ($keyword) {
                              $query
                                ->whereRaw('CONCAT(users.first_name," ",users.last_name) like ?', ["%{$keyword}%"]);
                            });
                        })
                        //->orderColumn('users.name', 'users.first_name $1')
                        ->toJson();    
    }

    public function cancelAppointment(Request $request)
    {
        $appointment_id = $request->appointment_id;
        $coach_id = Auth::guard('coach')->id();
        $user = $this->userRepo->get($coach_id);
        $result = $this->appointmentRepo->cancelAppointment($appointment_id, $coach_id);
        if($result) {
            // Fire Appointment Cancelled Event
            $appointment = $this->appointmentRepo->get($appointment_id);
            event(new AppointmentCancelledEvent($appointment));
            return response()->json(['success'=> 'true', 'message'=> 'Session cancelled.'], 200);
        }
        else
            return response()->json(['success'=> 'false', 'message'=> 'Unable to cancel selected session.'], 200);
    }

    public function downloadInvoice($appointment_id)
    {
        $coach_id = Auth::guard('coach')->id();
        $appointment = $this->appointmentRepo->get($appointment_id);
        if($coach_id!=$appointment->coach_id)
            abort(401);
        $file_name = $appointment->status=='cancelled'?'Gutschrift_'.$appointment_id:'Rechnung_'.$appointment_id;
        $pdf_obj = $this->appointmentRepo->createInvoice($appointment, 'coach', true);
        return $pdf_obj->download($file_name.'.pdf');
    }

    public function acceptRequest($request_id, Request $request)
    {
        $coach_id = Auth::guard('coach')->id();
        $slot_id  = $request->slot_id;
        $this->appRequestRepo->updateRequest($request_id, 'coach', $coach_id, 'coach_accepted', $slot_id);
        
        // Notify user by email
        $appRequest = $this->appRequestRepo->get($request_id);
        Mail::to($appRequest->user->email)->send(new AppmntRequestAccepted($appRequest));

        return response()->json(['success' => 'true', 'message' => 'Request accepted succesfully'], 200);
    }

    public function declineRequest($request_id)
    {
        $coach_id = Auth::guard('coach')->id();
        // Check if appointment is not booked with similar timeslot
        $this->appRequestRepo->updateRequest($request_id, 'coach', $coach_id, 'coach_rejected');
        // Create appointment with payment pending
        // Notify user about the request acceptance and link to pay and book
        $appRequest = $this->appRequestRepo->get($request_id);
        Mail::to($appRequest->user->email)->send(new AppmntRequestRejected($appRequest));
        return response()->json(['success' => 'true', 'message' => 'Request rejected'], 200);
    }

}