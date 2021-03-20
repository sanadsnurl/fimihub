<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;


class SetPaymentMethod extends FormRequest
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
        'payment' => 'required|exists:payment_methods,payment_id',
        'cvv' => 'required_if:payment,4|digits:3|nullable',
        'card_expiry_date' => 'required_if:payment,4|nullable',
        'card_number' => 'required_if:payment,4|nullable',
        'person_name' => 'required_if:payment,4|string|nullable',
        'remember_card' => 'integer|nullable',
        'address_id' => 'required|integer|exists:user_address,id'
        ];
        return $validator;
    }
}
