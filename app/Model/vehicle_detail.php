<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class vehicle_detail extends Model
{
    public function insertUpdateVehicleData($data)
    {
        $value=DB::table('vehicle_details')->where('user_id', $data['user_id'])->get();
        if($value->count() == 0)
        {
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('vehicle_details')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('vehicle_details')
                        ->where('user_id', $data['user_id'])
                        ->update($data);
        }

        return $query_data;
    }

    public function getVehicleData($userid)
    {
        try {
            $user_address=DB::table('vehicle_details')
                ->where('visibility', 0)
                ->where('user_id', $userid)
                ->first();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }


}
