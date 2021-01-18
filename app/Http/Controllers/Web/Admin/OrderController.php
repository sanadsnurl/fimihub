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
use App\Model\restaurent_detail;
use App\Model\Notification;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\payment_gateway_txn;
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

        if ($request->ajax()) {
            return Datatables::of($order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    if ($row->payment_type == 1 && $row->payment_status == 1) {
                        $btn .= '<a href="orderPaid?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Make Order Paid</a>';
                    }
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


                                foreach ($ordered_menu->add_on[0] as $add_data) {
                                    if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                        if ($loop_count == 1) {
                                            $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                        } else {
                                            $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                        }
                                        $loop_count_add += 1;
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            $order_menu .= ")";
                        } else {
                            $order_menu .= "/<br><b>Dish:".$loop_count." </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
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


                                foreach ($ordered_menu->add_on[0] as $add_data) {
                                    if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                        if ($loop_count == 1) {
                                            $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                        } else {
                                            $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                        }
                                        $loop_count_add += 1;
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
                ->rawColumns(['action','ordered_menu'])
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
        if ($order_data != NUll) {
            $order_menu = "";
            $loop_count = 1;
            $order_data->ordered_menu = json_decode($order_data->ordered_menu);

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


                        foreach ($ordered_menu->add_on[0] as $add_data) {
                            if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                } else {
                                    $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                }
                                $loop_count_add += 1;
                            }
                        }
                        $order_menu .= "] ";
                    }
                    $order_menu .= ")";
                } else {
                    $order_menu .= "/<br><b>Dish:".$loop_count." </b>(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
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


                        foreach ($ordered_menu->add_on[0] as $add_data) {
                            if (in_array($add_data->id, ($ordered_menu->product_adds_id) ?? [], FALSE)) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                } else {
                                    $order_menu .= "/(" . $add_data->cat_name . ' : ' . $add_data->name . ")";
                                }
                                $loop_count_add += 1;
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
            'txn_id' => 'required|string',

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
}
