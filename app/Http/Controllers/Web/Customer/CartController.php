<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
use App\Http\Traits\BillingCalculateTraits;
use App\Http\Traits\GetBasicPageDataTraits;
use Illuminate\Http\Request;
//custom import
use App\User;
use App\Model\cart;
use App\Model\restaurent_detail;
use App\Model\user_address;
use App\Model\cart_submenu;
use App\Model\menu_list;
use App\Model\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\cart_customization;
use App\Model\menu_custom_list;
use App\Model\menu_customization;
use Response;
use Session;

class CartController extends Controller
{
    use GetBasicPageDataTraits,BillingCalculateTraits;
    public function index(Request $request)
    {
        $user = Auth::user();
        $user = $this->getBasicCount($user);

        $user_data = auth()->user()->userByIdData($user->id);

        $user_address = new user_address();
        $user_add = $user_address->getUserAddress($user->id);


        $cart = new cart;
        $cart_avail = $cart->checkCartAvaibility($user->id);

        if ($cart_avail == NULL) {
            return view('customer.cart')->with([
                'user_data' => $user,
                'user_address' => $user_add
            ]);
        } else {
            $restaurent_detail = new restaurent_detail;
            $resto_data = $restaurent_detail->getRestoDataOnId($cart_avail->restaurent_id);

            $user_add_def = $user_address->getDefaultAddress($user->id) ?? '';
            $resto_add_def = $user_address->getUserAddress($resto_data->user_id) ?? '';

            // if(!empty($user_add_def)){
            //     dd($user_add_def);

            // }
            // if(!empty($user_add_def)){
            //     dd($resto_add_def);

            // }
            $cart_submenu = new cart_submenu;
            $quant_details = array();
            $quant_details['user_id'] = $user->id;
            $quant_details['cart_id'] = $cart_avail->id;
            $quant_details['restaurent_id'] = $cart_avail->restaurent_id;
            $cart_menu_data = $cart_submenu->getCartMenuList($quant_details);
            // dd($cart_menu_data->toArray());

            if ($cart_menu_data != NULL) {
                foreach ($cart_menu_data as $m_data) {

                    $add_ons = array();
                    $add_ons_cat = array();
                    $add_ons_select = array();
                    $add_ons_cat_select = array();
                    $menu_custom_list = new menu_custom_list();
                    $m_data->variant_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                                ->where('resto_custom_cat_id',$m_data->product_variant_id)->get();
                    $m_data->variant_data_cat = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                                ->where('resto_custom_cat_id',$m_data->product_variant_id)->first();
                    $var_sin_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                            ->where('resto_custom_cat_id',$m_data->product_variant_id)->first();
                    $m_data->product_add_on_id = json_decode($m_data->product_add_on_id);

                    if(!empty($m_data->variant_data)  && !empty($m_data->cart_variant_id)){
                        $var_d = $menu_custom_list->getCustomListPrice($m_data->cart_variant_id);
                        $m_data->price = $var_d->price;
                    }
                    if($m_data->product_add_on_id){
                        foreach($m_data->product_add_on_id as $add_on){
                            $add_ons[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                                    ->where('resto_custom_cat_id',$add_on)->get();
                            $add_ons_cat[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                                    ->where('resto_custom_cat_id',$add_on)->first();
                        }
                    }
                    if($m_data->product_adds_id){
                        $m_data->product_adds_id = json_decode($m_data->product_adds_id);
                        foreach($m_data->product_adds_id as $add_on_cart){
                            $var_ds = $menu_custom_list->getCustomListPrice($add_on_cart);

                        }
                    }

                    $m_data->add_on = ($add_ons);
                    $m_data->add_ons_cat = $add_ons_cat;



                }
                // dd($cart_menu_data->toArray());

                $billing_data_arary = ['menu_id' =>false,
                'order_id' =>false,
                'user_id' =>$user->id,
                'resto_id' =>$quant_details['restaurent_id']
                ];
                $billing_balance = ($this->getBilling($billing_data_arary));
                $user->currency = $this->currency;
                return view('customer.cartAddress')->with([
                    'user_data' => $user,
                    'menu_data' => $cart_menu_data,
                    'user_add_def' => $user_add_def,
                    'resto_add_def' => $resto_add_def,
                    'total_amount' => $billing_balance['total_amount'],
                    'total_amount_last' =>$billing_balance['total_amount_last'],
                    'item' => $billing_balance['item'],
                    'service_data' => $billing_balance['service_data'],
                    'sub_total' => $billing_balance['sub_total'],
                    'resto_data' => $resto_data,
                    'user_address' => $user_add
                ]);
            } else {
                return view('customer.cart')->with([
                    'user_data' => $user,
                    'user_address' => $user_add
                ]);
            }
        }
    }

    public function addToCart(Request $request)
    {
        $user = Auth::user();
        $variant_id = NULL;
        $resto_id = base64_decode(request('resto_id'));
        $menu_id = base64_decode(request('menu_id'));
        $check_event = (request('click_event'));
        $menu_all_data = json_decode(request('menu_data'));
        // dd(array_column($menu_all_data[0], "custom_data", FALSE));

    // dd(json_encode($menu_all_data->custom_data));
        $custom_data = array();
        // dd($menu_all_data);
        if(count($menu_all_data)){
            foreach ($menu_all_data as $value) {
                if($value->name == $menu_id.'-variant'){
                    $variant_id = $value->value;
                }else{
                    $custom_data[] = $value->value;

                }

            }
        }

        $custom_data = json_encode($custom_data);

        // dd($variant_id);
        // return ($check_event);
        $restaurent_detail = new restaurent_detail;

        $resto_data = $restaurent_detail->getRestoDataOnId($resto_id);
        if ($resto_data != NULL) {
            $menu_list = new menu_list;
            $menu_data = $menu_list->menuListByID($menu_id);

            if ($menu_data != NULL) {
                $cart_submenu_data = array();
                $cart = new cart;
                $cart_avail = $cart->checkCartAvaibility($user->id);
                if ($cart_avail == NULL) {
                    $cart_data = array();
                    $cart_data['user_id'] = $user->id;
                    $cart_data['restaurent_id'] = $resto_id;
                    $cart_data['customer_name'] = $user->name;
                    // $cart_data['delivery_fee'] = $resto_data->delivery_charge;
                    $cart_data['tax'] = $resto_data->tax;
                    $cart_id = $cart->makeCart($cart_data);
                    $cart_submenu_data['cart_id'] = $cart_id;
                } elseif ($cart_avail->restaurent_id == $resto_id) {
                    $cart_submenu_data['cart_id'] = $cart_avail->id;
                } else {
                    $cart_id = $cart->deleteCart($user->id);
                    $cart_data = array();
                    $cart_data['user_id'] = $user->id;
                    $cart_data['restaurent_id'] = $resto_id;
                    $cart_data['customer_name'] = $user->name;
                    // $cart_data['delivery_fee'] = $resto_data->delivery_charge;
                    $cart_data['tax'] = $resto_data->tax;
                    $cart_id = $cart->makeCart($cart_data);
                    $cart_submenu_data['cart_id'] = $cart_id;
                }

                $cart_submenu = new cart_submenu;
                $cart_submenu_data['user_id'] = $user->id;
                $cart_submenu_data['menu_id'] = $menu_data->id;
                $cart_submenu_data['product_variant_id'] = $variant_id;
                $cart_submenu_data['product_add_on_id'] = $custom_data;
                if($check_event == 2){
                    $cart_sub_menu = $cart_submenu->makeCartSubMenu($cart_submenu_data);

                }elseif($check_event == 1){
                    $cart_sub_menu = $cart_submenu->removeCartSubMenu($cart_submenu_data);

                }else{
                    $cart_sub_menu = $cart_submenu->changeCartExtraSubMenu($cart_submenu_data);

                }
                $billing_data_arary = ['menu_id' =>$menu_id,
                                    'order_id' =>false,
                                    'user_id' =>$cart_submenu_data['user_id'],
                                    'resto_id' =>$resto_id
                                    ];
                $billing_balance = $this->getBilling($billing_data_arary);
                if(isset($billing_balance['menu_data']['0'])){
                    $billing_menu_data =  $billing_balance['menu_data']['0'];

                }
// return $billing_balance;
                $response = [
                    'quantity' => $billing_menu_data->quantity ?? 0,
                    'items' => $billing_balance['item'],
                    'service_data' => $billing_balance['service_data'],
                    'sub_total' => $billing_balance['sub_total'],
                    'total_amount' => $billing_balance['total_amount']
                ];

                return ($response);
            } else {
                Session::flash('modal_message2', 'Inavlid Menu Item !');
            }
        } else {
            Session::flash('modal_message2', 'Inavlid Restaurent Details !');
        }
    }

}
