<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class menu_categories extends Model
{
    public function makeMenuCategory($data)
    {
        $count=DB::table('menu_categories')->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('menu_categories')->insertGetId($data);
        return $query_data;
    }

    public function editMenuCategory($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('menu_categories')
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }

    public function restaurentCategoryPaginationData()
    {
        $menu_categories=DB::table('menu_categories')
        ->where('visibility', 0);


        return $menu_categories;

    }
    public function deleteMainCategory($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        $query_data = DB::table('resto_menu_categories')
            ->where('menu_category_id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        // $query_data = DB::table('menu_list')
        //     ->where('menu_category_id', $data['id'])
        //     ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

}
