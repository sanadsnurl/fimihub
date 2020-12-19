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
            'pincode' => 'digits:6',
            'driving_license' => 'nullable|mimes:png,jpg,jpeg|max:8192',
            'dl_start_date' => 'string',
            'dl_end_date' => 'string',
            'registraion_start_date' => 'string',
            'registraion_end_date' => 'string',
            'account_number' => 'string',
            'holder_name' => 'string|max:150',
            'branch_name' => 'string|max:150',
            'ifsc_code' => 'string|nullable',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ];
        return $validator;
    }
}
