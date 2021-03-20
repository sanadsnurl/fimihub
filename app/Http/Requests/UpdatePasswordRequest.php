<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;


class UpdatePasswordRequest extends FormRequest
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
            'password'=>'required|min:6',
            'new_password'=>'required|min:6'
            
        ];
        return $validator;
    }
}
