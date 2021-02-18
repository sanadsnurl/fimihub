<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class AddToCartRequest extends FormRequest
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
            'restaurant_id' => 'required|numeric|exists:restaurent_details,id',
            'menu_id' => 'required|numeric|exists:menu_list,id',
            'variant_id' => 'numeric|nullable',
            'add_on_id' => 'numeric|nullable',
            'action_type' => 'required|numeric|in:1,2,3',
        ];
        return $validator;
    }
}
