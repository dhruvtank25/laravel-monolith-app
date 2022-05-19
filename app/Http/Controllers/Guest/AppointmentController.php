<?php

namespace App\Http\Controllers\Guest;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;
use App\Repositories\AppointmentRequestRepository;
use App\Traits\AppointmentTrait;
use Auth;

class AppointmentController extends Controller
{
    
    use AppointmentTrait;

    function __construct(AppointmentRepository $appointmentRepo, UserRepository $userRepo,  AppointmentRequestRepository $appRequestRepo)
    {
        $this->userRepo         = $userRepo;
        $this->appointmentRepo  = $appointmentRepo;
    }

    public function index()
    {
        $page_title = 'Bookings';
        $user_id    = $this->guard()->id();
        $appointments = $this->appointmentRepo->getGuestAppointment($user_id);
        return view('guest.bookings', compact('page_title', 'appointments'));
    }
        
    public function call_summary()
    {
        return view('guest.call_summary');
    }

    /**
     * Get the guard to be used.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('guest_user');
    }

}