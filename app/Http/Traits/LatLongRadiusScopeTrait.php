<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;
use App\Model\order;
use App\Model\restaurent_detail;
use App\User;

trait LatLongRadiusScopeTrait
{

    public $max_distance_km_rider = 100;
    public $max_distance_km_order = 100;
    public $max_distance_km_resto = 1000;
    /*
    *  find the n closest locations
    *  @param float $lat latitude of the po+int of interest
    *  @param float $lng longitude of the point of interest
    *  @param integer $max_distance max distance to search our from
    *  @param integer $max_locations max number of locations to return
    *  @param string $units miles|kilometers
    *  @return array
    */
    public function riderClosestOrders($order, $lat, $lng, $max_distance_km_order= 25, $units = 'kilometers')
    {

        // $numberOfVehicle = $myRequestDetails->number_of_vehicle ? $myRequestDetails->number_of_vehicle : 1;
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            default:
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 3959;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }

        /*
        *  Generate the select field for disctance
        */
        $disctance_select = sprintf(
                "orders.*,ua.latitude,ua.longitude,ua.id as addres_id, ( %d * acos( cos( radians(%s) ) " .
                        " * cos( radians( ua.latitude ) ) " .
                        " * cos( radians( ua.longitude ) - radians(%s) ) " .
                        " + sin( radians(%s) ) * sin( radians( ua.latitude ) ) " .
                    ") " .
                ") " .
                "AS distance",
                $gr_circle_radius,
                $lat,
                $lng,
                $lat
            );
        return order::leftjoin('user_address as ua', 'orders.address_id', '=', 'ua.id')
            // ->rightJoin('orders as o', 'user_address.id', '=', 'o.address_id')
            ->select(DB::raw($disctance_select) )
            // ->whereNotNull('o.address_id')
            ->where(function($query) {
                $query->orWhere('orders.order_status', 6)->orWhere('orders.order_status', 5);
            })
            ->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')
                ->where('oe.user_type', 1);
                // ->where('oe.user_id', Auth::id());
            })
            ->having('distance', '<=', $max_distance_km_order)
            ->orderBy('distance', 'ASC' )
            ->whereNull('oe.order_id')
            ->orderBy('orders.id', 'DESC')
            ->groupBy('orders.id');
    }

    /*
    *  find the n closest locations
    *  @param float $lat latitude of the po+int of interest
    *  @param float $lng longitude of the point of interest
    *  @param integer $max_distance_km_ordermax distance to search our from
    *  @param integer $max_locations max number of locations to return
    *  @param string $units miles|kilometers
    *  @return array
    */
    public function closestRiders($order, $lat, $lng, $max_distance_km_rider = 25, $units = 'kilometers')
    {
         // $numberOfVehicle = $myRequestDetails->number_of_vehicle ? $myRequestDetails->number_of_vehicle : 1;
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            default:
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 3959;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }

        /*
        *  Generate the select field for disctance
        */
        $disctance_select = sprintf(
                "users.*,ua.latitude,ua.longitude,ua.id as addres_id, ( %d * acos( cos( radians(%s) ) " .
                        " * cos( radians( ua.latitude ) ) " .
                        " * cos( radians( ua.longitude ) - radians(%s) ) " .
                        " + sin( radians(%s) ) * sin( radians( ua.latitude ) ) " .
                    ") " .
                ") " .
                "AS distance",
                $gr_circle_radius,
                $lat,
                $lng,
                $lat
            );
        return User::leftjoin('user_address as ua', 'users.id', '=', 'ua.user_id')
            // ->rightJoin('orders as o', 'user_address.id', '=', 'o.address_id')
            ->select(DB::raw($disctance_select) )
            // ->whereNotNull('o.address_id')
            // ->where(function($query) {
            //     $query->orWhere('orders.order_status', 6)->orWhere('orders.order_status', 5);
            // })
            // ->leftjoin('order_events as oe',function($query){
            //     $query->on('orders.id', '=', 'oe.order_id')
            //     ->where('oe.user_type', 1);
            //     // ->where('oe.user_id', Auth::id());
            // })
            ->where('users.user_type', 2)
            ->where('users.status', 1)
            ->having('distance', '<=', $max_distance_km_rider )
            ->whereNotNull('ua.user_id')
            ->orderBy('distance', 'ASC' )
            ->groupBy('users.id');
    }

    /*
    *  find the n closest locations
    *  @param float $lat latitude of the po+int of interest
    *  @param float $lng longitude of the point of interest
    *  @param integer $max_distance_km_rider max distance to search our from
    *  @param integer $max_locations max number of locations to return
    *  @param string $units miles|kilometers
    *  @return array
    */
    public function closestRestaurant($order, $lat, $lng, $max_distance_km_resto = 25, $units = 'kilometers')
    {
         // $numberOfVehicle = $myRequestDetails->number_of_vehicle ? $myRequestDetails->number_of_vehicle : 1;
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            default:
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 3959;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                $gr_circle_radius = 6371;
            break;
        }

        /*
        *  Generate the select field for disctance
        */
        $disctance_select = sprintf(
                "restaurent_details.*,ua.latitude,ua.longitude,ua.id as addres_id,COUNT(DISTINCT ml.id) AS dish_count,
                COUNT(DISTINCT oe.id) AS rating_count,Round(AVG(oe.order_feedback),1) AS rating,( %d * acos( cos( radians(%s) ) " .
                        " * cos( radians( ua.latitude ) ) " .
                        " * cos( radians( ua.longitude ) - radians(%s) ) " .
                        " + sin( radians(%s) ) * sin( radians( ua.latitude ) ) " .
                    ") " .
                ") " .
                "AS distance",
                $gr_circle_radius,
                $lat,
                $lng,
                $lat
            );
        return restaurent_detail::leftjoin('user_address as ua', 'restaurent_details.user_id', '=', 'ua.user_id')
            ->leftJoin('menu_list as ml', function($query) {
                return $query->on('restaurent_details.id', '=', 'ml.restaurent_id')->where('ml.visibility', 0);
            })
            ->leftJoin('order_events as oe', function($query) {
                return $query->on('restaurent_details.user_id', '=', 'oe.user_id')
                            ->where('oe.visibility', 0)
                            ->where('oe.user_type', 2)
                            ->whereNotNull('oe.order_feedback');
            })
            ->select(DB::raw($disctance_select) )
            // ->where('users.user_type', 2)
            ->having('distance', '<=', $max_distance_km_resto)
            ->whereNotNull('ua.user_id')
            ->where('restaurent_details.visibility', 0)
            ->having('dish_count', '>', 0)
            ->orderBy('distance', 'ASC' )
            ->groupBy('ml.restaurent_id')
            ->groupBy('restaurent_details.id');
    }
}
