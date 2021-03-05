<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
//user import section
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;

class orderFeedBack extends FormRequest
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
            'restaurant_rating' => 'required|in:1,2,3,4,5',
            'rider_rating' => 'required|in:1,2,3,4,5',
            'resto_feedback' => 'required|string',
            'rider_event_id' => 'required|numeric|exists:order_events,id',
            'resto_event_id' => 'required|numeric|exists:order_events,id',
            ];
        return $validator;
    }
}
