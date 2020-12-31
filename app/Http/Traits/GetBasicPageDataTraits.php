<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use App\Model\cart;
use App\Model\cart_submenu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use File;

trait GetBasicPageDataTraits
{
    function getBasicCount($user)
    {
        $user_data = $user;
        $cart = new cart();
        $cart_avail = $cart->checkCartAvaibility($user->id);
        if($cart_avail == NULL){
            $user_data->cart_item_count = 0;
        }
        else{

            $cart_submenu = new cart_submenu;
            $quant_details = array();
            $quant_details['user_id'] = $user->id;
            $quant_details['cart_id'] = $cart_avail->id;
            $quant_details['restaurent_id'] = $cart_avail->restaurent_id;
            $cart_menu_data = $cart_submenu->getCartMenuList($quant_details);
            if ($cart_menu_data != NULL) {
                $item = 0;
                foreach ($cart_menu_data as $m_data) {
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                    }
                }
            }
            $user_data->cart_item_count = $item ?? '0';

        }
        return $user_data;

    }

}
