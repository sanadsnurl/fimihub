<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\restaurent_detail;
use App\Model\order;
use App\Model\OrderEvent;
use App\Model\menu_list;
use App\Model\Cms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;
use Datatables;

class DashboardController extends Controller
{

    public function dashboardDetails()
    {
        $user = Auth::user();
        $user_instance = new User;
        $user_count = $user_instance->allUserList(3)->count();
        $merchant_count = $user_instance->allUserList(4)->count();
        $rider_count = $user_instance->allUserList(2)->count();
        $orders = new order;
        $order_data = $orders->allOrderPaginationData();
        $user['currency']=$this->currency;
        $user['user_count']=$user_count;
        $user['merchant_count']=$merchant_count;
        $user['rider_count']=$rider_count;
        $user['order_count']=$order_data->count();
        //dd($user);
        return view('admin.indexDashboard')->with(['data'=>$user]);

    }

    public function getFaqPage(Request $request){
        $user=Auth::user();

        $cmsObj = new Cms;
        $faq_data = $cmsObj->getCms(3);

        if ($request->ajax()) {
            return Datatables::of($faq_data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="editResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-secondary btn-sm btn-round waves-effect waves-light m-0">Edit</a>
                    <a href="deleteResto?resto_user_id='.base64_encode($row->resto_user_id).'" class="btn btn-outline-danger btn-sm btn-round waves-effect waves-light mt-1">Delete</a>
                    ';
                    return $btn;
                })
                ->addColumn('created_at', function($row){

                    return date('d F Y', strtotime($row->created_at));
                })
                ->rawColumns(['action'])
                ->make(true);

        }
        $user['currency']=$this->currency;
        $faq_data = $faq_data->get();
        return view('admin.manageFaq')->with(['data'=>$user,'faq_data'=>$faq_data]);
    }
}
