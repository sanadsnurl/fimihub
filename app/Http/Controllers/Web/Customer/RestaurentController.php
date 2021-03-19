<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\BillingCalculateTraits;
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
use App\Model\OrderEvent;
use Response;
use Session;

class RestaurentController extends Controller
{
    use GetBasicPageDataTraits,BillingCalculateTraits;
    public function getRestaurentDetails(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user = $this->getBasicCount($user);
        }

        $resto_id = base64_decode(request('resto_id'));
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($resto_id);

        $menu_list = new menu_list;

        $billing_data_arary = ['menu_id' =>false,
        'order_id' =>false,
        'user_id' =>$user->id ?? 0,
        'resto_id' =>$resto_id
        ];
        $billing_balance = $this->getBilling($billing_data_arary);
        $order_events = new OrderEvent();
        $rating_array = ['user_id'=> $resto_data->user_id,
                        'user_type'=>2
                    ];
        $rating_data = $order_events->getOrderEventRatingData($rating_array)->first();

        $menu_cat = $menu_list->menuCategory($resto_id);
        // dd($menu_cat->toArray());
        if(Auth::check()){

        $user->currency=$this->currency;
        }
        // dd($billing_balance['menu_data']);
        // dd($billing_balance['menu_data'][0]->add_ons_cat);

        return view('customer.menuList')->with(['user_data'=>$user ?? NULL,
                                                'menu_data'=>$billing_balance['menu_data'],
                                                'menu_cat'=>$menu_cat,
                                                'rating_data'=>$rating_data,
                                                'total_amount'=>$billing_balance['total_amount'],
                                                'sub_total'=>$billing_balance['sub_total'],
                                                'item'=>$billing_balance['item'],
                                                'resto_data'=>$resto_data
                                                ]);
    }
}
