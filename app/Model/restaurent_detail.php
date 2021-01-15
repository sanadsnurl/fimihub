<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class restaurent_detail extends Model
{
    public function insertUpdateRestoData($data)
    {
        $value=DB::table('restaurent_details')->where('user_id', $data['user_id'])->get();
        if($value->count() == 0)
        {
            $count=DB::table('restaurent_details')->max('id');
            $unique_id=100001+$count;
            $data['resto_id']='FIMIRESTO'.$unique_id;
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('restaurent_details')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('restaurent_details')
                        ->where('user_id', $data['user_id'])
                        ->update($data);
        }

        return $query_data;
    }

    public function getRestoData($userid)
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                ->where('visibility', 0)
                ->where('user_id', $userid)
                ->first();

            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getRestoDataOnId($userid)
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                ->where('visibility', 0)
                ->where('id', $userid)
                ->first();

            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }
    public function getallRestoData()
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                ->where('visibility', 0)
                ->orderBy('name')
                ->limit(6)
                ->get();

            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getallRestaurant()
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                ->where('visibility', 0)
                ->orderBy('name');

            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function deleteRestaurent($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('restaurent_details')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function getallRestaurantWithMenu()
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                                ->leftJoin('menu_list', function($join)
                                                {
                                                $join->on('menu_list.restaurent_id', '=', 'restaurent_details.id');
                                                $join->where('menu_list.visibility', 0);

                                                })
                                ->limit(6)
                                ->select('restaurent_details.*', DB::raw('COUNT(menu_list.restaurent_id) AS dish_count'))
                                ->where('restaurent_details.visibility', 0)
                                ->orderBy('restaurent_details.name')
                                ->having('dish_count', '>', 0)
                                ->groupBy('menu_list.restaurent_id')
                                ->get();


            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getallCatRestaurantWithMenu($data)
    {
        try {
            $restaurent_details=DB::table('restaurent_details')
                                ->leftJoin('menu_list', function($join)
                                                {
                                                $join->on('menu_list.restaurent_id', '=', 'restaurent_details.id');
                                                $join->where('menu_list.visibility', 0);

                                                })
                                ->limit(6)
                                ->select('restaurent_details.*', DB::raw('COUNT(menu_list.restaurent_id) AS dish_count'))
                                ->where('restaurent_details.visibility', 0)
                                ->where('restaurent_details.resto_type', $data)
                                ->orderBy('restaurent_details.name')
                                ->having('dish_count', '>', 0)
                                ->groupBy('menu_list.restaurent_id')
                                ->get();

            return $restaurent_details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }


    public function restroAddress()
    {
        return $this->belongsTo(user_address::class, 'user_id', 'user_id');
    }

    public function getPriceAttribute($value)
    {
        if(in_array(request()->segment(1),['Restaurent', 'admifimihub','api'])) {
            return $value;
        } else {
            return $value +(( DB::table('service_catagories')->where('service_catagories.id', 1)->first()->commission / 100) * $value);
        }
    }
}
