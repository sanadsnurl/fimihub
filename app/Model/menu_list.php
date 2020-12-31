<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class menu_list extends Model
{
    protected $table = 'menu_list';

    public function makeMenu($data)
    {
        $count=DB::table('menu_list')->max('listing_order');
        $unique_id=$count+1;
        $data['listing_order']=$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('menu_list')->insertGetId($data);
        return $query_data;
    }

    public function menuPaginationData($data)
    {
        $menu_list=DB::table('menu_list')
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.visibility', 0)
        ->where('menu_list.restaurent_id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
        ->orderBy('name');

        return $menu_list;

    }

    public function menuCategory($data)
    {
        $menu_list=DB::table('menu_list')
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->distinct('menu_list.menu_category_id')
        ->where('menu_list.visibility', 0)
        ->where('menu_list.restaurent_id', $data)
        ->select('mc.id as cat_id','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
        ->orderBy('cat_name')
        ->get();

        return $menu_list;

    }

    public function menuList($data)
    {
        $menu_list=DB::table('menu_list')
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.visibility', 0)
        ->where('menu_list.restaurent_id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
        ->orderBy('cat_name')
        ->get();

        return $menu_list;

    }

    public function menuListById($data)
    {
        $menu_list=DB::table('menu_list')
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.visibility', 0)
        ->where('menu_list.id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','menu_categories.discount as cat_discount','mc.id as cat_id')
        ->orderBy('cat_name')
        ->first();

        return $menu_list;

    }

    public function orderMenuListById($data)
    {
        $menu_list=DB::table('menu_list')
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
        ->orderBy('cat_name')
        ->first();

        return $menu_list;

    }

    public function menuListByQuantity($data)
    {
        $cart_exist = DB::table('carts')
        ->where('carts.restaurent_id', $data['restaurent_id'])
        ->where('carts.user_id', $data['user_id'])
        ->where('carts.visibility', 0);

        if($cart_exist->count() == 0)
        {
            $menu_list=DB::table('menu_list')
            ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
            ->leftJoin('menu_categories', function($join) use ($data)
                            {
                                $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                            })
                    ->where('menu_list.visibility', 0)
                    ->where('menu_list.restaurent_id', $data['restaurent_id'])
                    ->select('menu_list.*','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
                    ->orderBy('cat_name')
                    ->get();
        }
        else
        {
            $cart_exist = $cart_exist->first();
            $data['cart_exist_id'] = $cart_exist->id;
            // dd($data['cart_exist_id']);
            $menu_list = DB::table('menu_list')
                    ->leftJoin('cart_submenus', function($join) use ($data)
                        {
                            $join->on('cart_submenus.menu_id', '=', 'menu_list.id');
                            $join->where('cart_submenus.user_id', $data['user_id']);
                            $join->where('cart_submenus.cart_id',  $data['cart_exist_id']);
                            $join->where('cart_submenus.visibility', 0);

                        })
                    ->where('menu_list.restaurent_id', $data['restaurent_id'])
                    ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
                    ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
                    ->where('menu_list.visibility', 0)
                    ->select('menu_list.*','cart_submenus.quantity as quantity','menu_categories.name as cat_name','menu_categories.discount as cat_discount')
                    ->get();
        }

        return $menu_list;

    }

    public function deleteMenu($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('menu_customizations')
            ->where('menu_list_id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        $query_data = DB::table('menu_list')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function editMenu($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('menu_list')
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }

}
