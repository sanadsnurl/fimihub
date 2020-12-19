<?php

namespace App\Http\Traits;
use Illuminate\Support\Facades\DB;

trait LatLongRadiusScopeTrait
{
    /*
    *  find the n closest locations
    *  @param float $lat latitude of the po+int of interest
    *  @param float $lng longitude of the point of interest
    *  @param integer $max_distance max distance to search our from
    *  @param integer $max_locations max number of locations to return
    *  @param string $units miles|kilometers
    *  @return array
    */
    static public function closestRider($order, $lat, $lng, $max_distance = 25, $units = 'kilometers')
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
                "u.*,( %d * acos( cos( radians(%s) ) " .
                        " * cos( radians( user_address.latitude ) ) " .
                        " * cos( radians( user_address.longitude ) - radians(%s) ) " .
                        " + sin( radians(%s) ) * sin( radians( user_address.latitude ) ) " .
                    ") " .
                ") " .
                "AS distance",
                $gr_circle_radius,
                $lat,
                $lng,
                $lat
            );
        return DB::table('user_address')
            ->rightJoin('orders as o', 'user_address.id', '=', 'o.address_id')
            ->select(DB::raw($disctance_select) )
            ->whereNotNull('o.address_id')
            ->having('distance', '<=', $max_distance )
            ->orderBy('distance', 'ASC' )
            ->groupBy('u.user_id')
            ->get();
    }
}
