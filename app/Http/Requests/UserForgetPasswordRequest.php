<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class UserForgetPasswordRequest extends FormRequest
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
            'userid' => 'required',
            'email'=>'email',
            'mobile'=>'numeric|digits:10',
            'verification_code'=>'required|digits:6',
            'password'=>'required|min:6'
            
        ];
        return $validator;
    }
}
