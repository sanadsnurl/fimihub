<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class UserLoginRequest extends FormRequest
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
        $validator = [
            'userid' => 'required|numeric',
            'device_token' => 'string|nullable',
            'email'=>'email',
            'country_code'=>'string|nullable',
            'mobile'=>'numeric|digits:10',
            'password'=>'required|min:6',
            'new_password'=>'min:6'

        ];
        return $validator;
    }
}
