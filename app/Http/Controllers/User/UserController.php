<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Repositories\AppointmentRepository;
use App\Repositories\MangoPayRepository;
use App\Repositories\CountryRepository;
use App\Mail\deleteUserProfileMail;
use DataTables;
use Auth;
use Mail;

class UserController extends Controller
{

    function __construct(AppointmentRepository $appointmentRepo,
                         MangoPayRepository $mangoPayRepo, CountryRepository $countryRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
        $this->mangoPayRepo    = $mangoPayRepo;
        $this->countryRepo  = $countryRepo;
    }

    public function index()
    {
        $page_title   = 'My Profile';
        $user         = Auth::guard('user')->user();
        $countries    =  $this->countryRepo->getForDropdown();
        $card_details = $this->mangoPayRepo->getUserActiveCard($user->mango_user_id);
        return view('user.profile', compact('page_title', 'user', 'countries', 'card_details'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @return Response
     */
    public function update(UserRequest $request)
    {
        $inputs = $request->all();
        
        $user         = Auth::guard('user')->user();

        // Encrypt Password
        if(isset($inputs['password']))
            $inputs['password']     = bcrypt($inputs['password']);
        else
            unset($inputs['password']);

        if(!isset($inputs['is_anonymous']))
            $inputs['is_anonymous'] = 0;

        // Update to DB
        $user->fill($inputs);
        $user->save();

        $message = 'User updated successfully.';
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true', 'message'=> $message], 200);
        }
        return redirect()->back()->withSuccess($message);
    }

    public function nextAppointment()
    {
        //get current user
        $user = Auth::user();
        $status = array('payment pending','payment cancelled', 'scheduled');

        //get bookings
        $appointments =  $this->appointmentRepo->getBookings($user->id,$status);

        return DataTables::of($appointments)->toJson();
    }

    public function deleteProfile(Request $request)
    {
        $user   = Auth::guard('user')->user();
        if(isset($request->existing_password)) {
            if(!\Hash::check($request->existing_password, $user->password)){
                return response()->json(['success'=> 'false', 'message'=> 'Invalid current password.'], 200);
            }
            else {
                Mail::to($user->email)->send(
                    new deleteUserProfileMail($user)
                );
                $user->delete();
                Auth::guard('user')->logout();
                return response()->json(['success'=> 'true', 'message'=> 'Profile deleted'], 200);
            }
        } else {
            return response()->json(['success'=> 'false', 'message'=> 'Please enter your password to delete profile.'], 200);
        }
    }

}
