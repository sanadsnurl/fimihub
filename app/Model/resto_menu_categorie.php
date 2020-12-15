<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;

class resto_menu_categorie extends Model
{
    public function makeRestoMenuCategory($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('resto_menu_categories')->insertGetId($data);
        return $query_data;
    }

    public function restaurentCategoryData($data)
    {
        $menu_categories=DB::table('resto_menu_categories')
        ->join('menu_categories as mc', 'mc.id', '=', 'resto_menu_categories.menu_category_id')
        ->where('resto_menu_categories.visibility', 0)
        ->where('resto_menu_categories.restaurent_id', $data)
        ->select('resto_menu_categories.*','mc.name as cat_name','mc.id as cat_id','resto_menu_categories.id as cats_id');
        
    
        return $menu_categories;
    
    }
}