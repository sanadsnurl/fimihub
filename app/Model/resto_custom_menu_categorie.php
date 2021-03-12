<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use App\Model\custom_menu_categorie;
use Auth;

class resto_custom_menu_categorie extends Model
{
    public function makeRestoMenuCustomCategory($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('resto_custom_menu_categories')->insertGetId($data);
        return $query_data;
    }

    public function restaurentCategoryCustomData($data)
    {
        $menu_categories=DB::table('resto_custom_menu_categories')
        ->join('custom_menu_categories as mc', 'mc.id', '=', 'resto_custom_menu_categories.custom_cat_id')
        ->where('resto_custom_menu_categories.visibility', 0)
        ->where('mc.visibility', 0)
        ->where('resto_custom_menu_categories.restaurent_id', $data)
        ->select('resto_custom_menu_categories.*','mc.name as cat_name','mc.id as cat_id','resto_custom_menu_categories.id as cats_id');


        return $menu_categories;

    }
    public function getCustomCatByIds($id){
        try {
            $details=DB::table('resto_custom_menu_categories')
                ->where('visibility', 0)
                ->where('id', $id)
                ->get();

            return $details;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function editCustomCat($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }

    public function deleteCustomCat($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('resto_custom_menu_categories')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);
        $query_data = DB::table('menu_custom_list')
            ->where('resto_custom_cat_id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function customCat() {
        return $this->belongsTo(custom_menu_categorie::class, 'custom_cat_id', 'id');
    }

    public function productVariant() {
        return $this->belongsTo(custom_menu_categorie::class, 'custom_cat_id', 'id');
    }
}
