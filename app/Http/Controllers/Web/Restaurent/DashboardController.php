<?php

namespace App\Http\Controllers\Web\Restaurent;

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

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoData($user->id);
        $pending_order_status = ['5','6','7'];
        if($resto_data == NULL){
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData(0);
            $pending_order_data = $orders->customerOrderPaginationData(0)->whereIn('order_status',$pending_order_status);
            $menu_list = new menu_list;
            $menu_data = $menu_list->menuPaginationData(0);
        }
        else{
            $orders = new order;
            $order_data = $orders->customerOrderPaginationData($resto_data->id);
            $pending_order_data = $orders->customerOrderPaginationData($resto_data->id)->whereIn('order_status',$pending_order_status);
            $menu_list = new menu_list;
            $menu_data = $menu_list->menuPaginationData($resto_data->id);
        }
        $order_count = $order_data->count();
        $order_stats = array();
        $order_stats['order_count'] = $order_count;
        $order_stats['pending_order_count'] = $pending_order_data->count();
        $order_stats['dish_count'] = $menu_data->count();
        $order_stats = json_decode(json_encode($order_stats));
        $user['currency']=$this->currency;
        return view('restaurent.indexDashboard')->with(['data'=>$user,
                                                        'order_stats'=>$order_stats]);
        
    }
}
