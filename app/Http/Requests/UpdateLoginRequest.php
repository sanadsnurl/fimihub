<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Auth;

class UpdateLoginRequest extends FormRequest
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
            'name' => 'string|max:150',
            'mobile' => 'numeric|digits:10|unique:users,mobile,'. Auth::id(),
            'country_code' => 'string|nullable',
            'email' => 'nullable|email|unique:users,email,'. Auth::id(),
            'vehicle_number' => 'string',
            'model_name' => 'string',
            'vehicle_image' => 'nullable|mimes:png,jpg,jpeg|max:8192',
            'color' => 'string',
            'id_proof' => 'nullable|mimes:png,jpg,jpeg|max:8192',
            'address' => 'string',
            'pincode' => 'numeric',
            'driving_license' => 'nullable|mimes:png,jpg,jpeg|max:8192',
            'background_check' => 'mimes:png,jpg,jpeg,pdf|max:8192|nullable',
            'food_permit' => 'mimes:png,jpg,jpeg,pdf|max:8192|nullable',
            'dl_start_date' => 'string',
            'dl_end_date' => 'string',
            'registraion_start_date' => 'string',
            'registraion_end_date' => 'string',
            'account_number' => 'string',
            'holder_name' => 'string|max:150',
            'branch_name' => 'string|max:150',
            'branch_name' => 'string|max:150',
            'ifsc_code' => 'string|nullable',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'registration_number' => 'string|nullable',
            'policy_company	' => 'string|nullable',
            'insurance_company' => 'string|nullable',
            'insurance_start_date' => 'string|nullable',
            'insurance_end_date' => 'string|nullable',
        ];
        return $validator;
    }
}
