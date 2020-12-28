<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class menu_customization extends Model
{
    public function makeCustomization($data)
    {
        $count=DB::table('menu_customizations')->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('menu_customizations')->insertGetId($data);
        return $query_data;
    }

    public function getAddOnData($data)
    {
        $add_on_list=DB::table('menu_customizations')
                    ->join('menu_list as ml', 'ml.id', '=', 'menu_customizations.menu_list_id')
                    ->where('menu_customizations.visibility', 0)
                    ->where('menu_customizations.menu_list_id', $data)
                    ->select('menu_customizations.*','ml.name as menu_name')
                    ->orderBy('listing_order','DESC');

        return $add_on_list;

    }

    public function customizationById($data)
    {
        $add_on_list=DB::table('menu_customizations')
                    ->where('visibility', 0)
                    ->where('id', $data)
                    ->orderBy('listing_order','DESC');

        return $add_on_list;

    }

    public function deleteCustomization($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('menu_customizations')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function editCustomization($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('menu_customizations')
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }
}
