<?php

namespace App\Http\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
//user import section
use App\User;
use App\Model\cart;
use App\Model\cart_submenu;
use App\Model\menu_list;
use App\Model\menu_custom_list;
use App\Model\restaurent_detail;
use App\Model\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;
use File;
use DB;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;

trait BillingCalculateTraits
{
    function getBilling($billing_data_arary)
    {
        $menu_list = new menu_list;
        $quant_details = array();
        if ($billing_data_arary['user_id']) {
            $quant_details['user_id'] = $billing_data_arary['user_id'];
        }
        if ($billing_data_arary['resto_id']) {
            $quant_details['restaurent_id'] = $billing_data_arary['resto_id'];
        }
        if ($billing_data_arary['menu_id']) {
            $quant_details['menu_id'] = $billing_data_arary['menu_id'];
        }
        if ($billing_data_arary['order_id']) {
            $quant_details['order_id'] = $billing_data_arary['order_id'];
        }
        // return ($billing_data_arary['menu_id']);
        // $quant_details['cart_exist_id']=$billing_data_arary['cart_id'];
        $menu_total = 0;

        if ($billing_data_arary['menu_id']) {
            $menu_datass = $menu_list->menuListByQuantityById($quant_details);
            $menu_total = 0;
            $item = 0;
            if (count($menu_datass)) {
                foreach ($menu_datass as $m_data) {
                    $add_ons = array();
                    $add_ons_cat = array();
                    $add_ons_select = array();
                    $add_ons_cat_select = array();

                    $menu_custom_list = new menu_custom_list();
                    $m_data->variant_data = ($menu_custom_list->menuCustomPaginationData($m_data->restaurent_id ?? 0)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id ?? 0)->get()) ?? '[]';
                    $m_data->variant_data_cat = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id ?? 0)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id ?? 0)->first() ?? 0;
                    $var_sin_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id ?? 0)
                        ->where('resto_custom_cat_id', $m_data->product_variant_id ?? 0)->first() ?? 0;
                    $m_data->product_add_on_id = json_decode($m_data->product_add_on_id ?? 0) ?? 0;

                    if (!empty($m_data->variant_data) && count($m_data->variant_data)) {
                        if (!empty($m_data->quantity)  && !empty($m_data->cart_variant_id)) {
                            $var_d = $menu_custom_list->getCustomListPriceWithPer($m_data->cart_variant_id);
                            $m_data->price = $var_d->price ?? '';
                        } else {
                            $var_d = $menu_custom_list->getCustomListPriceWithPer($var_sin_data->id);
                            $m_data->price = $var_d->price ?? '';
                        }
                    }
                    if ($m_data->product_add_on_id) {
                        foreach ($m_data->product_add_on_id as $add_on) {
                            $add_ons[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->get();
                            $add_ons_cat[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->first();
                        }
                    }

                    if ($m_data->product_adds_id) {
                        $m_data->product_adds_id = json_decode($m_data->product_adds_id);


                        foreach ($m_data->product_adds_id as $add_on_cart) {
                            $add_ons_select[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->get();
                            $add_ons_cat_select[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                                ->where('resto_custom_cat_id', $add_on)->first();
                            $var_ds = $menu_custom_list->getCustomListPriceWithPer($add_on_cart);
                            // dd($var_ds);
                            // $m_data->price = $var_d->price;
                            if($var_ds != NULL){

                                $menu_total = $menu_total + (1 * $var_ds->price ?? $var_ds['price']);
                            }
                        }
                    }

                    $m_data->add_ons_select = ($add_ons_select);
                    $m_data->add_ons_cat_select = ($add_ons_cat_select);
                    $m_data->add_on = ($add_ons);
                    $m_data->add_ons_cat = $add_ons_cat;
                    if (!isset($m_data->quantity)) {
                        $m_data->quantity = NULL;
                    }
                    if ($m_data->quantity != NULL) {
                        $item = $item + $m_data->quantity;
                        $menu_total = $menu_total + ($m_data->quantity * $m_data->price);
                    }
                }
            }
        }
        $menu_data = $menu_list->menuListByQuantity($quant_details);


        // return $menu_data;
        $total_amount = 0;
        $item = 0;

        foreach ($menu_data as $m_data) {
            $add_ons = array();
            $add_ons_cat = array();
            $add_ons_select = array();
            $add_ons_cat_select = array();
            $menu_custom_list = new menu_custom_list();
            $m_data->variant_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                ->where('resto_custom_cat_id', $m_data->product_variant_id)->get();
            $m_data->variant_data_cat = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                ->where('resto_custom_cat_id', $m_data->product_variant_id)->first();
            $var_sin_data = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                ->where('resto_custom_cat_id', $m_data->product_variant_id)->first();
            if ($m_data->product_add_on_id) {
                $m_data->product_add_on_id = json_decode($m_data->product_add_on_id);
            } else {
                $m_data->product_add_on_id = [];
            }
            if (count($m_data->variant_data)) {
                if (!empty($m_data->quantity) && !empty($m_data->cart_variant_id)) {
                    $var_d = $menu_custom_list->getCustomListPrice($m_data->cart_variant_id);
                    // echo $m_data->cart_variant_id.'<br>';

                    $m_data->price = $var_d->price;
                    // echo $var_d->price ;
                } else {
                    $var_d = $menu_custom_list->getCustomListPrice($var_sin_data->id);
                    $m_data->price = $var_d->price;
                }
            }
            if ($m_data->product_add_on_id) {
                foreach ($m_data->product_add_on_id as $add_on) {
                    $add_ons[] = $menu_custom_list->menuCustomPaginationData($m_data->restaurent_id)
                        ->where('resto_custom_cat_id', $add_on)->get();
                    $add_ons_cat[] = $menu_custom_list->menuCustomCategoryData($m_data->restaurent_id)
                        ->where('resto_custom_cat_id', $add_on)->first();
                }
            }
            if ($m_data->product_adds_id) {
                $m_data->product_adds_id = json_decode($m_data->product_adds_id);
                foreach ($m_data->product_adds_id as $add_on_cart) {
                    $var_ds = $menu_custom_list->getCustomListPriceWithPer($add_on_cart);
                    // $m_data->price = $var_d->price;
                    if($var_ds != NULL){

                        $total_amount = $total_amount + (1 * $var_ds->price);
                    }
                }
            }

            $m_data->add_on = ($add_ons);
            $m_data->add_ons_cat = $add_ons_cat;
            if (!isset($m_data->quantity)) {
                $m_data->quantity = NULL;
            }
            if ($m_data->quantity != NULL) {
                $item = $item + $m_data->quantity;
                $total_amount = $total_amount + ($m_data->quantity * $m_data->price);
            }
        }
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($quant_details['restaurent_id']);
        $ServiceCategories = new ServiceCategory();
        $service_data = $ServiceCategories->getServiceById(1);
        if($resto_data->resto_tax_status == 2) {
            $service_data->tax = 0;
        }
        $sub_total = $total_amount / (1 + ($service_data->tax / 100));

        $service_tax = (($service_data->tax / 100) * $total_amount);
        $service_data->service_tax = $service_tax;
        $quantity = 0;
        if ($billing_data_arary['menu_id']) {
            $menu_data = $menu_datass;
            if (isset($menu_data[0]->quantity)) {
                $quantity = $menu_data[0]->quantity;
            }
        }
        // // return $menu_data;
        $total_amount_last = ($total_amount) + $service_tax;

        $response = ([
            'menu_data' => $menu_data,
            'quantity' => $quantity,
            'cart_menu' => $quantity,
            'menu_total' => round($menu_total, 2),
            'total_amount' => round($total_amount, 2),
            'total_amount_last' => round($total_amount_last, 2),
            'service_data' => $service_data,
            'sub_total' => round($sub_total, 2),
            'item' => $item,
            'delivery_charge' => 0
        ]);

        return $response;
    }

    public function getTotalWithDishTaxAddOnWithoutCommission($get_dish_total_array){

        $product_total = $get_dish_total_array['total_amount'] - $get_dish_total_array['delivery_fee'];

        $tax = $get_dish_total_array['service_tax'];
        $sub_total = $product_total / (1 + ($tax / 100));
        $total_tax = $product_total - $product_total / (1 + ($tax / 100));

        $commission = $get_dish_total_array['service_commission'];
        $product_total = $sub_total / (1 + ($commission / 100)) ;
        // dd($total_tax);
        return  round(($product_total + $total_tax),2) ?? 0;
    }
}
