<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Role;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $guest_id = Role::where('name', 'guest')->first()->id;
        $validate_arr = array(
                            'first_name' => 'required',
                            'last_name'  => 'required',
                            'street'     => 'required',
                            'post_code'  => 'required',
                            'place'      => 'required',
                        );
        switch($this->method())
        {
            case 'POST':
            {
                if($this->type!='guest') {
                    $validate_arr['email']  = [
                                                'required',
                                                'email',
                                                Rule::unique('users')->where(function($query) use ($guest_id) {
                                                    return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                }),
                                            ];
                    $validate_arr['password']   = 'required|confirmed';
                    $validate_arr['user_name']  = [
                                                    'nullable',
                                                    Rule::unique('users')->where(function($query) use ($guest_id) {
                                                        return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                    }),
                                                ];
                } else {
                    $validate_arr['email']      = 'required|email';
                    $validate_arr['user_name']  = 'nullable';
                }
                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                if(Auth::guard('user')->check()) {
                    $ignore_id = Auth::guard('user')->id();
                } else if(Auth::guard('admin')->check() && $this->route('user')!==null) {
                    $ignore_id = $this->route('user');
                } else {
                    abort(401);
                }

                $validate_arr['email'] =    [
                                                'required',
                                                'email',
                                                Rule::unique('users')->ignore($ignore_id)->where(function($query) use ($guest_id) {
                                                    return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                }),
                                            ];
                $validate_arr['user_name'] = [
                                                //'required',
                                                'nullable',
                                                Rule::unique('users')->ignore($ignore_id)->where(function($query) use ($guest_id) {
                                                    return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                }),
                                            ];
                break;
            }
        }
        return $validate_arr;
    }

}
