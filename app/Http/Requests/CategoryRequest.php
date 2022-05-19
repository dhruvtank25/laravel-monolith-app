<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
                        'title'  => 'required',
                    ];
        switch($this->method())
        {
            case 'POST':
            {
                $validate_arr['icon']   = 'required|mimes:svg';
                $validate_arr['banner'] = 'required|image';
                break;
            }
            case 'PUT':
            case 'PATCH':
            {
                break;
            }
        }
        return $validate_arr;
    }
}
