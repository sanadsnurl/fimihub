<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\BillingCalculateTraits;
use App\Http\Traits\GetBasicPageDataTraits;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\cart;
use App\Model\restaurent_detail;
use App\Model\user_address;
use App\Model\cart_submenu;
use App\Model\menu_list;
use App\Model\order;
use App\Model\Notification;
use App\Model\ServiceCategory;
use App\Model\OrderEvent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\PaypalIntegrationTraits;
use App\Http\Traits\FirstAtlanticIntegrationTraits;
use App\Model\cart_customization;
use App\Model\menu_custom_list;
use App\Model\menu_customization;
use App\Model\payment_gateway_txn;
use App\Model\payment_method;
use App\Model\saved_card;
use Illuminate\Support\Facades\DB;
use Response;
use Session;

use function GuzzleHttp\json_decode;

class OrderController extends Controller
{
    use NotificationTrait,
        GetBasicPageDataTraits,
        PaypalIntegrationTraits,
        BillingCalculateTraits,
        FirstAtlanticIntegrationTraits;

    public function getPaymentPage(Request $request)
    {
        $user = Auth::user();
        $user = $this->getBasicCount($user);

        $user_data = auth()->user()->userByIdData($user->id);
        $user_address = new user_address();
        $user_default_add = $user_address->getDefaultAddress($user->id);

        $payment_methods = new payment_method();
        $payment_method_data = $payment_methods->getPaymentMethodList($user->id)->get();

// dd($payment_method_data);
        if ($user_default_add != NULL) {

            $user_address = new user_address();
            $user_add = $user_address->getUserAddress($user->id);

            $cart = new cart;
            $cart_avail = $cart->checkCartAvaibility($user->id);


            if ($cart_avail == NULL) {
                return view('customer.cart')->with([
                    'user_data' => $user,
                    'user_address' => $user_add
                ]);
            } else {

                $upadte_cart = array();
                $upadte_cart['id'] = $cart_avail->id;
                $upadte_cart['address_id'] = $user_default_add->id;
                $update_cart_add = $cart->updateCart($upadte_cart);
                $restaurent_detail = new restaurent_detail;
                $resto_data = $restaurent_detail->getRestoDataOnId($cart_avail->restaurent_id);
                $user_add_def = $user_address->getDefaultAddress($user->id) ?? '';
                $resto_add_def = $user_address->getUserAddress($resto_data->user_id) ?? '';
                $cart_submenu = new cart_submenu;
                $quant_details = array();
                $quant_details['user_id'] = $user->id;
                $quant_details['cart_id'] = $cart_avail->id;
                $quant_details['restaurent_id'] = $cart_avail->restaurent_id;
                $cart_menu_data = $cart_submenu->getCartMenuList($quant_details);

                if ($cart_menu_data != NULL) {
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
                    $saved_cards = new saved_card();
                    $card_data = $saved_cards->getUserCardList($user->id);
                    // return $billing_balance;
                    $user->currency = $this->currency;
                    return view('customer.cartPayment')->with([
                        'user_data' => $user,
                        'payment_method_data' => $payment_method_data,
                        'menu_data' => $cart_menu_data,
                        'card_data' => $card_data,
                        'user_add_def' => $user_add_def,
                        'resto_add_def' => $resto_add_def,
                        'total_amount' => $billing_balance['total_amount'],
                        'total_amount_last' => $billing_balance['total_amount_last'],
                        'item' => $billing_balance['item'],
                        'service_data' => $billing_balance['service_data'],
                        'sub_total' => $billing_balance['sub_total'],
                        'resto_data' => $resto_data,
                        'user_address' => $user_add
                    ]);
                } else {
                    return view('customer.cart')->with([
                        'user_data' => $user,
                        'user_address' => $user_add
                    ]);;
                }
            }
        } else {
            Session::flash('message', 'Please Select Any Address');
            return redirect()->back();
        }
    }

    public function addPaymentType(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'payment' => 'required|in:1,2,3,4',
            'delivery_fee' => 'required|not_in:0',
            'cvv' => 'required_if:payment,4|digits:3|nullable',
            'card_expiry_date' => 'required_if:payment,4|nullable',
            'card_number' => 'required_if:payment,4|nullable',
            'person_name' => 'required_if:payment,4|string|nullable',
            'remember_card' => 'integer|nullable'

        ], [
            'delivery_fee.required' => 'Invalid Address',
            'delivery_fee.not_in:0' => 'Invalid Address'
        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $data = $request->toarray();
            $cart = new cart;
            $cart_avail = $cart->checkCartAvaibility($user->id);
            $user_address = new user_address();
            $user_default_add = $user_address->getDefaultAddress($user->id);
            if ($cart_avail == NULL) {
                return view('customer.cart')->with([
                    'user_data' => $user,
                    'user_address' => $user_default_add
                ]);
            }
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
                //     Session::flash('message', 'Restaurant Currently Closed !');
                //     return redirect()->back();
                // }
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
                // dd($cart_menu_data->toArray());

                $billing_data_arary = [
                    'menu_id' => false,
                    'order_id' => false,
                    'user_id' => $user->id,
                    'resto_id' => $quant_details['restaurent_id']
                ];
                $billing_balance = ($this->getBilling($billing_data_arary));
                $user['currency'] = $this->currency;

                $orders = new order;
                $add_order = array();
                $add_order['user_id'] = $user->id;
                $add_order['restaurent_id'] = $cart_avail->restaurent_id;
                $add_order['cart_id'] = $cart_avail->id;
                $add_order['address_id'] = $cart_avail->address_id;
                $add_order['customer_name'] =  $user->name;
                $add_order['ordered_menu'] = json_encode($cart_menu_data);
                $add_order['mobile'] =  $user->mobile;
                $add_order['total_amount'] = $billing_balance['total_amount_last'] + request('delivery_fee') ?? 0;
                $add_order['delivery_fee'] = request('delivery_fee') ?? 0;
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
                // dd("dd");
                $make_order_id = $orders->makeOrder($add_order);

                // ============================================= PUSH NOTIFICATION=======================================
                $sender_data = Auth::user();
                $push_notification_sender = array();
                $push_notification_sender['device_token'] = $sender_data->device_token;
                $push_notification_sender['title'] = 'Order Pending';
                $push_notification_sender['notification'] = 'Waiting For Restaurant Approval';

                $push_notification_sender_result = $this->pushNotification($push_notification_sender);

                // ==========================================================================================================

                $order_id = base64_encode($make_order_id);
                $order_data = $orders->getOrderData($make_order_id);
                // dd($order_data);
                // Paypal Payload
                if (request('payment') == 2) {
                    $make_payment_array = [
                        'business' => Config('PAYPAL_BUSINESS_EMAIL'),
                        'item_name' => 'food',
                        'item_number' => 1,
                        '_token' => request('_token'),
                        'amount' => $billing_balance['total_amount_last']  + request('delivery_fee') ?? 0,
                        'no_shipping' => 1,
                        'currency_code' => 'USD',
                        'notify_url' => '',
                        'cancel_return' => url('makePaypalOrder') . '?order_check=' . base64_encode('netset') . '&order_check_token=' . base64_encode($make_order_id),
                        'return' => url('makePaypalOrder') . '?order_check=' . base64_encode('netsetwork') . '&order_check_token=' . base64_encode($make_order_id),
                        'cmd' => '_xclick'
                    ];
                    $payment = $this->makePayment($make_payment_array);
                    return  redirect($payment);
                }
                // First Atlantic Payload
                if (request('payment') == 4) {
                    $make_payment_array = [
                        'order_id' => $order_data->order_id,
                        'order_unique_id' => $make_order_id,
                        '_token' => request('_token'),
                        'amount' => $billing_balance['total_amount_last']  + request('delivery_fee') ?? 0,
                        'card_ccv' => $data['cvv'],
                        'card_expiry_date' => $data['card_expiry_date'],
                        'card_number' => $data['card_number'],
                        'issue_number' => '',
                        'start_date' => '',
                    ];
                    // save card details
                    if ($request->has('remember_card') && request('remember_card')==1) {
                        $saved_cards = new saved_card();
                        $card_array  =  ['card_number' => base64_encode($data['card_number']),
                                        'card_expiry_date' => $data['card_expiry_date'],
                                        'person_name' => $data['person_name'],
                                        'user_id' => $user->id];

                        $save_insert = $saved_cards->makeSaveCards($card_array);
                    }
                    $payment = $this->makeFirstAtlanticPayment($make_payment_array);
                    $payment = (json_decode(json_encode($payment)));
                    $payment_auth_result = $payment->AuthorizeResult;
                    $payment_result = $payment_auth_result->CreditCardTransactionResults;
                    $order_number_bank = $payment_auth_result->OrderNumber;
                    $response_code = $payment_result->ReasonCode;
                    $txn_array = [
                        'txn_id' => $order_number_bank,
                        'user_id' => $user->id,
                        'order_id' => $make_order_id,
                        'txn_type' => 1,
                        'amount' => $billing_balance['total_amount_last']  + request('delivery_fee') ?? 0,
                        'status' => 1,
                        'payment_type' => 4,
                        'bank_response' => json_encode($payment)
                    ];
                    // Start transaction
                    DB::beginTransaction();
                    if ($response_code == 1) {
                        // Success
                        $cart_delete = $cart->deleteCart($user->id);
                        $orders = new order();
                        $payment_status = 2;
                        $order_status_update = $orders->updatePaymentStatus($make_order_id, $payment_status);
                        $payment_gateway_txns = new payment_gateway_txn();
                        $txn_done = $payment_gateway_txns->insertUpdateTxn($txn_array);
                    } else {
                        // Failed
                        $orders = new order();
                        $payment_status = 3;
                        $order_status_update = $orders->updatePaymentStatus($make_order_id, $payment_status);
                        $txn_array['status'] = 2;
                        $payment_gateway_txns = new payment_gateway_txn();
                        $txn_done = $payment_gateway_txns->insertUpdateTxn($txn_array);
                    }
                    if ($order_status_update && $txn_done) {
                        //Commit
                        DB::commit();
                        $order_statuss = "Order Confirmed";
                        $order_message = "Your order was successfully placed <br> and being prepared for delivery.";
                    } else {
                        //rollback
                        DB::rollBack();
                        $order_statuss = "Order Failed";
                        $order_message = "Your order was Failed ,<br> tansaction declined.";
                        Session::flash('modal_message', 'Payment failed !');

                        Session::flash('modal_check_subscribe', 'open');
                        return redirect()->back();
                    }
                }
                if(in_array(request('payment') ,[3])){
                    $cart = new cart;
                    $order_statuss = "Order Confirmed";
                    $order_message = "Your order was successfully placed <br> and being prepared for delivery.";
                    $cart_delete = $cart->deleteCart($user->id);
                }elseif(in_array(request('payment') ,[1])){
                    $cart = new cart;
                    $order_statuss = "Order Pending";
                    $order_message = "Your order was pending <br> admin approval needed.";
                    $cart_delete = $cart->deleteCart($user->id);
                }
                Session::flash('modal_check_order', 'open');
                Session::flash('order_statuss', $order_statuss ?? '');
                Session::flash('order_message', $order_message ?? '');
                Session::flash('order_id', $order_id);
                return redirect('/myOrder');
            } else {
            }
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function trackOrder(Request $request)
    {
        $user = Auth::user();
        $user = $this->getBasicCount($user);

        $order_id = base64_decode(request('odr_id'));
        $orders = new order;
        $order_data = $orders->getOrderData($order_id);
        $menu_order = json_decode($order_data->ordered_menu);
        if ($menu_order == NULL) {
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
            $event_data = array();
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
            $service_data->service_tax = $service_tax;

            $event_data = json_encode($event_data);
            $event_data = json_decode($event_data);
            $order_data->delivery_time = strtotime("+40 minutes", strtotime($order_data->created_at));
            $order_data->delivery_time = date('h:i', $order_data->delivery_time);
            // dd($event_data);
// dd($event_data->rider->vehicleDetails->color);
            return view('customer.trackOrder')->with([
                'user_data' => $user,
                'order_data' => $order_data,
                'add_on_data' => ($add_on_data),
                'menu_data' => json_decode($order_data->ordered_menu),
                'resto_data' => $resto_data,
                'total_amount_last' => $order_data->total_amount,
                'order_event_data' => $event_data,
                'sub_total' => $sub_total,
                'service_data' => $service_data,
                'total_amount' => $sub_total,
                'item' => $item
            ]);
        } else {
            Session::flash('message', 'Order Details Found !');
            return redirect()->back();
        }
    }

    public function postFeedback(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'restaurant_rating' => 'required|in:1,2,3,4,5',
            'rider_rating' => 'required|in:1,2,3,4,5',
            'resto_feedback' => 'required|string',

        ]);
        if (!$validator->fails()) {
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

            return redirect()->back();
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function changePaypalOrderStatus(Request $request)
    {
        $order_check = base64_decode(request('order_check'));
        $order_check_token = base64_decode(request('order_check_token'));
        $orders = new order;
        $order_data = $orders->getOrderData($order_check_token);
        if ($order_check == 'netsetwork') {
            $payment_status = 2;
            // dd($order_check);
            $order_statuss = "Order Confirmed";
            $order_message = "Your order was successfully placed <br> and being prepared for delivery.";
            $cart = new cart;
            $cart_delete = $cart->deleteCart($order_data->user_id);

            $order_status_update = $orders->updatePaymentStatus($order_check_token, $payment_status);
            Session::flash('order_statuss', $order_statuss ?? '');
            Session::flash('order_message', $order_message ?? '');
            Session::flash('modal_check_order', 'open');
            Session::flash('order_id', request('order_check_token'));
        } else {
            $payment_status = 3;
            $order_status = 1;
            $order_statuss = "Order Failed";
            $order_message = "Your order was Failed ,<br> tansaction declined.";
            $order_status_update = $orders->updatePaymentStatus($order_check_token, $payment_status);
            $order_status_updates = $orders->updateOrderStatus($order_check_token, $order_status);
            Session::flash('order_statuss', $order_statuss ?? '');
            Session::flash('order_message', $order_message ?? '');
            Session::flash('modal_check_order', 'open');
            Session::flash('order_id', request('order_check_token'));
        }

        return redirect('/myOrder');
    }

    public function firstAtlanticSaveResult(Request $request)
    {
        $data = $request->toArray();
        // First Atlantic Payload
        if (isset($data['ReasonCode'])) {
            $payment_reponse = json_encode($data);
            $order_number = $data['OrderID'];
            $customer_order = explode('-', $order_number);
            $make_order_id = $customer_order[0];
            $orders = new order();
            $order_data = $orders->getOrderData($make_order_id);
            $order_number_bank = $data['ReferenceNo'] ?? '';
            $response_code = $data['ReasonCode'] ?? '';

            $user = new User();
            $user_data = $user->userData($order_data->user_id);
            $user = Auth::loginUsingId($order_data->user_id);
            // auth()->attempt(['mobile'=>$user_data->mobile]);
            Session::put('user', $user);
            $txn_array = [
                'txn_id' => $order_number,
                'user_id' => $order_data->user_id,
                'order_id' => $make_order_id,
                'txn_type' => 1,
                'amount' => $order_data->total_amount ?? 0,
                'status' => 1,
                'payment_type' => 4,
                'bank_response' => $payment_reponse
            ];
            // Start transaction
            DB::beginTransaction();
            if ($response_code == 1) {
                // Success
                $order_statuss = "Order Confirmed";
                $order_message = "Your order was successfully placed <br> and being prepared for delivery.";
                $cart = new cart;
                $cart_delete = $cart->deleteCart($user->id);
                $orders = new order();
                $payment_status = 2;
                $order_status_update = $orders->updatePaymentStatus($make_order_id, $payment_status);
                $payment_gateway_txns = new payment_gateway_txn();
                $txn_done = $payment_gateway_txns->insertUpdateTxn($txn_array);
            } else {
                // Failed
                $order_statuss = "Order Failed";
                $order_message = "Your order was Failed ,<br> tansaction declined.";
                $orders = new order();
                $payment_status = 3;
                $order_status_update = $orders->updatePaymentStatus($make_order_id, $payment_status);
                $txn_array['status'] = 2;
                $payment_gateway_txns = new payment_gateway_txn();
                $txn_done = $payment_gateway_txns->insertUpdateTxn($txn_array);
            }
            if ($order_status_update && $txn_done) {
                //Commit
                DB::commit();
            } else {
                //rollback

                DB::rollBack();
                Session::flash('modal_message', 'Payment failed !');

                Session::flash('modal_check_subscribe', 'open');
                return redirect()->back();
            }
            Session::flash('modal_check_order', 'open');
            Session::flash('order_statuss', $order_statuss ?? '');
            Session::flash('order_message', $order_message ?? '');
            Session::flash('order_id', base64_encode($make_order_id));
            return redirect('/myOrder');
        } else {
            return redirect('accessDenied');
        }
    }
}
