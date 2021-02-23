<?php

namespace App\Http\Controllers\Api\Rider;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetOrderedMenuRequest;
use Illuminate\Http\Request;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\LatLongRadiusScopeTrait;
use App\Model\MyEarning;
use App\Model\Notification;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\ServiceCategory;
use App\User;
use Auth, Validator;

class OrderController extends Controller
{
    use NotificationTrait, LatLongRadiusScopeTrait;

    public function __construct(order $order, OrderEvent $orderEvent, MyEarning $myEarning) {
        $this->order = $order;
        $this->orderEvent = $orderEvent;
        $this->myEarning = $myEarning;
    }

    public function testingNotification()
    {
        $sender_data = Auth::user();
        $push_notification_sender=array();
        $push_notification_sender['device_token'] = $sender_data->device_token;
        $push_notification_sender['title'] = 'Testing notification';
        $push_notification_sender['notification']='Testing notification body';

        $notification_sender=array();
        $notification_sender['user_id'] = $sender_data->id;
        $notification_sender['txn_id'] = 'TESTNOTIFICATION123';
        $notification_sender['title'] = 'Testing notification';
        $notification_sender['notification']='Testing notification body';
        $notification = new notification();
        $notification_id = $notification->makeNotifiaction($notification_sender);

        $push_notification_sender_result=$this->pushNotification($push_notification_sender);
        return response()->json(['message'=> 'Testing notification','status' =>true ], $this->successStatus);
    }

    public function getOrders(Request $request, int $orderId = 0)
    {
        if ($orderId) {
            $order = $this->order->getOrder($orderId)
            ->with('restaurentDetails.restroAddress','userAddress.userDetails','restaurentDetails.restroAddress','cart.cartItems.menuItems')
            ->first();
            if(isset($order->ordered_menu)){
                $order->ordered_menu = json_decode($order->ordered_menu);

            }
        } else {

            $user = auth()->user();
            if(is_numeric($request->input('lng'))) {
                $lng =$request->input('lng');
            } else {
                return response()->json(['message' => 'Unable to detect location', 'status' => false], $this->successStatus);
            }
            if(is_numeric($request->input('lat'))) {
                $lat =$request->input('lat');
            } else {
                return response()->json(['message' =>'Unable to detect location', 'status' => false], $this->successStatus);
            }
            // $kmRadius = $this->max_distance_km_order ?? '100';
            $order = $this->riderClosestOrders($user, $lat, $lng)
            ->with('restaurentDetails.restroAddress','userAddress.userDetails')
            ->paginate(10);
            foreach($order as $value) {
                $value->ordered_menu = json_decode($value->ordered_menu);
            }
        }
        return response()->json(['data' => $order, 'message' => 'Success', 'status' => true], $this->successStatus);
    }

    public function getActiveOrder(Request $request, int $orderId = 0) {
        if ($orderId) {
            $order = $this->order->getActiveOrders($orderId)
            ->with('userAddress.userDetails','restaurentDetails.restroAddress','cart.cartItems.menuItems','orderEvent.reason')
            ->first();
            if(isset($order->ordered_menu)){
                $order->ordered_menu = json_decode($order->ordered_menu);

            }

        } else {

            $order = $this->order->getActiveOrders($orderId)
            ->with('restaurentDetails.restroAddress','userAddress.userDetails', 'orderEvent.reason')
            ->paginate(10);
            foreach($order as $value) {
                $value->ordered_menu = json_decode($value->ordered_menu);
            }
        }
        return response()->json(['data' => $order, 'message' => 'Success', 'status' => true], $this->successStatus);
    }

    public function updateEventOrderStatus(Request $request) {
        $validator = $this->validateUpdateStatus();

        if($validator->fails()) {
            $message = collect($validator->messages())->values()->first();
            return response()->json(['data' => $message[0], 'message' => 'Validation failed', 'status' => false], $this->successStatus);
        }
        $id = Auth::id();
        $orderId = $request->input('order_id');
        $orderStatus = $request->input('order_status');
        $data = array(
            'user_id' => $id,
            'order_status' => $orderStatus,
            'user_type' => 1,
            'visibility' => 0,
            'order_id' => $orderId
        );

        $alreadyAssigned = $this->orderEvent->orderAlreadyAssigned($orderId)->first();
        if(!empty($alreadyAssigned)) {
            return response()->json(['message' => 'Already assigned to other rider. Please refresh', 'status' => false], $this->successStatus);
        }

        if($orderStatus == 6) { // // Order rejected by rider
            $data['reason_id'] = $request->input('reason_id');
            $data['order_comment'] = $request->input('order_comment');
            $this->orderEvent->updateStatus($orderId, $data);
            // $this->order->updateStatus($orderId, 8); // 8-rider_cancel
        } else if($orderStatus == 5) { // Order delivered
            $this->order->updateStatus($orderId, 9); // 9-received
            $orderDetails = $this->order->getOrder($orderId)->first();
                // To do
                $price = $orderDetails->total_amount;
                $collectedPrice = $request->input('price');
                $ServiceCategories = new ServiceCategory();
                $service_data = $ServiceCategories->getServiceById(1);
                $rider_earning = (($service_data->rider_commission / 100) * $orderDetails->delivery_fee);

                $delivery_fee = $orderDetails->delivery_fee;
                $total_amount_c = round(abs($orderDetails->total_amount - $delivery_fee),2);
                $tax = $orderDetails->service_tax;
                $sub_total = $total_amount_c / (1 + ($tax / 100));
                $commission = $orderDetails->service_commission;
                $total_earning_resto = $sub_total / (1 + ($commission / 100));
                $total_earning_resto = round($total_earning_resto,2);
// return ($total_earning_resto);

            if($request->input('payment_type') == 3) {
                    $earning = array(
                        'user_id' => $id,
                        'order_id' => $orderId,
                        'ride_price' => $rider_earning,
                        'cash_price' => $price,
                        'resto_commission' => $total_earning_resto,
                    );
                    $this->myEarning->updateEarning($earning, $orderId);

            } else {
                $earning = array(
                    'user_id' => $id,
                    'order_id' => $orderId,
                    'ride_price' => $rider_earning,
                    'cash_price' => null,
                    'resto_commission' => $total_earning_resto,
                );
                $this->myEarning->updateEarning($earning, $orderId);
            }

            $this->orderEvent->updateStatus($orderId, $data);
        } else if($orderStatus == 4) { //  On the way
            $this->orderEvent->updateStatus($orderId, $data);
            //$this->order->updateStatus($orderId, 12); // 12-rider on the way

        } else if($orderStatus == 3) { // Order Picked Up
            $this->orderEvent->updateStatus($orderId, $data);
            $this->order->updateStatus($orderId, 7); // 7-rider picked product

        } else if($orderStatus == 1) { // Arriving to store
            $this->orderEvent->updateStatus($orderId, $data);
            //$this->order->updateStatus($orderId, 11); // 11-assigned to rider

        } else {
            $this->orderEvent->updateStatus($orderId, $data);
        }

        return response()->json(['data' => $data, 'message' => 'Status updated successfully.', 'status' => true], $this->successStatus);
    }

    public function getMyPreviusOrders(Request $request, int $orderId = 0)
    {
        if ($orderId) {
            $order = $this->order->getMyPreviusOrders($orderId)
            ->with('restaurentDetails.restroAddress','userAddress.userDetails','restaurentDetails','cart.cartItems.menuItems', 'orderEvent.reason')
            ->first();
            if(isset($order->ordered_menu)){
                $order->ordered_menu = json_decode($order->ordered_menu);

            }
        } else {

            $order = $this->order->getMyPreviusOrders($orderId)
            ->with('restaurentDetails.restroAddress','userAddress.userDetails', 'orderEvent.reason')
            ->paginate(10);
            foreach($order as $value) {
                $value->ordered_menu = json_decode($value->ordered_menu);
            }
        }

        return response()->json(['data' => $order, 'message' => 'Success', 'status' => true], $this->successStatus);
    }


    public function validateUpdateStatus() {
        return Validator::make(request()->all(), array(
            'order_status' => 'required|numeric|in:1,2,3,4,5,6',
            'order_id' => 'required|numeric',
            'reason_id' => 'nullable|required_if:order_status,6|nullable',
            'order_comment' => 'nullable|required_if:order_status,6|string',
            'payment_type' => 'nullable|required_if:order_status,5|numeric',
            'price' => 'nullable|required_if:payment_type,3|numeric',
        ));
    }

    public function getOrderedData(GetOrderedMenuRequest $request){
        $order_id = request('order_id');
        $orders = new order();
        $order_data = $orders->getOrderData($order_id);
        if(isset($order_data->ordered_menu)){

            $order_data->ordered_menu = json_decode($order_data->ordered_menu);
            $ordered_menu = $order_data->ordered_menu;


        }
        // dd($ordered_menu);

        foreach ($order_data->ordered_menu as $ordered_menu) {
            $ordered_menu->ordered_add_on = array();
            $ordered_menu->ordered_variant = array();
            foreach ($ordered_menu->add_on as $add_datas) {
                foreach ($add_datas as $add_data) {
                    if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                        $ordered_menu->ordered_add_on[] = $add_data;
                    }
                }
            }
            foreach ($ordered_menu->variant_data as $v_data) {
                if ($ordered_menu->cart_variant_id == $v_data->id) {
                    $ordered_menu->ordered_variant[] = $v_data;
                }
            }
        }
        return response()->json(['data'=>$order_data->ordered_menu,'message' => 'Success ', 'status' => true], $this->successStatus);
    }
}
