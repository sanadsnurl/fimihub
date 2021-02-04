<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Http\Traits\GetBasicPageDataTraits;
use App\Model\MyEarning;
use App\Model\order;
use App\Model\restaurent_detail;
use Illuminate\Support\Facades\Auth;
use Response;
use Session;
use DataTables;

class EarningController extends Controller
{
    public function restoEarningTrack(Request $request)
    {
        $user = Auth::user();
        $restaurent_detail = new restaurent_detail();
        $MyEarnings = new MyEarning();
        $resto_user_id = base64_decode(request('resto_user_id'));



        $resto_data = $restaurent_detail->getRestoData($resto_user_id);
        if ($resto_data == NULL) {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData(0);
        } else {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData($resto_data->id)
                            ->whereIn('orders.order_status',[9,10]);
        }
        $resto_id = $resto_data->id ?? 0;
        $resto_order_data = $MyEarnings->getMyEarningOnOrderResto($resto_id);
        $resto_order_data_sum = $MyEarnings->getMyTotalEarningResto($resto_id);
        // dd($resto_order_data);

        if ($request->ajax()) {
            return DataTables::of($resto_order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    if ($row->order_status == 5) {
                        $btn .= '<a href="packedOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Ready For Pick-Up</a>';
                    } elseif ($row->order_status == 3) {
                        $btn .= '<a href="acceptOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Accept</a>
                        <a href="rejectOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Reject</a>';
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
                ->addColumn('order_earning', function ($row) {
                    $delivery_fee = $row->delivery_fee;
                    $total_amount = round(abs($row->total_amount - $delivery_fee),2);
                    $tax = $row->service_tax;
                    $sub_total = $total_amount / (1 + ($tax / 100));
                    $commission = $row->service_commission;
                    $total_earning = $sub_total / (1 + ($commission / 100));

                    return round($total_earning,2);
                })
                ->addColumn('total_tax', function ($row) {
                    $delivery_fee = $row->delivery_fee;
                    $total_amount = round(abs($row->total_amount - $delivery_fee),2);
                    $tax = $row->service_tax;
                    $sub_total = $total_amount / (1 + ($tax / 100));
                    $total_tax = round(abs($total_amount - $sub_total),2);

                    return $total_tax;
                })
                ->addColumn('total_commission', function ($row) {
                    $delivery_fee = $row->delivery_fee;
                    $total_amount = round(abs($row->total_amount - $delivery_fee),2);
                    $tax = $row->service_tax;
                    $sub_total = $total_amount / (1 + ($tax / 100));
                    $commission = $row->service_commission;
                    $total_commission = $sub_total - $sub_total / (1 + ($commission / 100));
                    return round($total_commission,2);
                })
                ->rawColumns(['action', 'ordered_menu'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        // dd($resto_order_data->get());

        return view('admin.restoEarnings')->with(['data' => $user,'resto_user_id'=>$resto_user_id,'total_earning'=>$resto_order_data_sum]);
    }

    public function riderEarningTrack(Request $request)
    {
        $user = Auth::user();
        $MyEarnings = new MyEarning();
        $rider_user_id = base64_decode(request('rider_user_id'));
        $rider_order_data = $MyEarnings->getMyEarningOnOrder($rider_user_id);
        $rider_order_data_sum = $MyEarnings->getMyTotalEarning($rider_user_id);

// dd($rider_order_data_sum);
        if ($request->ajax()) {
            return DataTables::of($rider_order_data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $btn .= '<a href="viewOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-warning btn-sm btn-round waves-effect waves-light ">View</a>';
                    if ($row->order_status == 5) {
                        $btn .= '<a href="packedOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Ready For Pick-Up</a>';
                    } elseif ($row->order_status == 3) {
                        $btn .= '<a href="acceptOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-dark btn-sm btn-round waves-effect waves-light m-0">Accept</a>
                        <a href="rejectOrder?odr_id=' . base64_encode($row->id) . '" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light m-0">Reject</a>';
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
                ->rawColumns(['action', 'ordered_menu'])
                ->make(true);
        }
        $user['currency'] = $this->currency;
        // dd($order_data->get());

        return view('admin.riderEarnings')->with(['data' => $user,'rider_user_id'=>$rider_user_id,'total_earning'=>$rider_order_data_sum]);
    }
}
