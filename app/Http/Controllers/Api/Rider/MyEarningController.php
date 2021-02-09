<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\MyEarning;
use Auth, Validator;

class MyEarningController extends Controller
{
    public function __construct(MyEarning $myEarning)
    {
        $this->myEarning = $myEarning;
    }

    public function getMyEarning($orderId = false) {
        $userId = Auth::id();
        if($orderId) {
            $response = $this->myEarning->getMyEarning($userId, $orderId)->first();
        } else {
            $response = $this->myEarning->getMyEarning($userId)->get();
        }


        return response()->json(['data' => $response, 'message' => 'success', 'status' => true], $this->successStatus);
    }

    /**
     * 1. Week
     * 2. Month
     * 3. Year
     * 4. Between two dates
     * 5. all
     */
    public function getMyEarningByWeekMonthYear(Request $request) {
        $validator = $this->validateMyEarning();

        if($validator->fails()) {
            $message = collect($validator->messages())->values()->first();
            return response()->json(['message' =>  $message[0], 'status' => false], $this->successStatus);
        }
        $userId = Auth::id();
        $type = $request->input('type');
        $startDate = date('Y-m-d', strtotime($request->input('start_date')));
        $endDate = date('Y-m-d', strtotime($request->input('end_date')));
        $response = $this->myEarning->getMyEarningByWeekMonthYear($userId, $type, $startDate, $endDate)->with('orders')->get();
        foreach($response as $r){
            $r->orders->ordered_menu = json_decode($r->orders->ordered_menu);
            $r->order_id = ($r->orders->order_id);
        }
        if(count($response)) {
            return response()->json(['data' => $response, 'message' => 'success', 'status' => true], $this->successStatus);
        }
        return response()->json(['message' => 'No data found', 'status' => false], $this->successStatus);
    }

    public function validateMyEarning() {
        return Validator::make(request()->all(), array(
            'type' => 'required|integer|in:1,2,3,4,5,6',
            'start_date' => 'nullable|required_if:type,4|date',
            'end_date' => 'nullable|required_if:type,4|date',
        ));
    }
}
