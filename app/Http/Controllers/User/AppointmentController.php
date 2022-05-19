<?php

namespace App\Http\Controllers\User;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRequestRepository;
use App\Mail\AppmntRequestAccepted;
use App\Traits\AppointmentTrait;
use DataTables;
use Auth;
use Mail;

class AppointmentController extends Controller
{

    use AppointmentTrait;

    function __construct(AppointmentRepository $appointmentRepo, UserRepository $userRepo, AppointmentRequestRepository $appRequestRepo)
    {
        $this->userRepo         = $userRepo;
        $this->appointmentRepo  = $appointmentRepo;
        $this->appRequestRepo   = $appRequestRepo;
    }

    public function index()
    {
        $page_title = 'Bookings';
        $app_requests = $this->appRequestRepo->getRequestForUser($this->guard()->id());
        return view('user.bookings', compact('page_title', 'app_requests'));
    }

    public function getNextAppointmentDatatables(Request $request)
    {
        $inputs       = $request->all();
        //get current user
        $user = $this->guard()->user();

        //set datatable
        $request_table = isset($inputs[0])?$inputs[0]:'';
        
        $appointments = $this->appointmentRepo->getBookings($user->id , $request_table);
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
                            ->filterColumn('categories.title', function($query, $keyword) {
                                $query->whereHas("categories", function($query) use ($keyword) {
                                $query
                                    ->whereRaw('categories.title like ?', ["%{$keyword}%"]);
                                });
                            })
                            ->filterColumn('user.first_name', function($query, $keyword) {
                                $query->whereHas("user", function($query) use ($keyword) {
                                    $query
                                    ->whereRaw('CONCAT(users.first_name," ",users.last_name) like ?', ["%{$keyword}%"]);
                                });
                            })
                            ->filterColumn('coach.first_name', function($query, $keyword) {
                                $query->whereHas("coach", function($query) use ($keyword) {
                                    $query
                                    ->whereRaw('CONCAT(users.first_name," ",users.last_name) like ?', ["%{$keyword}%"]);
                                });
                            })
                            ->toJson();
    }

    public function acceptRequest($request_id, Request $request)
    {
        $user_id = $this->guard()->id();
        $slot_id  = $request->slot_id;
        $this->appRequestRepo->updateRequest($request_id, 'user', $user_id, 'user_accepted', $slot_id);

        // Notify user by email
        $appRequest = $this->appRequestRepo->get($request_id);
        Mail::to($appRequest->user->email)->send(new AppmntRequestAccepted($appRequest));
        
        return response()->json(['success' => 'true', 'message' => 'Request accepted succesfully'], 200);
    }

    public function declineRequest($request_id)
    {
        $user_id = $this->guard()->id();
        // Check if appointment is not booked with similar timeslot
        $this->appRequestRepo->updateRequest($request_id, 'user', $user_id, 'user_rejected');
        // Create appointment with payment pending
        // Notify coach about the request acceptance and link to pay and book
        return response()->json(['success' => 'true', 'message' => 'Request rejected'], 200);
    }

    public function call_summary()
    {
        return view('user.call_summary');
    }
    
    /**
     * Get the guard to be used.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('user');
    }


}