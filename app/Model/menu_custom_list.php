<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;

class menu_custom_list extends Model
{
    protected $table = 'menu_custom_list';

    public function makeCustomMenu($data)
    {
        $count=DB::table('menu_custom_list')->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('menu_custom_list')->insertGetId($data);
        return $query_data;
    }

    public function menuCustomPaginationData($data)
    {
        $menu_custom_list=DB::table('menu_custom_list')
        ->join('resto_custom_menu_categories as mc', 'mc.id', '=', 'menu_custom_list.resto_custom_cat_id')
        ->leftJoin('custom_menu_categories', function($join) use ($data)
                        {
                            $join->on('custom_menu_categories.id', '=', 'mc.custom_cat_id');

                        })
        ->where('menu_custom_list.visibility', 0)
        ->where('menu_custom_list.restaurent_id', $data)
        ->select('menu_custom_list.*','custom_menu_categories.name as cat_name')
        ->orderBy('name');

        return $menu_custom_list;

    }
}
