<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
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
        $validate_arr = [
                        'name'  => 'required',
                    ];
        switch($this->method())
        {
            case 'POST':
            {
                $validate_arr['code'] = 'required|unique:countries,code';
                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                $validate_arr['code'] = [
                                                'required',
                                                Rule::unique('countries')->ignore($this->country),
                                            ];
                break;
            }
        }
        return $validate_arr;
    }
}
