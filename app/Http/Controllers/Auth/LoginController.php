<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
   

   use AuthenticatesUsers {
       login as protected mainlogin;
   }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guards = array_keys(config('auth.guards'));
                
        foreach ($guards as $guard) {
            $this->middleware('guest:'.$guard)->except(['logout', 'guestLogin', 'shadowLogin']);
        }
        //$this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm($guard_name='', Request $request)
    {
        $redirect_to = '';
        if($request->has('redirect_to'))
            $redirect_to = $request->redirect_to;
        if($guard_name!='') {
            $guards = array_keys(config('auth.guards'));
            if(!in_array($guard_name, $guards)){
                return abort(404);
            }
        }
        return view('auth.login',compact('guard_name', 'redirect_to'));
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        $requested_guard = request()->guard;
        if($requested_guard==''){
            return Auth::guard();
        }else{
            return Auth::guard($requested_guard);
        }
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function login(Request $request)
    {           
        return $this->mainlogin($request);
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $cred_arr = $this->credentials($request);
        $cred_arr['can_login'] = 1;
        return $this->guard()->attempt(
            $cred_arr, $request->filled('remember')
        );
    }

    /**
     * Override laravel authenticated method for custom use
     * @param  Request $request 
     * @param  mixed    $user    
     * @return mixed 
     */
    protected function authenticated(Request $request, $user)
    {
        // Check if user tried booking coach before login
        $appointment = session('appointment_data', null); // default null

        $guard_name = $request->guard;
        if($guard_name==''){
            $user_id    = $user->id;
            $guard_name = $user->roles->name;
            if($guard_name!='user'){
                // Login to logged in users guard
                Auth::logout();
                Auth::guard($guard_name)->loginUsingId($user_id);
            }
        }
        $userArr = $user->toArray();
                
        Session::put('user', json_encode($userArr));
        if ($request->expectsJson()) {
            return response()->json(['success'=>'true'], 200);
        }

        if($request->has('redirect_to') && $request->redirect_to)
            return redirect($request->redirect_to);

        // Save appointment if login is part of appointment flow
        if($appointment!=null && $guard_name=='user')
            return redirect('book-coach');

        return redirect()->route($guard_name);
        //return redirect($guard_name.'/');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        if(isset(request()->email) && request()->email!='')
            return 'email';
        return 'username';
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function logout($guard_name='')
    {
        if($guard_name==''){
            Auth::logout();
        }else{
            Auth::guard($guard_name)->logout();
        }
        //clear all session data
        Session::flush();
        return redirect('/login');
    }

    /**
     * Login as other user (only for superadmin/admin)
     * @param  String $guard_name   Type of user to login as
     * @param  String $Id           Id of the user to login as
     * @return \Illuminate\Http\Response
     */
    public function shadowLogin($guard_name, $Id)
    {
        if(Auth::guard('admin')->check()){
            if(Auth::guard($guard_name)->check())
                Auth::guard($guard_name)->logout();
            Auth::guard($guard_name)->loginUsingId($Id);
            return redirect($guard_name.'/dashboard');
        }else{
            return redirect('/login/admin');
        }
    }

    /**
     * Login guest by link
     * @param  Integer  $userId
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function guestLogin($userId, Request $request)
    {
        // Sign url generation
        /*echo \URL::signedRoute('guest.login', ['user' => $userId]);
        exit;*/
        // Validate signed url
        if (! $request->hasValidSignature())
            abort(401);
        $user = \App\Models\User::find($userId);
        if(!$user || $user->roles->name!='guest')
            abort(404);
        Auth::guard('guest_user')->login($user);
        return redirect()->route('guest_user.bookings');
    }

}
