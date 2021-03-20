<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class custom_menu_categorie extends Model
{
    public function makeMenuCustomCategory($data)
    {
        $count=DB::table('custom_menu_categories')->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('custom_menu_categories')->insertGetId($data);
        return $query_data;
    }

    public function restaurentCategoryCustomPaginationData()
    {
        $custom_menu_categories=DB::table('custom_menu_categories')
        ->where('visibility', 0);


        return $custom_menu_categories;

    }
    public function getCustomCategory($id){
        try {
            $details=DB::table('custom_menu_categories')
                ->where('visibility', 0)
                ->where('id', $id)
                ->get();

            return $details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }
}
