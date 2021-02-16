<?php

namespace App\Http\Requests\user;

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
            'user_id' => 'required|string|max:150',
            'password' => 'required|string|min:6',
            'country_code' => 'string|nullable',
            'device_token' => 'string|nullable',
        ];
        return $validator;
    }
}
