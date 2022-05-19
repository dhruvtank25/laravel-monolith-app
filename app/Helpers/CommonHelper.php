<?php

namespace App\Helpers;

use Auth;

class CommonHelper
{
    /**
     * Returns Logged in user type and details
     * @return Array
     */
    public static function checkUserType()
    {
        if(Auth::guard('admin')->check()) {
            $type = 'admin';
            $user = Auth::guard('admin')->user();
        }else if(Auth::guard('coach')->check()) {
            $type = 'coach';
            $user = Auth::guard('coach')->user();
        } else if(Auth::guard('user')->check()) {
            $type = 'user';
            $user = Auth::guard('user')->user();
        } else if(Auth::guard('guest_user')->check()) {
            $type = 'guest_user';
            $user = Auth::guard('guest_user')->user();
        } else {
            $type = 'guest';
            $user = ['id' => 0];
        }
        return ['type' => $type, 'data' => $user];
    }

    /**
     * Returns name of guard
     * @param  Illuminate\Auth\Guard $guard
     * @return String
     */
    public static function getGuardName($guard)
    {
        $sessionName = $guard->getName();
        $parts = explode("_", $sessionName);
        unset($parts[count($parts)-1]);
        unset($parts[0]);
        $guardName = implode("_",$parts);
        return $guardName;
    }

}