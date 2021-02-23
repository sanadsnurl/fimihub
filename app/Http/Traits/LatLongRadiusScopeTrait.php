<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;
use App\Model\order;
use App\Model\restaurent_detail;
use App\User;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Collection;

use Illuminate\Pagination\LengthAwarePaginator;
use stdClass;

trait LatLongRadiusScopeTrait
{
    public $max_distance_km_rider;
    public $max_distance_km_order;
    public $max_distance_km_resto;

    public function __construct()
    {
        $this->max_distance_km_rider = Config('RIDER_NEAR_ORDER');
        $this->max_distance_km_order = Config('RIDER_NEAR_ORDER');
        $this->max_distance_km_runner = Config('RUNNER_NEAR_ORDER');
        $this->max_distance_km_resto = Config('RESTAURANT_NEAR_USER');
        # code...
    }

    /*
    *  find the n closest locations
    *  @param float $lat latitude of the po+int of interest
    *  @param float $lng longitude of the point of interest
    *  @param integer $max_distance max distance to search our from
    *  @param integer $max_locations max number of locations to return
    *  @param string $units miles|kilometers
    *  @return array
    */
    public function riderClosestOrders($order, $lat, $lng, $max_distance_km_order1 = 25, $units = 'kilometers')
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
            ->having('distance', '<=', Config('RIDER_NEAR_ORDER'))
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
    public function closestRiders($order, $lat, $lng, $max_distance_km_rider1 = 25, $units = 'kilometers')
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
            ->where('users.role', 1)
            ->where('users.status', 1)
            ->having('distance', '<=', Config('RIDER_NEAR_ORDER') )
            ->whereNotNull('ua.user_id')
            ->orderBy('distance', 'ASC' )
            ->groupBy('users.id');
    }

    public function closestRunner($order, $lat, $lng, $max_distance_km_rider1 = 25, $units = 'kilometers')
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
            ->where('users.role', 2)
            ->where('users.status', 1)
            ->having('distance', '<=', Config('RUNNER_NEAR_ORDER') )
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
    public function closestRestaurant($order, $lat, $lng, $max_distance_km_resto1 = 25, $units = 'kilometers')
    {
         // $numberOfVehicle = $myRequestDetails->number_of_vehicle ? $myRequestDetails->number_of_vehicle : 1;
        /*
        *  Allow for changing of units of measurement
        */
        switch ( $units ) {
            default:
            case 'miles':
                //radius of the great circle in miles
                $gr_circle_radius = 3958.8;
            break;
            case 'kilometers':
                //radius of the great circle in kilometers
                // $gr_circle_radius = 6371.07103;
                $gr_circle_radius = 8471;

            break;
        }

        /*
        *  Generate the select field for disctance
        */
        // dd($lng);
        $disctance_select = sprintf(
                "null as dis,null as dis_new,restaurent_details.*,ua.latitude,ua.longitude,ua.id as addres_id,COUNT(DISTINCT ml.id) AS dish_count,
                COUNT(DISTINCT oe.id) AS rating_count,Round(AVG(oe.order_feedback),1) AS rating,(( %d * acos( cos( radians(%s) ) " .
                        " * cos( radians( ua.latitude ) ) " .
                        " * cos( radians( ua.longitude ) - radians(%s) ) " .
                        " + sin( radians(%s) ) * sin( radians( ua.latitude ) ) " .
                    ") " .
                ") )  " .
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
            ->having('distance', '<=', Config('RESTAURANT_NEAR_USER'))
            ->whereNotNull('ua.user_id')
            ->where('restaurent_details.visibility', 0)
            ->having('dish_count', '>', 0)
            ->orderBy('distance', 'ASC' )
            ->groupBy('ml.restaurent_id')
            ->groupBy('restaurent_details.id');



            // return $data;

            // dd($data->toArray());
    }
    public function paginate($items, $perPage = 5, $page = null, $options = [])
    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }
    public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2) {
        // dd($longitude1);
        $source_address = $latitude1.",".$longitude1;
        $destination_address = $latitude2.",".$longitude2;
                $url = "https://maps.googleapis.com/maps/api/directions/json?origin=".$source_address."&destination=".$destination_address."&sensor=false&key=".Config('GOOGLE_MAPS_API_KEY');
                    // dd($url);
                $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    $response = curl_exec($ch);
                    curl_close($ch);
                    $response_all = json_decode($response);
                    // dd($response_all);
                    $distance = $response_all->routes[0]->legs[0]->distance->text ?? null;
                    $distance = str_replace('', ',',str_replace('', ' km',$distance));
                    return ($distance ?? null);

   }


}
