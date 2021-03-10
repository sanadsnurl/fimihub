<?php

namespace App\Http\Controllers\Web\Admin;

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
use App\Model\cart;
use App\Model\menu_list;
use App\Model\restaurent_detail;
use App\Model\Notification;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\payment_gateway_txn;
use App\Model\ServiceCategory;
use App\Model\user_address;
use Response;
use Session;
use DataTables;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use NotificationTrait, LatLongRadiusScopeTrait;

    public function getCustomerOrderList(Request $request)
    {
        $user = Auth::user();

        $orders = new order;
        $order_data = $orders->allOrderPaginationData()
            ->with('userAddress.userDetails', 'restaurentDetails.restroAddress', 'cart.cartItems.menuItems', 'orderEvent.reason');
// dd($order_data->get());
        if ($request->ajax()) {
            return Datatables::of($order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    if ($row->payment_type == 1 && $row->payment_status == 1) {
                        $btn .= '<a href="orderPaid?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Make Order Paid</a>';
                    }
                    $btn .= '<a href="deleteOrder?delete_status=' . base64_encode(1) . '&odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-primary btn-sm btn-round waves-effect waves-light ">Delete</a>';
                    return $btn;
                })
                ->addColumn('created_at', function ($row) {
                    return date('d F Y', strtotime($row->created_at));
                })
                ->addColumn('payment_type', function ($row) {
                    if ($row->payment_type == 1) {
                        return "Bank Transfer";
                    } elseif ($row->payment_type == 2) {
                        return "Paypal";
                    } elseif ($row->payment_type == 3) {
                        return "COD";
                    } elseif ($row->payment_type == 4) {
                        return  "Credit/Debit Card";
                    } else {
                        return "N.A";
                    }
                })
                ->addColumn('order_status', function ($row) {

                    if ($row->order_status == 3) {
                        $orderStatus = "Restaurent Approval Needed";
                    } elseif ($row->order_status == 5) {
                        $orderStatus = "Order Placed";
                    } elseif (in_array($row->order_status, array(2, 4, 8))) {
                        $orderStatus = "Order Cancelled";
                    } elseif ($row->order_status == 6) {
                        $orderStatus = "Order Packed";
                    } elseif ($row->order_status == 7) {
                        $orderStatus = "Order Picked";
                    } elseif ($row->order_status == 9) {
                        $orderStatus = "Order Recieved";
                    } elseif ($row->order_status == 10) {
                        $orderStatus = "Order Refunded";
                    } else {
                        $orderStatus = "N.A";
                    }
                    $btn = $orderStatus.'<p><a href="trackOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-primary btn-sm btn-round waves-effect waves-light ">Track Order</a></p>';
                    return $btn;

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
                ->rawColumns(['action', 'ordered_menu', 'order_status'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        $order_data = $order_data->get();
        // dd($order_data);
        return view('admin.customerOrder')->with(['data' => $user, 'order_data' => $order_data]);
    }


    public function viewOrder(Request $request)
    {
        $user = Auth::user();
        $order_id = base64_decode(request('odr_id'));
        $orders = new order;
        $order_data = $orders->allOrderPaginationDataById($order_id)
            ->with('userAddress.userDetails', 'restaurentDetails.restroAddress', 'cart.cartItems.menuItems', 'orderEvent.reason');

        $user['currency'] = $this->currency;

        $order_data = $order_data->first();
        // dd($order_id);
        if ($order_data != NUll) {
            $order_menu = "";
            $loop_count = 1;
            $order_data->ordered_menu = json_decode($order_data->ordered_menu);
            // dd($order_data->ordered_menu);

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
                $order_data->payment_type =  "Credit/Debit Card";
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

                    if ($o_event->order_status == 1) {
                        $o_event->order_status = "Arriving to store";
                    } elseif ($o_event->order_status == 2) {
                        $o_event->order_status = "Arrived at store";
                    } elseif ($o_event->order_status == 3) {
                        $o_event->order_status = "Order Picked Up";
                    } elseif ($o_event->order_status == 4) {
                        $o_event->order_status = "On the way";
                    } elseif ($o_event->order_status == 5) {
                        $o_event->order_status = "Order Delivered";
                    } elseif ($o_event->order_status == 6) {
                        $o_event->order_status = "Order Rejected";
                    } else {
                        $o_event->order_status = "N.A";
                    }

                    $event_data['rider'] = $o_event;
                    $ride_event_data = auth()->user()->userByIdData($o_event->user_id);
                    $event_data['rider_details'] = $ride_event_data;
                }
            }
        }
        $event_data = json_encode($event_data);
        $event_data = json_decode($event_data);

        // dd($order_data->toArray());
        // dd($order_data->restaurentDetails->name);
        // $order_data->restaurent_details->name
        return view('admin.viewOrder')->with([
            'data' => $user,
            'order_data' => $order_data,
            'event_data' => $event_data,
            'add_datas' => $add_datas
        ]);
    }

    public function viewOrderPaid(Request $request)
    {
        $user = Auth::user();
        $order_id = base64_decode(request('odr_id'));
        $orders = new order;
        $order_data = $orders->allOrderPaginationDataById($order_id)->first();
        // dd($order_data->first()->toArray());
        return view('admin.changeOrderPaidStatus')->with([
            'data' => $user,
            'order_data' => $order_data
        ]);
    }

    public function orderPaidProcess(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'txn_id' => 'required|string|unique:payment_gateway_txns,txn_id',

        ]);
        if (!$validator->fails()) {
            $user = Auth::user();
            $order_id = base64_decode(request('odr_id'));
            $data = $request->toarray();
            $txn_array = [
                'txn_id' => $data['txn_id'],
                'user_id' => $data['user_id'],
                'txn_type' => 1,
                'amount' => $data['total_amount'],
                'status' => 1,
                'payment_type' => 1,
                'order_id' => $data['id']
            ];
            // Start transaction
            DB::beginTransaction();
            // Run Queries
            $orders = new order();
            $payment_status = 2;
            $order_status_update = $orders->updatePaymentStatus($data['id'], $payment_status);
            $payment_gateway_txns = new payment_gateway_txn();
            $txn_done = $payment_gateway_txns->insertUpdateTxn($txn_array);
            if ($order_status_update && $txn_done) {
                Session::flash('message', 'Paid Status Updated !');
                //Commit
                DB::commit();
                return redirect('adminfimihub/customerOrder');
            } else {
                Session::flash('message', 'Something Went Wrong !');
                //rollback
                DB::rollBack();
                return redirect()->back()->withInput();
            }
        } else {
            return redirect()->back()->withInput()->withErrors($validator);
        }
    }

    public function deleteCustomOrder(Request $request)
    {
        $user = Auth::user();
        $delete_status = base64_decode(request('delete_status'));
        $delete_url = $request->fullUrl();
        $delete_url = str_replace('delete_status=MQ%3D%3D','delete_status=Mg%3D%3D',  $delete_url);

        if($delete_status == 1){
            Session::flash('popup_delete', $delete_url);

            return redirect()->back();
        }
        $odr_id = base64_decode(request('odr_id'));
        $orders = new order();
        $delete_menu = array();
        $delete_menu['id'] = $odr_id;

        $delete_menu = $orders->deleteCustomerOrder($delete_menu);
        Session::flash('message', 'Order Deleted Successfully !');

        return redirect()->back();
    }



    public function trackOrder(Request $request)
    {
        $user = Auth::user();
        // $user = $this->getBasicCount($user);

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
                    $ride_event_data = auth()->user()->userByIdData($o_event->user_id);
                    $event_data['rider_details'] = $ride_event_data;
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
            // dd($order_data->delivery_time);

            return view('admin.trackOrder')->with([
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
                'item' => $item,
                'data' => $user
            ]);
        } else {
            Session::flash('message', 'Order Details Found !');
            return redirect()->back();
        }
    }
}
