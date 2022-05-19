<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use App\Repositories\CountryRepository;
use Illuminate\Auth\Events\Registered;
use App\Models\Role;
use Carbon\Carbon;
use Auth;
use Session;

class UserController extends Controller
{
    function __construct(UserRepository $userRepo, CountryRepository $countryRepo)
    {
        $this->userRepo     = $userRepo;
        $this->countryRepo  = $countryRepo;
    }

    public function create()
    {
        $page_title = 'User Registration';
        $type       = 'user';
        // If Request is a flow of new appointment
        $appointment = session('appointment_data', null); // default null
        if($appointment!=null)
            $appointment = json_decode($appointment, true);
        $countries  =  $this->countryRepo->getForDropdown();
        return view('user_registeration', compact('page_title', 'appointment', 'countries', 'type'));
    }

    public function guestCreate()
    {
        $page_title = 'Guest Registration';
        $type       = 'guest';
        // If Request is a flow of new appointment
        $appointment = session('appointment_data', null); // default null
        if($appointment==null)
            abort(404);
        $appointment = json_decode($appointment, true);
        $countries  =  $this->countryRepo->getForDropdown();
        return view('user_registeration', compact('page_title', 'appointment', 'countries', 'type'));
    }

    public function store(UserRequest $request)
    {
        $inputs = $request->all();

        $user_type          = $inputs['type'];
        if($user_type=='guest') {
            $guard_name          = 'guest_user';
            $role_obj            = Role::where('name', 'guest')->first();
            $inputs['password']  = 'cbnmbfdsdere2chs';
            $inputs['can_login'] = 0;
            $inputs['email_verified_at'] = date('Y-m-d H:i:s');
        }
        else {
            $guard_name         = 'user';
            $role_obj           = Role::where('name', 'user')->first();
        }
        $inputs['role_id']  = $role_obj->id;

        // Encrypt Password
        $inputs['password']     = bcrypt($inputs['password']);

        // Save to DB
        $user_id = $this->userRepo->add($inputs, false);
        $user    = $this->userRepo->get($user_id);
        
        if($user_type!='guest') {
            // Trigger Registered event, this by default will send verification email
            event(new Registered($user));
        }

        // Check if user tried booking coach before login
        //$appointment = session('appointment_data', null); // default null

        // Login User
        Auth::guard($guard_name)->login($user);

        $message = 'User added successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true', 'id' => $user_id, 'message'=> $message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function complete()
    {
        $page_title = 'User Registration Thank You';
        return view('user_thankyou', compact('page_title'));
    }

    public function checkEmailExists(Request $request)
    {
        // All valid for guest registration
        if(isset($request->type) && $request->type=='guest')
            return 'true';
        $email = $request->email;
        if($email=="")
            return "false";
        $result = $this->userRepo->fieldUniqueCheck('email', $email);
        if(count($result)==0)
            return "true";
        // Check if email is same as logged in user's email
        $result_arr =  \App\Helpers\CommonHelper::checkUserType();
        if($result_arr['data']['id']==$result[0]->id)
            return "true";
        else
            return "false";
    }

    public function checkUsernameExists(Request $request)
    {
        // All valid for guest registration
        if(isset($request->type) && $request->type=='guest')
            return 'true';

        $user_name = $request->user_name;
        if(!$user_name)
            return "true"; // As null value is accepted
        $result = $this->userRepo->fieldUniqueCheck('user_name', $user_name);
        if(count($result)==0)
            return "true";
        // Check if username is same as logged in user's username
        $result_arr =  \App\Helpers\CommonHelper::checkUserType();
        if($result_arr['data']['id']==$result[0]->id)
            return "true";
        else
            return "false";

    }

}