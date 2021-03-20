<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\orderFeedBack;
use App\Http\Requests\user\SetPaymentMethod;
use App\Http\Requests\user\TrackOrderRequest;
use App\Http\Traits\BillingCalculateTraits;
use App\Http\Traits\LatLongRadiusScopeTrait;
use App\Http\Traits\NotificationTrait;
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
use App\Model\ServiceCategory;
use App\Model\user_address;
use Response;
use File;

use function GuzzleHttp\json_decode;

class OrderController extends Controller
{
    use LatLongRadiusScopeTrait, BillingCalculateTraits, NotificationTrait;
    public function getPaymentMethod(Request $request)
    {
        $user = Auth::user();

        $payment_methods = new payment_method();
        $payment_method_data = $payment_methods->getPaymentMethodList()->get();

        return response()->json([
            'payment_method_data' => $payment_method_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }

    public function setPaymentMethod(SetPaymentMethod $request)
    {
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
        } else {
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
                $restaurent_detail = new restaurent_detail;
                $resto_data = $restaurent_detail->getRestoDataOnId($cart_avail->restaurent_id);
                $resto_add_def = $user_address->getUserAddress($resto_data->user_id) ?? null;

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
                $delivery_distance = 0;
                if (!empty($user_add_def)) {
                    $delivery_distance = $this->getDistanceBetweenPointsNew(
                        $user_add_def->latitude,
                        $user_add_def->longitude,
                        $resto_add_def[0]->latitude,
                        $resto_add_def[0]->longitude
                    ) ?? -1;
                } else {
                    $delivery_charge = 0;
                }
                if ($delivery_distance == -1) {
                    return response()->json([
                        'message' => 'Invalid Address !',
                        'status' => false
                    ], $this->invalidStatus);
                } else {
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
                $add_order['address_id'] = request('address_id');
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
                if (in_array(request('payment'), [3])) {
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
                } elseif (in_array(request('payment'), [1])) {
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
                } else {
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
            } else {
                return response()->json([
                    'message' => 'Cart Menu Empty !',
                    'status' => true
                ], $this->successStatus);
            }
        }
    }

    public function trackOrder(TrackOrderRequest $request)
    {
        $user = Auth::user();

        $order_id = request('order_id');
        $orders = new order;
        $order_data = $orders->getOrderData($order_id);
        $menu_order = json_decode($order_data->ordered_menu);
        if ($menu_order == NULL) {
            return response()->json([
                'message' => 'Invalid Order\'s !',
                'status' => false
            ], $this->invalidStatus);
        }
        $menu_data = array();
        $menu_data_list = array();
        $add_on_data = array();

        $item = 0;
        $custom_count = 0;
        $custom_total = 0;
        $total_cart_value = 0;
        foreach ($menu_order as $m_data) {
            $menu_list = new menu_list;
            $menu_data_list = $menu_list->orderMenuListById($m_data->id);

            if ($menu_data_list != null) {

                $item = $item + $m_data->quantity;
                $menu_data_list->quantity = $m_data->quantity;
                $menu_data_list->price = $m_data->price;
                $total_cart_value = $total_cart_value + $m_data->price * $m_data->quantity;
                // dd($m_data->add_on);
                $add_on_data = array();
                if ($m_data->add_on) {
                    foreach ($m_data->add_on as $add_data) {

                        // $total_cart_value = $total_cart_value + $add_data->price * $add_data->quantity;
                        $add_on_data[] = $add_data;
                    }
                }

                $menu_data_list->add_on_data = $add_on_data;
                $menu_data[] = $menu_data_list;
            }
        }
        // dd($add_on_data);
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnIdNotDel($order_data->restaurent_id);

        $resto_data->delivery_fee = $order_data->delivery_fee;
        $cart = new cart;
        $cart_data = $cart->getCartData($order_data->id);

        $service_data = array();
        $service_data['tax'] = $order_data->service_tax;
        $service_data['commission'] = $order_data->service_commission;
        $service_data = json_encode($service_data);
        $service_data = json_decode($service_data);
        // $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax
        $service_tax = $order_data->total_amount;
        $service_data->service_tax = $service_tax;
        $sub_total = $total_cart_value;
        $user['currency'] = $this->currency;

        if ($order_data != NULL) {
            $OrderEvents = new OrderEvent;
            $order_event_data = $OrderEvents->getOrderEvent($order_id);
            $event_data = null;
            foreach ($order_event_data as $o_event) {
                if ($o_event->user_type == 2) {
                    $event_data['restaurant'] = $o_event;
                } elseif ($o_event->user_type == 1) {
                    $event_data['rider'] = $o_event;
                    $users = new User();
                    $ride_event_data = $users->userIdData($o_event->user_id)->with(['riderBankDetails','vehicleDetails'])->first();
                    $event_data['rider_details'] = $ride_event_data;
                    $order_events = new OrderEvent();
                    $rating_array = ['user_id'=> $event_data['rider_details']['id'],
                                    'user_type'=>1
                                ];
                    $rating_data = $order_events->getOrderEventRatingData($rating_array)->first();
                    $event_data['rider_rating_data'] = $rating_data;
                }
            }
            $total_amount = abs($order_data->total_amount - $order_data->delivery_fee);
            $ServiceCategories = new ServiceCategory();
            $service_data = $ServiceCategories->getServiceById(1);

            $sub_total = $total_amount / (1 + ($order_data->service_tax / 100));

            $service_tax = (($order_data->service_tax / 100) * $sub_total);
            $service_data->service_tax = round($service_tax, 2);

            $event_data = json_encode($event_data);
            $event_data = json_decode($event_data);
            $order_data->delivery_time = strtotime("+40 minutes", strtotime($order_data->created_at));
            $order_data->delivery_time = date('h:i', $order_data->delivery_time);
            // dd($order_data->delivery_time);
            // return ($order_data);

            $order_data->ordered_menu = json_decode($order_data->ordered_menu);
            if (count($order_data->ordered_menu)) {
                // return $order_data->ordered_menu;
                foreach ($order_data->ordered_menu as $m_data) {
                    // $m_data->variant_data_cat->variant_menu = array();
                    $variant_menu = array();
                    // return ( $m_data->variant_data_cat );
                    if (!empty($m_data->variant_data)) {
                        foreach ($m_data->variant_data as $variant_d) {
                            if ($m_data->variant_data_cat->cats_id == $variant_d->resto_custom_cat_id) {
                                $variant_menu[] = $variant_d;
                            }
                            $m_data->variant_data_cat->variant_menu = $variant_menu;
                        }
                    }
                    // $m_data->add_ons_cat = json_decode($m_data->add_ons_cat) ?? [];
                    // dd($m_data->add_ons_cat);
                    if (count($m_data->add_ons_cat)) {
                        $add_on_menu_cat = array();
                        foreach ($m_data->add_ons_cat as $add_cat_loop_data) {
                            if ($add_cat_loop_data != NULL) {
                                $add_cat_loop_data->add_on_menu = array();
                                $add_on_menu = array();
                                // $add_on_menu_cat[] = $add_cat_loop_data;
                                // return ($add_cat_loop_data);
                                if (count($m_data->add_on)) {
                                    foreach ($m_data->add_on as $add_loop_data) {
                                        if (count($add_loop_data)) {
                                            foreach ($add_loop_data as $add_loop_data_m) {
                                                // return ($add_loop_data_m);
                                                if ($add_cat_loop_data->cats_id == $add_loop_data_m->resto_custom_cat_id) {
                                                    $add_on_menu[] = $add_loop_data_m;
                                                }
                                            }
                                            $add_cat_loop_data->add_on_menu = $add_on_menu;
                                        }
                                    }
                                    $add_on_menu_cat[] = $add_cat_loop_data;
                                }
                            }
                            $m_data->add_ons_cat = $add_on_menu_cat;
                        }
                    }

                    unset($m_data->variant_data);
                    unset($m_data->add_on);
                    unset($m_data->sub_menu_id);
                    unset($m_data->listing_order);
                    unset($m_data->cart_variant_id);
                    unset($m_data->product_adds_id);
                    unset($m_data->restaurent_id);
                    unset($m_data->product_variant_id);
                    unset($m_data->product_add_on_id);
                    unset($m_data->product_adds_id);
                    unset($m_data->menu_category_id);
                    unset($m_data->cart_variant_id);
                    // if (!isset($m_data->product_adds_id)) {
                    //     $m_data->product_adds_id = [];
                    // }
                    if (!isset($m_data->quantity)) {
                        $m_data->quantity = 0;
                    } else {
                        $m_data->quantity = (int)$m_data->quantity;
                    }
                    // if (!isset($m_data->cart_variant_id)) {
                    //     $m_data->cart_variant_id = 0;
                    // }
                }
            }
            // dd($order_data);
            return response()->json([
                'order_data' => $order_data,
                'resto_data' => $resto_data,
                'total_amount_last' => $order_data->total_amount,
                'order_event_data' => $event_data,
                'service_data' => $service_data,
                'total_amount' => round($sub_total, 2),
                'item' => $item,
                'message' => 'Success',
                'status' => true
            ], $this->successStatus);
        } else {
            return response()->json([
                'message' => 'Order Details Found !',
                'status' => false
            ], $this->invalidStatus);
        }
    }

    public function orderFeedback(orderFeedBack $request)
    {
        $data = $request->toArray();
        $rider_feedback = array();
        $rider_feedback['order_feedback'] = $data['rider_rating'];
        $rider_feedback['id'] = $data['rider_event_id'];
        $OrderEvents = new OrderEvent;
        $order_event_data_resto = $OrderEvents->updateOrderEvent($rider_feedback);

        $resto_feedback = array();
        $resto_feedback['order_feedback'] = $data['restaurant_rating'];
        $resto_feedback['feedback_comment'] = $data['resto_feedback'];
        $resto_feedback['id'] = $data['resto_event_id'];
        $order_event_data_rider = $OrderEvents->updateOrderEvent($resto_feedback);

        return response()->json([
            'message' => 'Feedback Submitted !',
            'status' => true
        ], $this->successStatus);
    }
}
