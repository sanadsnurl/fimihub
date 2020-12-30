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

        $orders = new order;
        $order_data = $orders->allOrderPaginationData()
                ->with('userAddress.userDetails','restaurentDetails.restroAddress','cart.cartItems.menuItems','orderEvent.reason');

        if ($request->ajax()) {
            return Datatables::of($order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    // if ($row->payment_type == 1 && $row->payment_status == 1) {
                    //     $btn .= '<a href="orderPaid?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Make Order Paid</a>';
                    // }
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
                            $order_menu .= "(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                            if (isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL) {
                                $order_menu .= " [";
                                $loop_count_add = 1;


                                foreach ($ordered_menu->add_on_data as $add_data) {
                                    if ($add_data->quantity != 0) {
                                        if ($loop_count == 1) {
                                            $order_menu .= "(" . $add_data->name . " x " . $add_data->quantity . ")";
                                        } else {
                                            $order_menu .= "/(" . $add_data->name . " x " . $add_data->quantity . ")";
                                        }
                                        $loop_count_add += 1;
                                    }
                                }
                                $order_menu .= "] ";
                            }
                            $order_menu .= ")";
                        } else {
                            $order_menu .= "/(" . $ordered_menu->name . " x " . $ordered_menu->quantity;

                            $order_menu .= ")";
                        }
                        $loop_count += 1;
                    }
                    return $order_menu;
                })
                ->rawColumns(['action'])
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
        ->with('userAddress.userDetails','restaurentDetails.restroAddress','cart.cartItems.menuItems','orderEvent.reason');

        $user['currency'] = $this->currency;

        $order_data = $order_data->first();
        if ($order_data != NUll) {
            $order_menu = "";
            $loop_count = 1;
            $order_data->ordered_menu = json_decode($order_data->ordered_menu);

            foreach ($order_data->ordered_menu as $ordered_menu) {
                if ($loop_count == 1) {
                    $order_menu .= "(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                    if (isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->add_on_data as $add_data) {
                            if ($add_data->quantity != 0) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $add_data->name . " x " . $add_data->quantity . ")";
                                } else {
                                    $order_menu .= "/(" . $add_data->name . " x " . $add_data->quantity . ")";
                                }
                                $loop_count_add += 1;
                            }
                        }
                        $order_menu .= "] ";
                    }
                    $order_menu .= ")";
                } else {
                    $order_menu .= "/(" . $ordered_menu->name . " x " . $ordered_menu->quantity;
                    if (isset($ordered_menu->add_on_data) && $ordered_menu->add_on_data != NULL) {
                        $order_menu .= " [";
                        $loop_count_add = 1;


                        foreach ($ordered_menu->add_on_data as $add_data) {
                            if ($add_data->quantity != 0) {
                                if ($loop_count == 1) {
                                    $order_menu .= "(" . $add_data->name . " x " . $add_data->quantity . ")";
                                } else {
                                    $order_menu .= "/(" . $add_data->name . " x " . $add_data->quantity . ")";
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
        return view('admin.viewOrder')->with(['data' => $user,
                                            'order_data' => $order_data,
                                            'event_data' => $event_data,
                                            'add_datas' => $add_datas]);
    }
}
