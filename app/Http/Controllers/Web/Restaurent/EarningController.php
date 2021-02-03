<?php

namespace App\Http\Controllers\Web\Restaurent;

use App\Http\Controllers\Controller;
//custom import
use App\User;
use App\Http\Traits\GetBasicPageDataTraits;
use App\Model\order;
use App\Model\restaurent_detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;
use Session;
use DataTables;

class EarningController extends Controller
{
    use GetBasicPageDataTraits;
    public function earningTrack(Request $request)
    {
        $user = Auth::user();
        $restaurent_detail = new restaurent_detail();
        $resto_data = $restaurent_detail->getRestoData($user->id);
        if ($resto_data == NULL) {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData(0);
        } else {
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData($resto_data->id)
                            ->whereIn('orders.order_status',[9,10]);
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

                    return $total_earning;
                })
                ->rawColumns(['action', 'ordered_menu'])
                ->make(true);
        }
        $user['currency'] = $this->currency;

        return view('restaurent.myEarnings')->with(['data' => $user]);
    }
}
