<?php

namespace App\Http\Controllers\Web\Customer;

use App\Http\Controllers\Controller;
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
use App\Model\menu_customization;
use Response;
use Session;

class CartController extends Controller
{
    use GetBasicPageDataTraits;
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

            $cart_submenu = new cart_submenu;
            $quant_details = array();
            $quant_details['user_id'] = $user->id;
            $quant_details['cart_id'] = $cart_avail->id;
            $quant_details['restaurent_id'] = $cart_avail->restaurent_id;
            $cart_menu_data = $cart_submenu->getCartMenuList($quant_details);
            if ($cart_menu_data != NULL) {
                $total_amount = 0;
                $item = 0;
                $custom_total = 0;
                $custom_count = 0;
                foreach ($cart_menu_data as $m_data) {
                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $percentage = $service_data->commission;

                    $menu_customizations = new menu_customization();
                    $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    foreach ($m_data->add_on_data as $add_data) {
                        $cart_customizations = new cart_customization();
                        $quant_details['cart_submenu_id'] = $m_data->sub_menu_id;
                        $quant_details['custom_id'] = $add_data->id;
                        $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                        $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                        if (isset($cart_add_data)) {
                            if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                $add_data->quantity = $cart_add_data->quantity;
                                $custom_count = $custom_count + $cart_add_data->quantity;
                                $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                            } else {
                                $add_data->quantity = 0;
                            }
                        } else {
                            $add_data->quantity = 0;
                        }
                    }

                    $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                        $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                    }
                }
                $sub_total = $total_amount + $custom_total;

                $total_amount = $total_amount + $custom_total;
                // dd($cart_menu_data);
                //add delivery charge and tax in total amount
                $ServiceCategories = new ServiceCategory;
                $service_data = $ServiceCategories->getServiceById(1);
                $service_tax = (($service_data->tax / 100) * $total_amount);
                $service_data->service_tax = $service_tax;
                $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax + $service_tax;
                $user->currency = $this->currency;
                return view('customer.cartAddress')->with([
                    'user_data' => $user,
                    'menu_data' => $cart_menu_data,
                    'total_amount' => $total_amount,
                    'item' => $item,
                    'service_data' => $service_data,
                    'sub_total' => $sub_total,
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
        $resto_id = base64_decode(request('resto_id'));
        $menu_id = base64_decode(request('menu_id'));
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
                    $cart_data['delivery_fee'] = $resto_data->delivery_charge;
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
                    $cart_data['delivery_fee'] = $resto_data->delivery_charge;
                    $cart_data['tax'] = $resto_data->tax;
                    $cart_id = $cart->makeCart($cart_data);
                    $cart_submenu_data['cart_id'] = $cart_id;
                }
                $cart_submenu = new cart_submenu;
                $cart_submenu_data['user_id'] = $user->id;
                $cart_submenu_data['menu_id'] = $menu_data->id;
                $cart_sub_menu = $cart_submenu->makeCartSubMenu($cart_submenu_data);

                $get_qaunt = array();
                $get_quant['cart_id'] = $cart_submenu_data['cart_id'];
                $get_quant['menu_id'] = $menu_data->id;
                $cart_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);

                $menu_list = new menu_list;
                $quant_details = array();
                $quant_details['user_id'] = $user->id;
                $quant_details['restaurent_id'] = $resto_id;

                $menu_data = $menu_list->menuListByQuantity($quant_details);

                $total_amount = 0;
                $item = 0;
                $custom_count = 0;
                $custom_total = 0;
                foreach ($menu_data as $m_data) {
                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $percentage = $service_data->commission;
                    $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);

                    //==================================================================================
                    $menu_customizations = new menu_customization();
                    $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    foreach ($m_data->add_on_data as $add_data) {
                        $cart_customizations = new cart_customization();
                        $quant_details['custom_id'] = $add_data->id;
                        $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                        $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                        $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                        if (isset($cart_add_data)) {
                            if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                $add_data->quantity = $cart_add_data->quantity;
                                $custom_count = $custom_count + $cart_add_data->quantity;
                                $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                            } else {
                                $add_data->quantity = 0;
                            }
                        } else {
                            $add_data->quantity = 0;
                        }
                    }
                    //===================================================================
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                        $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                    }
                }
                $sub_total = $total_amount + $custom_total;

                $total_amount = $total_amount + $custom_total;
                $ServiceCategories = new ServiceCategory;
                $service_data = $ServiceCategories->getServiceById(1);
                $service_tax = (($service_data->tax / 100) * $total_amount);
                // $service_tax = number_format((float) $service_tax, 2);
                $service_data->service_tax = $service_tax;
                $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax;

                $response = [
                    'quantity' => $cart_sub_menu->quantity,
                    'items' => $item,
                    'service_data' => $service_data,
                    'sub_total' => $sub_total,
                    'total_amount' => $total_amount
                ];

                return ($response);
            } else {
                Session::flash('modal_message2', 'Inavlid Menu Item !');
            }
        } else {
            Session::flash('modal_message2', 'Inavlid Restaurent Details !');
        }
    }

    public function removeFromCart(Request $request)
    {
        $user = Auth::user();
        $resto_id = base64_decode(request('resto_id'));
        $menu_id = base64_decode(request('menu_id'));
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
                    $cart_data['delivery_fee'] = $resto_data->delivery_charge;
                    $cart_data['tax'] = $resto_data->tax;
                    $cart_id = $cart->makeCart($cart_data);
                    $cart_submenu_data['cart_id'] = $cart_id;
                } elseif ($cart_avail->restaurent_id == $resto_id) {
                    $cart_submenu_data['cart_id'] = $cart_avail->id;
                }

                $cart_submenu = new cart_submenu;
                $cart_submenu_data['user_id'] = $user->id;
                $cart_submenu_data['menu_id'] = $menu_data->id;
                $cart_sub_menu = $cart_submenu->removeCartSubMenu($cart_submenu_data);

                $get_qaunt = array();
                $get_quant['cart_id'] = $cart_submenu_data['cart_id'];
                $get_quant['menu_id'] = $menu_data->id;
                $cart_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);
                if ($cart_sub_menu == NULL) {
                    $menu_list = new menu_list;
                    $quant_details = array();
                    $quant_details['user_id'] = $user->id;
                    $quant_details['restaurent_id'] = $resto_id;

                    $menu_data = $menu_list->menuListByQuantity($quant_details);

                    $total_amount = 0;
                    $item = 0;
                    $custom_count = 0;
                    $custom_total = 0;
                    foreach ($menu_data as $m_data) {
                        $ServiceCategories = new ServiceCategory;
                        $service_data = $ServiceCategories->getServiceById(1);
                        $percentage = $service_data->commission;
                        //==================================================================================
                        $menu_customizations = new menu_customization();
                        $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                        foreach ($m_data->add_on_data as $add_data) {
                            $cart_customizations = new cart_customization();
                            $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                            $quant_details['custom_id'] = $add_data->id;
                            $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                            $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                            if (isset($cart_add_data)) {
                                if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                    $add_data->quantity = $cart_add_data->quantity;
                                    $custom_count = $custom_count + $cart_add_data->quantity;
                                    $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                                } else {
                                    $add_data->quantity = 0;
                                }
                            } else {
                                $add_data->quantity = 0;
                            }
                        }
                        //===================================================================
                        $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);

                        if ($m_data->quantity != NULL) {
                            $item = $item + $m_data->quantity;
                            $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                        }
                    }
                    if($item == 0){
                        $cart_delete = $cart->deleteCartAndMenu($user->id);
                    }
                    $sub_total = $total_amount + $custom_total;
                    $total_amount = $total_amount + $custom_total;

                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $service_tax = (($service_data->tax / 100) * $total_amount);
                    $service_data->service_tax = $service_tax;
                    $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax;


                    $response = [
                        'quantity' => 0,
                        'items' => $item,
                        'service_data' => $service_data,
                        'sub_total' => $sub_total,
                        'total_amount' => $total_amount
                    ];

                    return $response;
                } else {
                    $menu_list = new menu_list;
                    $quant_details = array();
                    $quant_details['user_id'] = $user->id;
                    $quant_details['restaurent_id'] = $resto_id;

                    $menu_data = $menu_list->menuListByQuantity($quant_details);

                    $total_amount = 0;
                    $item = 0;
                    $custom_count = 0;
                    $custom_total = 0;
                    foreach ($menu_data as $m_data) {
                        $ServiceCategories = new ServiceCategory;
                        $service_data = $ServiceCategories->getServiceById(1);
                        $percentage = $service_data->commission;
                        //==================================================================================
                        $menu_customizations = new menu_customization();
                        $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                        foreach ($m_data->add_on_data as $add_data) {
                            $cart_customizations = new cart_customization();
                            $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                            $quant_details['custom_id'] = $add_data->id;
                            $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                            $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                            if (isset($cart_add_data)) {
                                if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                    $add_data->quantity = $cart_add_data->quantity;
                                    $custom_count = $custom_count + $cart_add_data->quantity;
                                    $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                                } else {
                                    $add_data->quantity = 0;
                                }
                            } else {
                                $add_data->quantity = 0;
                            }
                        }
                        //===================================================================

                        $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);

                        if ($m_data->quantity != NULL) {
                            $item = $item + $m_data->quantity;
                            $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                        }
                    }
                    $sub_total = $total_amount + $custom_total;
                    $total_amount = $total_amount + $custom_total;
                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $service_tax = (($service_data->tax / 100) * $total_amount);
                    $service_data->service_tax = $service_tax;
                    $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax;

                    $response = [
                        'quantity' => $cart_sub_menu->quantity,
                        'items' => $item,
                        'service_data' => $service_data,
                        'sub_total' => $sub_total,
                        'total_amount' => $total_amount
                    ];

                    return ($response);
                }
            } else {
                Session::flash('modal_message2', 'Inavlid Menu Item !');
            }
        } else {
            Session::flash('modal_message2', 'Inavlid Restaurent Details !');
        }
    }


    public function addToCartCustom(Request $request)
    {
        $user = Auth::user();
        $resto_id = base64_decode(request('resto_id'));
        $menu_id = base64_decode(request('menu_id'));
        $custom_id = base64_decode(request('custom_id'));

        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($resto_id);

        if ($resto_data != NULL) { // Resto is valid
            $menu_list = new menu_list;
            $menu_data = $menu_list->menuListByID($menu_id);

            if ($menu_data != NULL) { // Menu is valid
                $cart_submenu_data = array();
                $cart = new cart;
                $cart_avail = $cart->checkCartAvaibility($user->id);
                if ($cart_avail == NULL) {
                    $cart_data = array();
                    $cart_data['user_id'] = $user->id;
                    $cart_data['restaurent_id'] = $resto_id;
                    $cart_data['customer_name'] = $user->name;
                    $cart_data['delivery_fee'] = $resto_data->delivery_charge;
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
                    $cart_data['delivery_fee'] = $resto_data->delivery_charge;
                    $cart_data['tax'] = $resto_data->tax;
                    $cart_id = $cart->makeCart($cart_data);
                    $cart_submenu_data['cart_id'] = $cart_id;
                }
                $cart_submenu = new cart_submenu;
                $cart_submenu_data['user_id'] = $user->id;
                $cart_submenu_data['menu_id'] = $menu_data->id;
                $get_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);
                if (empty($get_sub_menu)) {
                    $cart_sub_menu = $cart_submenu->makeCartSubMenu($cart_submenu_data);
                    $get_qaunt = array();
                    $get_quant['cart_id'] = $cart_submenu_data['cart_id'];
                    $get_quant['menu_id'] = $menu_data->id;
                    $cart_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);

                    $cart_submenu_data['cart_submenu_id'] = $cart_sub_menu->id;
                    $cart_submenu_data['custom_id'] = $custom_id;
                    $cart_customizations = new cart_customization();
                    $cart_custom_set = $cart_customizations->makeCustomCartSubMenu($cart_submenu_data);
                } else {
                    $get_qaunt = array();
                    $get_quant['cart_id'] = $cart_submenu_data['cart_id'];
                    $get_quant['menu_id'] = $menu_data->id;
                    $cart_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);

                    $cart_submenu_data['cart_submenu_id'] = $cart_sub_menu->id;
                    $cart_submenu_data['custom_id'] = $custom_id;
                    $cart_customizations = new cart_customization();
                    $cart_custom_set = $cart_customizations->makeCustomCartSubMenu($cart_submenu_data);
                }

                $menu_list = new menu_list;
                $quant_details = array();
                $quant_details['user_id'] = $user->id;
                $quant_details['restaurent_id'] = $resto_id;

                $menu_data = $menu_list->menuListByQuantity($quant_details);

                $total_amount = 0;
                $item = 0;
                $custom_count = 0;
                $custom_total = 0;
                foreach ($menu_data as $m_data) {
                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $percentage = $service_data->commission;

                    $menu_customizations = new menu_customization();
                    // $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    $cart_customizations = new cart_customization();
                    $quant_details['cart_submenu_id'] = $cart_sub_menu->id;
                    $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                    $quant_details['custom_id'] = $custom_id;
                    $cart_add_data = $cart_customizations->getCartCustomDataByID($quant_details['custom_id'])->first();
                    //==================================================================================
                    $menu_customizations = new menu_customization();
                    $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    foreach ($m_data->add_on_data as $add_data) {
                        $cart_customizations = new cart_customization();
                        $quant_details['custom_id'] = $add_data->id;
                        $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                        $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                        if (isset($cart_add_data)) {
                            if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                $add_data->quantity = $cart_add_data->quantity;
                                $custom_count = $custom_count + $cart_add_data->quantity;
                                $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                            } else {
                                $add_data->quantity = 0;
                            }
                        } else {
                            $add_data->quantity = 0;
                        }
                    }
                    //===================================================================

                    $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                        $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                    }
                }
                $sub_total = $total_amount + $custom_total;
                $total_amount = $total_amount + $custom_total;
                $ServiceCategories = new ServiceCategory;
                $service_data = $ServiceCategories->getServiceById(1);
                $service_tax = (($service_data->tax / 100) * $total_amount);
                // $service_tax = number_format((float) $service_tax, 2);
                $service_data->service_tax = $service_tax;
                $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax;
                // return $cart_sub_menu;
                $response = [
                    'quantity' => $cart_add_data->quantity,
                    'items' => $item,
                    'service_data' => $service_data,
                    'sub_total' => $sub_total,
                    'total_amount' => $total_amount
                ];

                return ($response);
            } else {
                Session::flash('modal_message2', 'Inavlid Menu Item !');
            }
        } else {
            Session::flash('modal_message2', 'Inavlid Restaurent Details !');
        }
    }

    public function removeFromCartCustom(Request $request)
    {
        $user = Auth::user();
        $resto_id = base64_decode(request('resto_id'));
        $menu_id = base64_decode(request('menu_id'));
        $custom_id = base64_decode(request('custom_id'));

        $restaurent_detail = new restaurent_detail;

        $resto_data = $restaurent_detail->getRestoDataOnId($resto_id);
        if ($resto_data != NULL) {
            $menu_list = new menu_list;
            $menu_data = $menu_list->menuListByID($menu_id);

            if ($menu_data != NULL) {
                $cart_submenu_data = array();
                $cart = new cart;
                $cart_avail = $cart->checkCartAvaibility($user->id);
                if ($cart_avail->restaurent_id == $resto_id) {
                    $cart_submenu_data['cart_id'] = $cart_avail->id;
                }

                $cart_submenu = new cart_submenu;
                $cart_submenu_data['user_id'] = $user->id;
                $cart_submenu_data['menu_id'] = $menu_data->id;
                $get_sub_menu = $cart_submenu->getCartValue($cart_submenu_data);
                if (!empty($get_sub_menu)) {
                    $get_qaunt = array();
                    $get_quant['cart_id'] = $cart_submenu_data['cart_id'];
                    $get_quant['menu_id'] = $menu_data->id;
                    $get_quant['cart_submenu_id'] = $menu_data->id;
                    $get_quant['custom_id'] = $menu_data->id;

                    // $quant_details['cart_submenu_id'] = $get_sub_menu->id;
                    // $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                    // $quant_details['custom_id'] = $custom_id;
                    $cart_customizations = new cart_customization();

                    $cart_submenu_data['cart_submenu_id'] = $get_sub_menu->id;
                    $cart_submenu_data['custom_id'] = $custom_id;
                    $cart_custom_set = $cart_customizations->removeCustomCartSubMenu($cart_submenu_data);
                }
                $menu_list = new menu_list;
                $quant_details = array();
                $quant_details['user_id'] = $user->id;
                $quant_details['restaurent_id'] = $resto_id;

                $menu_data = $menu_list->menuListByQuantity($quant_details);

                $total_amount = 0;
                $item = 0;
                $custom_count = 0;
                $custom_total = 0;
                foreach ($menu_data as $m_data) {
                    $ServiceCategories = new ServiceCategory;
                    $service_data = $ServiceCategories->getServiceById(1);
                    $percentage = $service_data->commission;

                    $menu_customizations = new menu_customization();
                    // $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    $cart_customizations = new cart_customization();
                    $quant_details['cart_submenu_id'] = $get_sub_menu->id;
                    $quant_details['cart_id'] = $cart_submenu_data['cart_id'];
                    $quant_details['custom_id'] = $custom_id;
                    $cart_add_datas = $cart_customizations->getCartCustomDataByID($quant_details['custom_id'])->first();
                    if (!isset($cart_add_datas)) {
                        $cart_add_datas['quantity'] = 0;
                    }
                    //==================================================================================
                    $menu_customizations = new menu_customization();
                    $m_data->add_on_data = $menu_customizations->getAddOnData($m_data->id)->get();

                    foreach ($m_data->add_on_data as $add_data) {
                        $cart_customizations = new cart_customization();
                        $quant_details['custom_id'] = $add_data->id;
                        $cart_add_data = $cart_customizations->getCartCustomDataBySubMenu($quant_details)->first();
                        $add_data->price = $add_data->price + (($percentage / 100) * $add_data->price);
                        if (isset($cart_add_data)) {
                            if (isset($cart_add_data->quantity) || $cart_add_data->quantity != 0) {
                                $add_data->quantity = $cart_add_data->quantity;
                                $custom_count = $custom_count + $cart_add_data->quantity;
                                $custom_total = $custom_total + ($cart_add_data->quantity * $add_data->price);
                            } else {
                                $add_data->quantity = 0;
                            }
                        } else {
                            $add_data->quantity = 0;
                        }
                    }
                    //===================================================================

                    $m_data->price = $m_data->price + (($percentage / 100) * $m_data->price);
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                        $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
                    }
                }
                $sub_total = $total_amount + $custom_total;
                $total_amount = $total_amount + $custom_total;
                $ServiceCategories = new ServiceCategory;
                $service_data = $ServiceCategories->getServiceById(1);
                $service_tax = (($service_data->tax / 100) * $total_amount);
                // $service_tax = number_format((float) $service_tax, 2);
                $service_data->service_tax = $service_tax;
                $total_amount = ($total_amount - $resto_data->discount) + $resto_data->delivery_charge + $resto_data->tax;
                // return $cart_sub_menu;
                $response = [
                    'quantity' => $cart_add_datas->quantity ?? 0,
                    'items' => $item,
                    'service_data' => $service_data,
                    'sub_total' => $sub_total,
                    'total_amount' => $total_amount
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
