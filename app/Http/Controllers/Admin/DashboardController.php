<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\AppointmentRepository;

class DashboardController extends Controller
{
    function __construct(UserRepository $userRepo, AppointmentRepository $appointmentRepo)
    {
        $this->userRepo        = $userRepo;
        $this->appointmentRepo = $appointmentRepo;
    }

    public function index()
    {
    	$page_title = 'Dashboard';
        $data_arr['user']              = $this->userRepo->getUsers('user')->count();
        $data_arr['coach']             = $this->userRepo->getUsers('coach')->count();
        $data_arr['guest']             = $this->userRepo->getUsers('guest')->count();
        $data_arr['completed_session'] = $this->appointmentRepo->getCounts('completed');
        $data_arr['total_session']     = $this->appointmentRepo->getCounts();
    	return view('admin.dashboard',compact('page_title', 'data_arr'));
    }
}
