<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\GetBasicPageDataTraits;
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
use App\Model\menu_custom_list;
use Response;
use Session;

class RestaurentController extends Controller
{
    use GetBasicPageDataTraits;
    public function getRestaurentDetails(Request $request)
    {
        $user = Auth::user();

        $user = $this->getBasicCount($user);
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
            $add_ons = array();
            $add_ons_cat = array();
            $menu_custom_list = new menu_custom_list();
            $m_data->variant_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                        ->where('resto_custom_cat_id',$m_data->product_variant_id)->get();
            $m_data->variant_data_cat = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                        ->where('resto_custom_cat_id',$m_data->product_variant_id)->first();
            $m_data->product_add_on_id = json_decode($m_data->product_add_on_id);

            foreach($m_data->product_add_on_id as $add_on){
                $add_ons[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                        ->where('resto_custom_cat_id',$add_on)->get();
                $add_ons_cat[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                        ->where('resto_custom_cat_id',$add_on)->first();
            }
            $m_data->add_on = ($add_ons);
            $m_data->add_ons_cat = $add_ons_cat;
            if(!isset($m_data->quantity)){
                $m_data->quantity=NULL;
            }
            if($m_data->quantity != NULL){
                $item = $item + $m_data->quantity;
                $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
            }
        }
        // dd($m_data->add_on);

        // dd($menu_data->toArray());
        $menu_cat = $menu_list->menuCategory($resto_id);
        $user->currency=$this->currency;
        return view('customer.menuList')->with(['user_data'=>$user,
                                                'menu_data'=>$menu_data,
                                                'menu_cat'=>$menu_cat,
                                                'total_amount'=>$total_amount,
                                                'item'=>$item,
                                                'resto_data'=>$resto_data
                                                ]);
    }
}
