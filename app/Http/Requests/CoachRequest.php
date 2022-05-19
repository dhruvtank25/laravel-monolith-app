<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Auth;
use App\Models\Role;

class CoachRequest extends FormRequest
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
        $validate_arr = [];
        switch($this->method())
        {
            case 'POST':
            {
                $validate_arr['first_name']        = 'required';
                $validate_arr['last_name']         = 'required';
                $validate_arr['email']             = [
                                                        'required',
                                                        'email',
                                                        Rule::unique('users')->where(function($query) use ($guest_id) {
                                                            return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                        }),
                                                    ];
                $validate_arr['phone_number']      = 'required';
                $validate_arr['password']          = 'required|confirmed';
                $validate_arr['birth_date']        = 'required|date_format:Y-m-d|before:18 years ago';
                $validate_arr['nationality']       = 'required';
                if(!Auth::guard('admin')->check()) {
                    $validate_arr['terms_condition']   = 'required';
                    $validate_arr['privacy_policy']    = 'required';
                    $validate_arr['agree_credentials'] = 'required';
                }
                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                if(Auth::guard('coach')->check()) {
                    $ignore_id = Auth::guard('coach')->id();
                } else if(Auth::guard('admin')->check() && $this->route('user')!==null) {
                    $ignore_id = $this->route('user');
                } else {
                    abort(401);
                }

                $validate_arr = $this->basicValidations($validate_arr, $ignore_id, $guest_id);
                break;
            }
        }
        return $validate_arr;
    }

    public function basicValidations($validate_arr, $user_id=0, $guest_id=4)
    {
        $method = $this->method();
        $validate_arr = [];

        // If basic info present for coach (first tab)
        if(isset($this->email)) {
            $validate_arr['first_name']   =  'required';
            $validate_arr['last_name']    =  'required';
            $validate_arr['email']        =    [
                                                'required',
                                                'email',
                                                Rule::unique('users')->ignore($user_id)->where(function($query) use ($guest_id) {
                                                    return $query->where('role_id', '!=', $guest_id)->whereNull('deleted_at');
                                                }),
                                            ];
            $validate_arr['phone_number'] = 'required';
            $validate_arr['birth_date']   = 'required|date_format:Y-m-d|before:18 years ago';
            $validate_arr['nationality']  = 'required';
            $validate_arr['id_doc']       = 'required';
        }

        // Coach other detail validations
        if($this->exists('coach_company') || $this->exists('person_type') || $this->exists('price_per_hour'))
            $validate_arr = $this->coachCompanyValidations($validate_arr, $method);
        if($this->exists('owner_name') || $this->exists('iban') || $this->exists('bic'))
            $validate_arr = $this->coachBankValidations($validate_arr, $method);
        if($this->exists('description'))
            $validate_arr =  $this->coachKnowledgeValidations($validate_arr, $method);
        return $validate_arr;
    }

    public function coachCompanyValidations($validate_arr, $method='POST')
    {
        $validate_arr['coach_company']           = 'required';

        // Addresses validation
        $validate_arr['latitude']                = 'required';
        $validate_arr['longitude']               = 'required';
        $validate_arr['street']                  = 'required';
        $validate_arr['post_code']               = 'required';
        $validate_arr['place']                   = 'required';
        $validate_arr['country']                 = 'required';
        $validate_arr['country_code']            = 'required';
        if($this->different_work==1) {
            $validate_arr['work_latitude']       = 'required';
            $validate_arr['work_longitude']      = 'required';
            $validate_arr['work_street']         = 'required';
            $validate_arr['work_post_code']      = 'required';
            $validate_arr['work_place']          = 'required';
            $validate_arr['work_country']        = 'required';
            $validate_arr['work_country_code']   = 'required';
        }

        // Company Validation
        $validate_arr['person_type']             = 'required';
        $validate_arr['tax_number']              = 'required_without:ust_id';
        $validate_arr['ust_id']                  = 'required_without:tax_number';

        if($this->person_type=='business') {
            $validate_arr['company_type']        = 'required';
            $validate_arr['company_number']      = 'required|alpha_num';
            // Shareholder validation
            
            // Shareholder validation end
            //$validate_arr['commercial_doc']      = 'required';
        }

        if($this->person_type=='business' || $this->is_commercial)
            $validate_arr['ustid_doc']               = 'required';
        $validate_arr['agree_ustid']             = 'required';
        $validate_arr['impressum']               = 'required';
        //Company Validation end
        
        $validate_arr['price_per_hour']          = 'required|numeric|min:20';
        return $validate_arr;
    }

    public function coachBankValidations($validate_arr, $method='POST')
    {
        $validate_arr['owner_name']         = 'required';
        $validate_arr['iban']             = [
                                                'required',
                                                'regex:/^[a-zA-Z]{2}\d{2}\s*(\w{4}\s*){2,7}\w{1,4}\s*$/',
                                            ];
        $validate_arr['bic']              = [
                                                'required','
                                                regex:/^[a-zA-Z]{6}\w{2}(\w{3})?$/',
                                            ];
        return $validate_arr;
    }

    public function coachKnowledgeValidations($validate_arr, $method='POST')
    {
        $validate_arr['categories']   = 'required|array|min:1';
        $validate_arr['language']     = 'required|array|min:1';
        $validate_arr['priorities']   = 'required|array|min:1';
        $validate_arr['description']  = 'required';
        return $validate_arr;
    }

}
