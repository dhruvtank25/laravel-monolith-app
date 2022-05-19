<?php

namespace App\Http\Controllers\Admin;

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

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (Auth::guard('admin')->user()) {
            return redirect('/admin/dashboard');
        }
        //$this->middleware('guest')->except('logout');
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * Override laravel authenticated method for custom use
     * @param  Request $request 
     * @param  mixed    $user    
     * @return mixed 
     */
    protected function authenticated(Request $request, $user){
       $userArr = $user->toArray();
       Session::put('user', json_encode($userArr));
       return redirect('admin/dashboard');
       //return redirect()->intended($this->redirectPath());
    }

    protected function getLogin()
    {
        if (Auth::guard('admin')->user()) {
            return redirect('admin/dashboard');
        }
        return view('admin.admin_auth.login');
    }

    protected function logout()
    {
        Auth::guard('admin')->logout();
        //Auth::logout();

        //clear all session data
        //Session::flush();

        return redirect('/admin');
    }
}
