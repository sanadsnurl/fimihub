<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;
use function GuzzleHttp\json_decode;

class menu_custom_list extends Model
{
    protected $table = 'menu_custom_list';

    public function makeCustomMenu($data)
    {
        $count=$this->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = $this->insertGetId($data);
        return $query_data;
    }

    public function menuCustomPaginationData($data)
    {
        $menu_custom_list=$this
        ->join('resto_custom_menu_categories as mc', 'mc.id', '=', 'menu_custom_list.resto_custom_cat_id')
        ->leftJoin('custom_menu_categories', function($join) use ($data)
                        {
                            $join->on('custom_menu_categories.id', '=', 'mc.custom_cat_id');

                        })
        ->where('menu_custom_list.visibility', 0)
        ->where('menu_custom_list.restaurent_id', $data)
        ->select('menu_custom_list.*','custom_menu_categories.name as cat_name');

        return $menu_custom_list;

    }

    public function menuCustomCategoryData($data)
    {
        $menu_custom_list=$this
        ->join('resto_custom_menu_categories as mc', 'mc.id', '=', 'menu_custom_list.resto_custom_cat_id')
        ->leftJoin('custom_menu_categories', function($join) use ($data)
                        {
                            $join->on('custom_menu_categories.id', '=', 'mc.custom_cat_id');

                        })
        ->groupBy('mc.custom_cat_id')
        ->where('menu_custom_list.visibility', 0)
        ->where('menu_custom_list.restaurent_id', $data)
        ->select('custom_menu_categories.name as cat_name')
        ->orderBy('custom_menu_categories.name');

        return $menu_custom_list;
    }

    public function getCustomListPrice($value)
    {
        $menu_custom_list=DB::table('menu_custom_list')
                ->where('id',$value)
                ->where('visibility',0)
                ->first();

        return $menu_custom_list;


    }
    public function getCustomListPriceWithPer($value)
    {
        $menu_custom_list=$this
                ->where('id',$value)
                ->where('visibility',0)
                ->first();

        return $menu_custom_list;

    }

    public function getPriceAttribute($value)
    {
        return $value +(( DB::table('service_catagories')->where('service_catagories.id', 1)->first()->tax / 100) * $value);

    }
}
