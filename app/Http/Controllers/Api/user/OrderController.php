<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\SetPaymentMethod;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\cart;
use App\Model\cart_submenu;
use App\Model\menu_custom_list;
use App\Model\menu_list;
use App\Model\Notification;
use App\Model\oauth_access_token;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\payment_method;
use App\Model\restaurent_detail;
use App\Model\user_address;
use Response;
use File;

class OrderController extends Controller
{

    public function getPaymentMethod(Request $request){
        $user = Auth::user();

        $payment_methods = new payment_method();
        $payment_method_data = $payment_methods->getPaymentMethodList()->get();

        return response()->json([
            'payment_method_data' => $payment_method_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }

    public function setPaymentMethod(SetPaymentMethod $request){
        $user = Auth::user();
        $data = $request->toarray();

        $cart = new cart;
        $cart_avail = $cart->checkCartAvaibility($user->id);

        $user_address = new user_address();
        $user_add_def = $user_address->getDefaultAddress($user->id);

        if ($cart_avail == NULL) {
            return response()->json([
                'message' => 'Cart Empty !',
                'status' => true
            ], $this->successStatus);
        }else{
            $cart_submenu = new cart_submenu;
            $quant_details = array();
            $quant_details['user_id'] = $user->id;
            $quant_details['cart_id'] = $cart_avail->id;
            $quant_details['restaurent_id'] = $cart_avail->restaurent_id;
            $cart_menu_data = $cart_submenu->getCartMenuList($quant_details);

            if ($cart_menu_data != NULL) {
                $restaurent_detail = new restaurent_detail;
                $resto_data_with_time = $restaurent_detail->checkRestoTimeAvailiability($cart_avail->restaurent_id);
                // if ($resto_data_with_time == NULL) {
                //     return response()->json([
                //         'message' => 'Restaurant Closed !',
                //         'status' => true
                //     ], $this->successStatus);
                // }
                $resto_add_def = $user_address->getUserAddress($resto_data_with_time->user_id) ?? null;

                foreach ($cart_menu_data as $m_data) {

                    $add_ons = array();
                    $add_ons_cat = array();
                    $add_ons_select = array();
                    $add_ons_cat_select = array();
                    $menu_custom_list = new menu_custom_list();
                    $m_data->variant_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id)->get();
                    $m_data->variant_data_cat = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id)->first();
                    $var_sin_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id)->first();
                    if ($m_data->product_add_on_id) {
                        $m_data->product_add_on_id = json_decode($m_data->product_add_on_id) ?? 0;
                    }

                    if (!empty($m_data->variant_data)  && !empty($m_data->cart_variant_id)) {
                        $var_d = $menu_custom_list->getCustomListPrice($m_data->cart_variant_id);
                        $m_data->price = $var_d->price;
                    }
                    if ($m_data->product_add_on_id) {
                        foreach ($m_data->product_add_on_id as $add_on) {
                            $add_ons[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->get();
                            $add_ons_cat[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->first();
                        }
                    }

                    if ($m_data->product_adds_id) {
                        $m_data->product_adds_id = json_decode($m_data->product_adds_id);
                        foreach ($m_data->product_adds_id as $add_on_cart) {
                            $var_ds = $menu_custom_list->getCustomListPrice($add_on_cart);
                        }
                    }

                    $m_data->add_on = ($add_ons);
                    $m_data->add_ons_cat = $add_ons_cat;
                }

                $billing_data_arary = [
                    'menu_id' => false,
                    'order_id' => false,
                    'user_id' => $user->id,
                    'resto_id' => $quant_details['restaurent_id']
                ];
                $billing_balance = ($this->getBilling($billing_data_arary));
                $user['currency'] = $this->currency;

                $delivery_charge = 0;
                if(!empty($user_add_def)){
                    $delivery_distance = $this->getDistanceBetweenPointsNew($user_add_def->latitude,
                                    $user_add_def->longitude,
                                    $resto_add_def[0]->latitude,
                                    $resto_add_def[0]->longitude
                                ) ?? -1;
                }else{
                    $delivery_charge = 0;
                }
                if($delivery_distance == -1){
                    return response()->json([
                        'message' => 'Invalid Address !',
                        'status' => false
                    ], $this->invalidStatus);
                }else{
                    // dd($billing_balance['service_data']);
                    $delivery_distance = (float)str_replace('', 'km', $delivery_distance);
                    if ($delivery_distance <= 10000) {
                        if ($delivery_distance <= $billing_balance['service_data']->on_km) {
                            $delivery_charge = $billing_balance['service_data']->flat_delivery_charge;
                        } else if ($delivery_distance > $billing_balance['service_data']->on_km) {
                            $extra_km = $delivery_distance - $billing_balance['service_data']->on_km;
                            $delivery_charge = $billing_balance['service_data']->flat_delivery_charge + $extra_km * $billing_balance['service_data']->after_flat_delivery_charge;

                        } else {
                            return response()->json([
                                'message' => 'Invalid Address !',
                                'status' => false
                            ], $this->invalidStatus);

                        }
                    } else {
                        return response()->json([
                            'message' => 'No Nearby Restaurant Located !',
                            'status' => true
                        ], $this->successStatus);
                    }
                }
                $orders = new order();
                $add_order = array();
                $add_order['user_id'] = $user->id;
                $add_order['restaurent_id'] = $cart_avail->restaurent_id;
                $add_order['cart_id'] = $cart_avail->id;
                $add_order['address_id'] = $cart_avail->address_id;
                $add_order['customer_name'] =  $user->name;
                $add_order['ordered_menu'] = json_encode($cart_menu_data);
                $add_order['mobile'] =  $user->mobile;
                $add_order['total_amount'] = $billing_balance['total_amount_last'] + (float)$delivery_charge ?? 0;
                $add_order['delivery_fee'] = (float)$delivery_charge ?? 0;
                $add_order['service_tax'] = $billing_balance['service_data']->tax;
                $add_order['service_commission'] = $billing_balance['service_data']->commission;
                $add_order['tax'] = $cart_avail->tax;
                $add_order['order_status'] = 3;
                $add_order['payment_type'] = request('payment');
                if ($add_order['payment_type'] == 3) {
                    $add_order['payment_status'] = 2;
                } else {
                    $add_order['payment_status'] = 1;
                }
                $make_order_id = $orders->makeOrder($add_order) ?? 0;

                // $order_id = base64_encode($make_order_id);
                $order_data = $orders->getOrderData($make_order_id);
                // cash on delivery
                if(in_array(request('payment') ,[3])){
                    $cart = new cart;
                    $order_statuss = "Order Confirmed";
                    $order_message = "Your order was successfully placed <br> and being prepared for delivery.";
                    // ============================================= PUSH NOTIFICATION=======================================
                     $sender_data = Auth::user();
                     if (isset($sender_data->device_token)) {
                         $push_notification_sender = array();
                         $push_notification_sender['device_token'] = $sender_data->device_token;
                         $push_notification_sender['title'] = 'Order Confirmed';
                         $push_notification_rider['page_token'] = $make_order_id;
                         $push_notification_sender['notification'] = 'Your order was successfully placed and being prepared for delivery.';
                         $push_notification_sender_result = $this->pushNotification($push_notification_sender);
                     }

                     $notification_sender = array();
                     $notification_sender['user_id'] = $sender_data->id;
                     $notification_sender['txn_id'] = $order_data->order_id;
                     $notification_sender['title'] = 'Order Confirmed';
                     $notification_sender['notification'] = 'Your order was successfully placed and being prepared for delivery.';
                     $notification = new Notification();
                     $notification_id = $notification->makeNotifiaction($notification_sender);
                    // ==========================================================================================================

                    $cart_delete = $cart->deleteCart($user->id);
                    return response()->json([
                        'order_id' => $make_order_id,
                        'message' => 'Order Confirmed !',
                        'status' => true
                    ], $this->successStatus);
                // Bank transfer
                }elseif(in_array(request('payment') ,[1])){
                    $cart = new cart;
                    // ============================================= PUSH NOTIFICATION=======================================
                     $sender_data = Auth::user();
                     if (isset($sender_data->device_token)) {
                         $push_notification_sender = array();
                         $push_notification_sender['device_token'] = $sender_data->device_token;
                         $push_notification_sender['title'] = 'Order Pending';
                         $push_notification_rider['page_token'] = $make_order_id;
                         $push_notification_sender['notification'] = 'Waiting For Restaurant Approval';
                         $push_notification_sender_result = $this->pushNotification($push_notification_sender);
                     }

                     $notification_sender = array();
                     $notification_sender['user_id'] = $sender_data->id;
                     $notification_sender['txn_id'] = $order_data->order_id;
                     $notification_sender['title'] = 'Order Pending';
                     $notification_sender['notification'] = 'Waiting For Restaurant Approval';
                     $notification = new Notification();
                     $notification_id = $notification->makeNotifiaction($notification_sender);
                    // ==========================================================================================================

                    $cart_delete = $cart->deleteCart($user->id);
                    return response()->json([
                        'order_id' => $make_order_id,
                        'message' => 'Order pending !',
                        'status' => true
                    ], $this->successStatus);
                }else{
                // ============================================= PUSH NOTIFICATION=======================================
                    $sender_data = Auth::user();
                    if (isset($sender_data->device_token)) {
                        $push_notification_sender = array();
                        $push_notification_sender['device_token'] = $sender_data->device_token;
                        $push_notification_sender['title'] = 'Order Failed';
                        $push_notification_rider['page_token'] = $make_order_id;
                        $push_notification_sender['notification'] = 'Failed';
                        $push_notification_sender_result = $this->pushNotification($push_notification_sender);
                    }

                    $notification_sender = array();
                    $notification_sender['user_id'] = $sender_data->id;
                    $notification_sender['txn_id'] = $order_data->order_id;
                    $notification_sender['title'] = 'Order Failed';
                    $notification_sender['notification'] = 'Failed';
                    $notification = new Notification();
                    $notification_id = $notification->makeNotifiaction($notification_sender);

                    return response()->json([
                        'order_id' => $make_order_id,
                        'message' => 'Order Failed !',
                        'status' => true
                    ], $this->successStatus);
                    $cart = new cart;
                    $cart_delete = $cart->deleteCart($user->id);

                // ==========================================================================================================

                }

            }else{
                return response()->json([
                    'message' => 'Cart Menu Empty !',
                    'status' => true
                ], $this->successStatus);
            }

        }













        $payment_methods = new payment_method();
        $payment_method_data = $payment_methods->getPaymentMethodList()->get();

        return response()->json([
            'payment_method_data' => $payment_method_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }
}
