<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\GetRestaurentMenuListRequest;
use App\Http\Requests\user\GetRestaurentRequest;
use App\Http\Traits\BillingCalculateTraits;
use App\Http\Traits\GetBasicPageDataTraits;
use App\Http\Traits\LatLongRadiusScopeTrait;
use Illuminate\Http\Request;
//custom import
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Traits\OtpGenerationTrait;
use App\Model\menu_list;
use App\Model\oauth_access_token;
use App\Model\OrderEvent;
use App\Model\restaurent_detail;
use Response;
use File;

class RestaurentManageController extends Controller
{
    use LatLongRadiusScopeTrait, GetBasicPageDataTraits,BillingCalculateTraits;
    public function getRestaurentList(GetRestaurentRequest $request)
    {
        $user = Auth::user();
        $user_data = auth()->user()->userByIdData($user->id);
        $user_data = $this->getBasicCount($user);

        $near_by_radius = config('RESTAURANT_NEAR_USER');
        $near_by_radius = (int)$near_by_radius;

        // dd($user_data);
        if (request('search_field') != NULL) {
            $search_field = request('search_field');
        } else {
            unset($request['search_field']);
        }

        $lat = request('latitude') ?? '18.4490849';
        $lng = request('longitude') ?? '-77.2419522';
        if ($lat == 0 || $lng == 0) {
            $resto = [];

            $resto_data = [];

            $nonveg_resto_data = [];
            $veg_resto_data = [];
        } else {
            $lats = $lat;
            $lngs = $lng;
            // print($lats);
            // $kmRadius = $this->max_distance_km_resto;
            $resto = $this->closestRestaurant($user, $lats, $lngs);

            $restaurent_detail = new restaurent_detail();
            //all restaurants
            $resto_data_query = $this->closestRestaurant($user, $lats, $lngs);
            if ($request->has('search_field')) {
                $resto_data_query = $resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $resto_data = $resto_data_query->limit(6)->get();
            foreach ($resto_data as $value) {
                $value->dis = $this->getDistanceBetweenPointsNew($lats, $lngs, $value->latitude, $value->longitude);
            }
            $resto_data = $resto_data->sortBY('dis', SORT_NATURAL)->where('dis', '<=', $near_by_radius)->values()->all();

            // dd($resto_data->toArray());
            //all nonveg restaurants
            $nonveg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [1, 3]);
            if ($request->has('search_field')) {
                $nonveg_resto_data_query = $nonveg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $nonveg_resto_data = $nonveg_resto_data_query->limit(6)->get();
            foreach ($nonveg_resto_data as $value) {
                $value->dis = $this->getDistanceBetweenPointsNew($lats, $lngs, $value->latitude, $value->longitude);
            }
            $nonveg_resto_data = $nonveg_resto_data->sortBY('dis', SORT_NATURAL)->where('dis', '<=', $near_by_radius)->values()->all();

            //all veg restaurants
            $veg_resto_data_query = $this->closestRestaurant($user, $lats, $lngs)->whereIn('resto_type', [2, 3]);
            if ($request->has('search_field')) {
                $veg_resto_data_query = $veg_resto_data_query->where('ml.name', 'like', '%' . $search_field . '%')
                    ->orWhere('restaurent_details.name', 'like', '%' . $search_field . '%');
            }
            $veg_resto_data = $veg_resto_data_query->limit(6)->get();
            foreach ($veg_resto_data as $value) {
                $value->dis = $this->getDistanceBetweenPointsNew($lats, $lngs, $value->latitude, $value->longitude);
            }
            $veg_resto_data = $veg_resto_data->sortBY('dis', SORT_NATURAL)->where('dis', '<=', $near_by_radius)->values()->all();
        }

        return response()->json([
            'near_by_restaurant' => $resto_data,
            'nonveg' => $nonveg_resto_data,
            'veg' => $veg_resto_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }


    public function getRestaurentMenuDetails(GetRestaurentMenuListRequest $request)
    {
        $user = Auth::user();

        $user = $this->getBasicCount($user);
        $restaurant_id = (request('restaurant_id'));
        $restaurent_detail = new restaurent_detail;
        $resto_data = $restaurent_detail->getRestoDataOnId($restaurant_id);
        // return $resto_data;
        if(empty($resto_data)){
            return response()->json(['message' => 'Invalid Restaurant', 'status' => false], $this->failureStatus);

        }
        $menu_list = new menu_list();

        $billing_data_arary = [
            'menu_id' => false,
            'order_id' => false,
            'user_id' => $user->id,
            'resto_id' => $restaurant_id
        ];
        $billing_balance = $this->getBilling($billing_data_arary);
        $order_events = new OrderEvent();
        $rating_array = [
            'user_id' => $resto_data->user_id,
            'user_type' => 2
        ];
        $rating_data = $order_events->getOrderEventRatingData($rating_array)->first();

        $menu_cat = $menu_list->menuCategory($restaurant_id);
        $user->currency = $this->currency;
        if(count($billing_balance['menu_data'])){
            foreach($billing_balance['menu_data'] as $m_data){
                // return $m_data;
                // $m_data->variant_data_cat->variant_menu = array();
                $variant_menu = array();
                // return ( $m_data->variant_data_cat->variant_menu );
                if(count($m_data->variant_data)){
                    foreach($m_data->variant_data as $variant_d){
                        if($m_data->variant_data_cat->cats_id == $variant_d->resto_custom_cat_id){
                            $variant_menu[] = $variant_d;
                        }
                        $m_data->variant_data_cat->variant_menu = $variant_menu;
                    }
                }
                if(count($m_data->add_ons_cat)){
                    $add_on_menu_cat = array();
                    foreach($m_data->add_ons_cat as $add_cat_loop_data){
                        if( $add_cat_loop_data != NULL){
                            $add_cat_loop_data->add_on_menu = array();
                            $add_on_menu = array();
                            // $add_on_menu_cat[] = $add_cat_loop_data;
                            // return ($add_cat_loop_data);
                            if(count($m_data->add_on)){
                                foreach($m_data->add_on as $add_loop_data){
                                    if(count($add_loop_data)){
                                        foreach($add_loop_data as $add_loop_data_m){
                                        // return ($add_loop_data_m);
                                        if($add_cat_loop_data->cats_id == $add_loop_data_m->resto_custom_cat_id){
                                            $add_on_menu[] = $add_loop_data_m;
                                        }
                                    }
                                    $add_cat_loop_data->add_on_menu = $add_on_menu;
                                    }
                                }
                                $add_on_menu_cat[] = $add_cat_loop_data;
                            }
                        }
                        $m_data->add_ons_cat = $add_on_menu_cat;
                    }
                }

                unset($m_data->variant_data);
                unset($m_data->add_on);
                if(!isset($m_data->product_adds_id)){
                    $m_data->product_adds_id = [];
                }
                if(!isset($m_data->quantity)){
                    $m_data->quantity = 0;
                }else{
                    $m_data->quantity = (int)$m_data->quantity;
                }
                if(!isset($m_data->cart_variant_id)){
                    $m_data->cart_variant_id = 0;
                }
            }
    }
// dd( $billing_balance['menu_data']);
        return response()->json([
            'menu_data' => $billing_balance['menu_data'],
            'menu_cat' => $menu_cat,
            'rating_data' => $rating_data,
            'total_amount' => $billing_balance['total_amount'],
            'sub_total' => $billing_balance['sub_total'],
            'item' => $billing_balance['item'],
            'resto_data' => $resto_data,
            'message' => 'success',
            'status' => true
        ], $this->successStatus);
    }
}
