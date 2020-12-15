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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use Session;

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
}
