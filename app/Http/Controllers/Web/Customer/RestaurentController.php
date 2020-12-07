<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\restaurent_detail;
use App\Model\menu_list;
use App\Model\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use Response;
use Session;

class RestaurentController extends Controller
{
    public function getRestaurentDetails(Request $request)
    {
        $user = Auth::user();
        $resto_id = base64_decode(request('resto_id'));
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($resto_id);

        $menu_list = new menu_list;
        $quant_details = array();
        $quant_details['user_id']=$user->id;
        $quant_details['restaurent_id']=$resto_id;

        $menu_data = $menu_list->menuListByQuantity($quant_details);
        $total_amount=0;
        $item=0;

        foreach($menu_data as $m_data){
            $ServiceCategories = new ServiceCategory;
            $service_data = $ServiceCategories->getServiceById(1);
            $percentage = $service_data->commission;

            $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);
            if(!isset($m_data->quantity)){
                $m_data->quantity=NULL;
            }
            if($m_data->quantity != NULL){
                $item = $item + $m_data->quantity;
                $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
            }
        }
        // dd($menu_data);
        $menu_cat = $menu_list->menuCategory($resto_id);
        $user['currency']=$this->currency;
        return view('customer.menuList')->with(['user_data'=>$user,
                                                'menu_data'=>$menu_data,
                                                'menu_cat'=>$menu_cat,
                                                'total_amount'=>$total_amount,
                                                'item'=>$item,
                                                'resto_data'=>$resto_data
                                                ]);
    }
}
