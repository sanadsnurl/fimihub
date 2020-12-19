<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class UserStoreRequest extends FormRequest
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
            'name' => 'required|string|max:150',
            'password' => 'required|string|min:6',
            'mobile' => 'required|numeric|unique:users',
            'country_code' => 'string|nullable',
            'email' => 'email|unique:users|nullable',
            'device_token' => 'required|string',
            'vehicle_number' => 'required|string',
            'model_name' => 'required|string',
            'vehicle_image' => 'mimes:png,jpg,jpeg|max:8192|nullable',
            'color' => 'required|string',
            'id_proof' => 'required|mimes:png,jpg,jpeg|max:8192|nullable',
            'address' => 'required|string',
            'pincode' => 'required|digits:6',
            'driving_license' => 'required|mimes:png,jpg,jpeg|max:8192|nullable',
            'dl_start_date' => 'required|string',
            'dl_end_date' => 'required|string',
            'registraion_start_date' => 'required|string',
            'registraion_end_date' => 'required|string',
            'account_number' => 'required|string',
            'holder_name' => 'required|string|max:150',
            'branch_name' => 'required|string|max:150',
            'ifsc_code' => 'string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',

        ];
        return $validator;

    }


}
