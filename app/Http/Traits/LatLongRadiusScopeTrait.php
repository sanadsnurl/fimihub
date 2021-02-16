<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;
use App\Model\order;
use App\Model\restaurent_detail;
use App\User;
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
        $disctance_select = sprintf(
                "restaurent_details.*,ua.latitude,ua.longitude,ua.id as addres_id,COUNT(DISTINCT ml.id) AS dish_count,
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

            // foreach ($data as $value) {
            //     $value->frick= new stdClass ;
            //     $value->dakota = new stdClass ;
            //     $value->dakota->lat =  $lat;
            //     $value->dakota->lng =  $lng;
            //     $value->frick->lat =  $value->latitude;
            //     $value->frick->lng =  $value->longitude;
            //     $value->dis = $this->getDistanceScript((int)$lat,$lng, $value->latitude,$value->longitude);
            // }




            // dd($data->toArray());
    }

    // public function getDistanceBetweenPointsNew($latitude1, $longitude1, $latitude2, $longitude2, $unit = 'Km') {
    //     $theta = $longitude1 - $longitude2;
    //     $distance = (sin(deg2rad($latitude1)) * sin(deg2rad($latitude2))) + (cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * cos(deg2rad($theta)));
    //     $distance = acos($distance);
    //     $distance = rad2deg($distance);
    //     $distance = $distance * 60 * 1.1515; switch($unit) {
    //          case 'Mi': break; case 'Km' : $distance = $distance * 1.609344;
    //     }
    //     return (round($distance,2));
//    }








   public function getDistanceScript($lat,$lng, $latitude,$longitude) {

    // echo "<script>";
    // echo "alert('hello');";
    // echo "</script>";

    $value = 'asdfsdf';
    echo "<script>";

        // console.log(dakota, 'dakota');
        // console.log(frick, 'frick');
     echo   "
        var frick = {};
        var dakota = {};
        frick.lat = (int)$lat;
        frick.lng = (int)$lng;
        dakota.lat = (int)$latitude;
        dakota.lng = (int)$longitude;
        var directionsData = {};
        if (isNaN(frick.lat)) {
            return false;
        }
        if (isNaN(frick.lng)) {
            return false;
        }
        if (isNaN(dakota.lat)) {
            return false;
        }
        if (isNaN(dakota.lng)) {
            return false;
        }
        const center = { lat: 18.4490849, lng: -77.2419522 };
        const options = { zoom: 15, scaleControl: true, center: center };
        map = new google.maps.Map(
            document.getElementById('map'), options);
        // get distance accouring to address
        // const dakota = {lat: 28.6623, lng: 77.1411};
        // const frick = {lat: 28.6280, lng: 77.3649};
        // The markers for The Dakota and The Frick Collection
        var mk1 = new google.maps.Marker({ position: dakota, map: map });
        var mk2 = new google.maps.Marker({ position: frick, map: map });
        // Draw a line showing the straight distance between the markers
        function haversine_distance(mk1, mk2) {
            var R = 3958.8; // Radius of the Earth in miles
            var rlat1 = mk1.position.lat() * (Math.PI / 180); // Convert degrees to radians
            var rlat2 = mk2.position.lat() * (Math.PI / 180); // Convert degrees to radians
            var difflat = rlat2 - rlat1; // Radian difference (latitudes)
            var difflon = (mk2.position.lng() - mk1.position.lng()) * (Math.PI / 180); // Radian difference (longitudes)
            var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
            return d;
        }
        // Calculate and display the distance between markers
        var distance = haversine_distance(mk1, mk2);
        // console.log(distance, 'distance');
        let directionsService = new google.maps.DirectionsService();
        let directionsRenderer = new google.maps.DirectionsRenderer();
        const route = {
            origin: dakota,
            destination: frick,
            travelMode: 'DRIVING'
        }
        directionsData = directionsService.route(route,
            function(response, status) { // anonymous function to capture directions
                if (status !== 'OK') {
                    // window.alert('Directions request failed due to ' + status);
                    // console.log('Directions request failed due to ' + status);
                    $('#add_error').html('Address Is Not Valid !');

                    return;
                } else {
                    directionsRenderer.setDirections(response); // Add route to the map
                    var directionsData = response.routes[0].legs[0]; // Get data about the mapped route
                    if (!directionsData) {
                        $('#add_error').html('Address Is Not Valid !');

                        //   console.log('Directions request failed');
                        return;
                    } else {
                        var delivery_crg = 0;
                        var str = directionsData.distance.text;
                        var diskm = str.replace('km', '');
                        var dis = parseFloat(diskm.replace(',', ''));

                        console.log(dis);

                    }
                }
            });";

        echo "</script>";
return true;

    // return  $value;
   }

}
