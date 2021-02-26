<?php

namespace App\Http\Controllers\Web\Restaurent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\NotificationTrait;
use App\Http\Traits\LatLongRadiusScopeTrait;
use App\Model\restaurent_detail;
use App\Model\Notification;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\Reason;
use App\Model\user_address;
use Response;
use Session;
use DataTables;


class OrderController extends Controller
{
    use NotificationTrait, LatLongRadiusScopeTrait;

    public function getCustomerOrderList(Request $request)
    {
        $user = Auth::user();

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NULL) {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData(0);
        } else {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData($resto_data->id);
        }


        if ($request->ajax()) {
            return Datatables::of($order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    if ($row->order_status == 5) {
                        $btn .= '<a href="packedOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Ready For Pick-Up</a>';
                    } elseif ($row->order_status == 3) {
                        $btn .= '<a href="acceptOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Accept</a>
                        <a href="rejectOrderPage?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Reject</a>';
                    }
                    $btn .= '<a href="deleteOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">Delete</a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('order_time', function ($row) {
                    return date('h:i A', strtotime($row->created_at));
                })
                ->addColumn('payment_type', function ($row) {
                    if ($row->payment_type == 1) {
                        return "Bank Transfer";
                    } elseif ($row->payment_type == 2) {
                        return "Paypal";
                    } elseif ($row->payment_type == 3) {
                        return "COD";
                    } elseif ($row->payment_type == 4) {
                        return "Credit/Debit Card";
                    } else {
                        return "N.A";
                    }
                })
                ->addColumn('order_status', function ($row) {

                    if ($row->order_status == 3) {
                        return "Restaurent Approval Needed";
                    } elseif ($row->order_status == 5) {
                        return "Order Placed";
                    } elseif (in_array($row->order_status, array(2, 4, 8))) {
                        return "Order Cancelled";
                    } elseif ($row->order_status == 6) {
                        return "Order Packed";
                    } elseif ($row->order_status == 7) {
                        return "Order Picked";
                    } elseif ($row->order_status == 9) {
                        return "Order Recieved";
                    } elseif ($row->order_status == 10) {
                        return "Order Refunded";
                    } else {
                        return "N.A";
                    }
                })
                ->addColumn('ordered_menu', function ($row) {
                    $order_menu = "";
                    $loop_count = 1;
                    $row->ordered_menu = json_decode($row->ordered_menu);

                    foreach ($row->ordered_menu as $ordered_menu) {
                        if ($loop_count == 1) {
                            $order_menu .= "<b>Dish:1 </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                            if (isset($ordered_menu->cart_variant_id) && $ordered_menu->cart_variant_id != NULL) {
                                $order_menu .= " [";
                                $loop_count_add = 1;


                                foreach ($ordered_menu->variant_data as $v_data) {
                                    if ($ordered_menu->cart_variant_id == $v_data->id) {
                                        if ($loop_count == 1) {
                                            $order_menu .= "(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                        } else {
                                            $order_menu .= "/(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                        }
                                        $loop_count_add += 1;
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            if (isset($ordered_menu->product_adds_id) && $ordered_menu->product_adds_id != NULL) {
                                $order_menu .= " [";
                                $loop_count_add = 1;


                                foreach ($ordered_menu->add_on as $add_datas) {
                                    foreach ($add_datas as $add_data) {

                                        if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                            if ($loop_count == 1) {
                                                $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                            } else {
                                                $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                            }
                                            $loop_count_add += 1;
                                        }
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            $order_menu .= ")";
                        } else {
                            $order_menu .= "/<br><b>Dish:" . $loop_count . " </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                            if (isset($ordered_menu->cart_variant_id) && $ordered_menu->cart_variant_id != NULL) {
                                $order_menu .= " [";
                                $loop_count_add = 1;


                                foreach ($ordered_menu->variant_data as $v_data) {
                                    if ($ordered_menu->cart_variant_id == $v_data->id) {
                                        if ($loop_count == 1) {
                                            $order_menu .= "(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                        } else {
                                            $order_menu .= "/(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                        }
                                        $loop_count_add += 1;
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            if (isset($ordered_menu->product_adds_id) && $ordered_menu->product_adds_id != NULL) {
                                $order_menu .= " [";
                                $loop_count_add = 1;


                                foreach ($ordered_menu->add_on as $add_datas) {
                                    foreach ($add_datas as $add_data) {
                                        if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                            if ($loop_count == 1) {
                                                $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                            } else {
                                                $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                            }
                                            $loop_count_add += 1;
                                        }
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            $order_menu .= ")";
                        }
                        $loop_count += 1;
                    }
                    return $order_menu;
                })
                ->rawColumns(['action', 'ordered_menu'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        $order_data = $order_data->get();
        // dd($order_data);
        return view('restaurent.customerOrder')->with(['data' => $user, 'order_data' => $order_data]);
    }

    public function acceptOrder(Request $request)
    {

        $user = Auth::user();

        $order_id = base64_decode(request('odr_id'));
        $order_status = 5;
        $orders = new order;
        $order_data = $orders->getOrderData($order_id);
        // dd($order_data);
        $order_status_update = $orders->updateOrderStatus($order_id, $order_status);

        $user_instanca = new User;
        $customer_data = $user_instanca->userByIdData($order_data->user_id);

        $order_event = array();
        $order_event['order_id'] = $order_id;
        $order_event['user_id'] = $user->id;
        $order_event['order_status'] = 1;
        $order_event['user_type'] = 2;
        $OrderEvents = new OrderEvent;
        $make_event = $OrderEvents->makeUpdateOrderEvent($order_event);

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($order_data->restaurent_id);
        if ($resto_data == NUll) {
            $resto_data = null;
        }
        $user_address = new user_address;
        $resto_add = $user_address->getUserAddress($resto_data->user_id);

        // ============================================= PUSH NOTIFICATION=======================================
        $push_notification_sender = array();
        $push_notification_sender['device_token'] = $customer_data->device_token;
        $push_notification_sender['title'] = 'Order Accepted';
        $push_notification_rider['page_token'] = 1;
        $push_notification_sender['notification'] = 'Order Accepted By Restaurent';

        $notification_sender = array();
        $notification_sender['user_id'] = $customer_data->id;
        $notification_sender['txn_id'] = $order_data->order_id;
        $notification_sender['title'] = 'Order Accepted';
        $notification_sender['notification'] = 'Order Accepted By Restaurant';
        $notification = new Notification();
        $notification_id = $notification->makeNotifiaction($notification_sender);
        $push_notification_sender_result = $this->pushNotification($push_notification_sender);

        // ================================== get rider by restaurant location ====================
        $lat = $resto_add[0]->latitude;
        $lng = $resto_add[0]->longitude;
        // $kmRadius = $this->max_distance_km_rider;
        $rider = $this->closestRiders($user, $lat, $lng)->get();
        $runner = $this->closestRunner($user, $lat, $lng)->get();

        foreach ($rider as $rid) {
            if (isset($rid->device_token)) {
                $push_notification_rider = array();
                $push_notification_rider['device_token'] = $rid->device_token;
                $push_notification_rider['title'] = 'New Order Request';
                $push_notification_rider['page_token'] = 1;
                $push_notification_rider['notification'] = 'New Order By ' . $customer_data->name;
                $push_notification_rider_result = $this->pushNotification($push_notification_rider);
            }
            $notification_rider = array();
            $notification_rider['user_id'] = $rid->id;
            $notification_rider['txn_id'] = $order_data->order_id;
            $notification_rider['title'] = 'New Order Request';
            $notification_rider['notification'] = 'New Order By ' . $customer_data->name;
            $notification = new Notification();
            $notification_id = $notification->makeNotifiaction($notification_rider);
        }
        foreach ($runner as $rid) {
            if (isset($rid->device_token)) {
                $push_notification_rider = array();
                $push_notification_rider['device_token'] = $rid->device_token;
                $push_notification_rider['title'] = 'New Order Request';
                $push_notification_rider['page_token'] = 1;
                $push_notification_rider['notification'] = 'New Order By ' . $customer_data->name;
                $push_notification_rider_result = $this->pushNotification($push_notification_rider);
            }
            $notification_rider = array();
            $notification_rider['user_id'] = $rid->id;
            $notification_rider['txn_id'] = $order_data->order_id;
            $notification_rider['title'] = 'New Order Request';
            $notification_rider['notification'] = 'New Order By ' . $customer_data->name;
            $notification = new Notification();
            $notification_id = $notification->makeNotifiaction($notification_rider);
        }
        // ==========================================================================================================

        return redirect()->back();
    }

    public function rejectOrder(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'odr_id' => 'required|string',
            'reason_id' => 'required|string',
            'reason_string' => 'string|max:150|nullable',

        ]);
        if($validator->fails()){
        	return redirect()->back()->withErrors($validator);

        }

        $user = Auth::user();

        $order_id = base64_decode(request('odr_id'));
        $reason_id = (request('reason_id'));
        if($reason_id == -1){
            $reason_id = (request('reason_string'));
        }
        $order_status = 4;
        $orders = new order;
        $order_status_update = $orders->updateOrderStatus($order_id, $order_status);
        $order_data = $orders->getOrderData($order_id);

        $user_instanca = new User;
        $customer_data = $user_instanca->userByIdData($order_data->user_id);

        $order_event = array();
        $order_event['order_id'] = $order_id;
        $order_event['user_id'] = $user->id;
        $order_event['order_status'] = 2;
        $order_event['user_type'] = 2;
        $order_event['reason_string'] = $reason_id;
        $OrderEvents = new OrderEvent;
        $make_event = $OrderEvents->makeUpdateOrderEvent($order_event);
        // ============================================= PUSH NOTIFICATION=======================================
        $push_notification_sender = array();
        $push_notification_sender['device_token'] = $customer_data->device_token;
        $push_notification_sender['title'] = 'Order Rejected';
        $push_notification_rider['page_token'] = 3;
        $push_notification_sender['notification'] = 'Order Rejected By Restaurant';

        $notification_sender = array();
        $notification_sender['user_id'] = $customer_data->id;
        $notification_sender['txn_id'] = $order_data->order_id;
        $notification_sender['title'] = 'Order Rejected';
        $notification_sender['notification'] = 'Order Rejected By Restaurant';
        $notification = new Notification();
        $notification_id = $notification->makeNotifiaction($notification_sender);
        $push_notification_sender_result = $this->pushNotification($push_notification_sender);

        // ==========================================================================================================

        return redirect('Restaurent/customerOrder');
    }

    public function packedOrder(Request $request)
    {
        $user = Auth::user();

        $order_id = base64_decode(request('odr_id'));
        $order_status = 6;
        $orders = new order;
        $order_data = $orders->getOrderData($order_id);
        $order_status_update = $orders->updateOrderStatus($order_id, $order_status);
        // dd($order_data);
        $user_instanca = new User;
        $customer_data = $user_instanca->userByIdData($order_data->user_id);

        $order_event = array();
        $order_event['order_id'] = $order_id;
        $order_event['user_id'] = $user->id;
        $order_event['order_status'] = 3;
        $order_event['user_type'] = 2;
        $OrderEvents = new OrderEvent;
        $make_event = $OrderEvents->makeUpdateOrderEvent($order_event);


        // ============================================= PUSH NOTIFICATION=======================================
        if (isset($customer_data->device_token)) {
            $push_notification_sender = array();
            $push_notification_sender['device_token'] = $customer_data->device_token;
            $push_notification_sender['title'] = 'Order Packed';
            $push_notification_rider['page_token'] = 2;
            $push_notification_sender['notification'] = 'Order Packed By Restaurant';
            $push_notification_sender_result = $this->pushNotification($push_notification_sender);
        }

        $notification_sender = array();
        $notification_sender['user_id'] = $customer_data->id;
        $notification_sender['txn_id'] = $order_data->order_id;
        $notification_sender['title'] = 'Order Packed';
        $notification_sender['notification'] = 'Order Packed By Restaurant';
        $notification = new Notification();
        $notification_id = $notification->makeNotifiaction($notification_sender);


        //============================= Rider PUSH =======================================
        $OrderEvents = new OrderEvent;
        $order_event_data = $OrderEvents->getOrderEvent($order_event);

        foreach ($order_event_data as $oe_data) {
            if ($oe_data->user_type == 1) {
                $user_instanca = new User;
                $rid = $user_instanca->userByIdData($oe_data->user_id);

                $push_notification_rider = array();
                $push_notification_rider['device_token'] = $rid->device_token;
                $push_notification_rider['title'] = 'Order Ready For Pick-Up';
                $push_notification_rider['page_token'] = 2;
                $push_notification_rider['notification'] = $customer_data->name . ' ,Order Has Been Packed';

                $notification_rider = array();
                $notification_rider['user_id'] = $rid->id;
                $notification_rider['txn_id'] = $order_data->order_id;
                $notification_rider['title'] = 'Order Ready For Pick-Up';
                $notification_rider['notification'] = $customer_data->name . ', Order Has Been Packed';
                $notification = new Notification();
                $notification_id = $notification->makeNotifiaction($notification_rider);
                $push_notification_rider_result = $this->pushNotification($push_notification_rider);
            }
        }

        // ==========================================================================================================

        return redirect()->back();
    }

    public function viewOrder(Request $request)
    {
        $user = Auth::user();
        $order_id = base64_decode(request('odr_id'));
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NULL) {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData(0);
        } else {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData($resto_data->id);
        }

        $user['currency'] = $this->currency;

        $order_data = $order_data->where('id', $order_id)->first();
        if ($order_data != NUll) {
            $order_menu = "";
            $loop_count = 1;
            $order_data->ordered_menu = json_decode($order_data->ordered_menu);
            $order_data->ordered_menu_added = $order_data->ordered_menu;

            foreach ($order_data->ordered_menu as $ordered_menu) {
                if ($loop_count == 1) {
                    $order_menu .= "<b>Dish:1 </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                    if (isset($ordered_menu->cart_variant_id) && $ordered_menu->cart_variant_id != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->variant_data as $v_data) {
                            if ($ordered_menu->cart_variant_id == $v_data->id) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                } else {
                                    $order_menu .= "/(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                }
                                $loop_count_add += 1;
                            }
                        }
                        $order_menu .= "] ";
                    }
                    if (isset($ordered_menu->product_adds_id) && $ordered_menu->product_adds_id != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->add_on as $add_datas) {
                            foreach ($add_datas as $add_data) {

                                if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                    if ($loop_count == 1) {
                                        $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                    } else {
                                        $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                    }
                                    $loop_count_add += 1;
                                }
                            }
                        }
                        $order_menu .= "] ";
                    }
                    $order_menu .= ")";
                } else {
                    $order_menu .= "/<br><b>Dish:" . $loop_count . " </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                    if (isset($ordered_menu->cart_variant_id) && $ordered_menu->cart_variant_id != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->variant_data as $v_data) {
                            if ($ordered_menu->cart_variant_id == $v_data->id) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                } else {
                                    $order_menu .= "/(" . $v_data->cat_name . ' : ' . $v_data->name . ")";
                                }
                                $loop_count_add += 1;
                            }
                        }
                        $order_menu .= "] ";
                    }
                    if (isset($ordered_menu->product_adds_id) && $ordered_menu->product_adds_id != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->add_on as $add_datas) {
                            foreach ($add_datas as $add_data) {
                                if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                    if ($loop_count == 1) {
                                        $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                    } else {
                                        $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                    }
                                    $loop_count_add += 1;
                                }
                            }
                        }
                        $order_menu .= "] ";
                    }
                    $order_menu .= ")";
                }
                $loop_count += 1;
            }
            $order_data->ordered_menu = $order_menu;

            if ($order_data->order_status == 3) {
                $order_data->order_status = "Restaurent Approval Needed";
            } elseif ($order_data->order_status == 5) {
                $order_data->order_status = "Order Placed";
            } elseif (in_array($order_data->order_status, array(2, 4, 8))) {
                $order_data->order_status = "Order Cancelled";
            } elseif ($order_data->order_status == 6) {
                $order_data->order_status = "Order Packed";
            } elseif ($order_data->order_status == 7) {
                $order_data->order_status = "Order Picked";
            } elseif ($order_data->order_status == 9) {
                $order_data->order_status = "Order Recieved";
            } elseif ($order_data->order_status == 10) {
                $order_data->order_status = "Order Refunded";
            } else {
                $order_data->order_status = "N.A";
            }

            if ($order_data->payment_type == 1) {
                $order_data->payment_type = "Bank Transfer";
            } elseif ($order_data->payment_type == 2) {
                $order_data->payment_type = "Paypal";
            } elseif ($order_data->payment_type == 3) {
                $order_data->payment_type = "COD";
            } elseif ($order_data->payment_type == 4) {
                $order_data->payment_type = "Credit/Debit Card";
            } else {
                $order_data->payment_type = "N.A";
            }

            $user_address = new user_address;
            $add_datas = $user_address->getAddressById($order_data->address_id);

            $OrderEvents = new OrderEvent;
            $order_event_data = $OrderEvents->getOrderEvent($order_id);
            $event_data = array();
            foreach ($order_event_data as $o_event) {
                if ($o_event->user_type == 2) {
                    $event_data['restaurant'] = $o_event;
                } elseif ($o_event->user_type == 1) {
                    $event_data['rider'] = $o_event;
                    $ride_event_data = auth()->user()->userByIdData($o_event->user_id);
                    $event_data['rider_details'] = $ride_event_data;
                }
            }
        }
        $event_data = json_encode($event_data);
        $event_data = json_decode($event_data);

        // dd($order_data->address_id);
        return view('restaurent.viewOrder')->with([
            'data' => $user,
            'order_data' => $order_data,
            'event_data' => $event_data,
            'add_datas' => $add_datas
        ]);
    }

    public function rejectOrderPage(Request $request)
    {
        $user = Auth::user();
        $order_id = base64_decode(request('odr_id'));
        $orders = new order;
        $order_data = $orders->getOrderData($order_id);
        $Reasons = new Reason();
        $order_event_data = $Reasons->getReasons(4)->get();
// dd($order_event_data);
        return view('restaurent.rejectReason')->with([
            'data' => $user,
            'order_data' => $order_data,
            'reason_list' => $order_event_data
        ]);
    }

    public function deleteCustomOrder(Request $request)
    {
        $user = Auth::user();
        $odr_id = base64_decode(request('odr_id'));

        $orders = new order();
        $delete_menu = array();
        $delete_menu['id'] = $odr_id;

        $delete_menu = $orders->deleteCustomerOrder($delete_menu);
        Session::flash('message', 'Order Deleted Successfully !');

        return redirect()->back();
    }
}
