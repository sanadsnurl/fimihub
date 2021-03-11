<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

use function GuzzleHttp\json_decode;

class menu_list extends Model
{
    public $table = 'menu_list';


    public function makeMenu($data)
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

    public function menuPaginationData($data)
    {
        $menu_list=$this
            ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
            ->leftJoin('menu_categories', function($join) use ($data)
                            {
                                $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                            })
            ->where('menu_list.visibility', 0)
            ->where('menu_categories.visibility', 0)
            ->where('menu_list.restaurent_id', $data)
            ->select('menu_list.*','menu_categories.name as cat_name')
            ->orderBy('name');

        return $menu_list;

    }

    public function getMenuPaginationData($data)
    {
        $menu_list=$this
            ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
            ->leftJoin('menu_categories', function($join) use ($data)
                            {
                                $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                            })
            ->whereIn('menu_list.visibility', [0,1])
            ->where('menu_categories.visibility', 0)
            ->where('menu_list.restaurent_id', $data)
            ->select('menu_list.*','menu_categories.name as cat_name')
            ->orderBy('name');

        return $menu_list;

    }

    public function menuCategory($data)
    {
        $menu_list=$this
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->distinct('menu_list.menu_category_id')
        ->where('menu_categories.visibility', 0)
        ->where('menu_list.visibility', 0)
        ->where('menu_list.restaurent_id', $data)
        ->select('mc.id as cat_id','menu_categories.name as cat_name')
        ->orderBy('cat_name')
        ->get();

        return $menu_list;

    }

    public function menuList($data)
    {
        $menu_list=$this
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.visibility', 0)
        ->where('menu_categories.visibility', 0)
        ->where('menu_list.restaurent_id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name')
        ->orderBy('cat_name')
        ->get();

        return $menu_list;

    }

    public function menuListById($data)
    {
        $menu_list=$this
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.visibility', 0)
        ->where('menu_categories.visibility', 0)
        ->where('menu_list.id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','mc.id as cat_id')
        ->orderBy('cat_name')
        ->first();

        return $menu_list;

    }

    public function menuListByIdWithBlock($data)
    {
        $menu_list=$this
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->whereIn('menu_list.visibility', [0,1])
        ->where('menu_categories.visibility', 0)
        ->where('menu_list.id', $data)
        ->select('menu_list.*','menu_categories.name as cat_name','mc.id as cat_id')
        ->orderBy('cat_name')
        ->first();

        return $menu_list;

    }

    public function orderMenuListById($data)
    {
        $menu_list=$this
        ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
        ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
        ->where('menu_list.id', $data)
        ->where('menu_categories.visibility', 0)
        ->select('menu_list.*','menu_categories.name as cat_name')
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
            $menu_list=$this
            ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
            ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
                    ->where('menu_list.visibility', 0)
                    ->where('menu_categories.visibility', 0)
                    ->where('menu_list.restaurent_id', $data['restaurent_id'])
                    ->select('menu_list.*','menu_categories.name as cat_name')
                    ->orderBy('cat_name')
                    ->get();
        }
        else
        {
            $cart_exist = $cart_exist->first();
            $data['cart_exist_id'] = $cart_exist->id;
            // dd($data['cart_exist_id']);
            $menu_list = $this
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
                    ->where('menu_categories.visibility', 0)
                    ->select('menu_list.*',
                    'cart_submenus.quantity as quantity',
                    'cart_submenus.product_variant_id as cart_variant_id',
                    'cart_submenus.product_add_on_id as product_adds_id',
                    'menu_categories.name as cat_name')
                    ->get();
        }

        return $menu_list;

    }

    public function menuListByQuantityById($data)
    {
        $cart_exist = DB::table('carts')
        ->where('carts.restaurent_id', $data['restaurent_id'])
        ->where('carts.user_id', $data['user_id'])
        ->where('carts.visibility', 0);

        if($cart_exist->count() == 0)
        {
            $menu_list=$this
            ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
            ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');


                        })
                    ->where('menu_list.visibility', 0)
                    ->where('menu_categories.visibility', 0)
                    ->where('menu_list.restaurent_id', $data['restaurent_id'])
                    ->select('menu_list.*','menu_categories.name as cat_name')
                    ->orderBy('cat_name')
                    ->get();
        }
        else
        {
            $cart_exist = $cart_exist->first();
            $data['cart_exist_id'] = $cart_exist->id;
            // dd($data['cart_exist_id']);
            $menu_list = $this
                    ->Join('cart_submenus', function($join) use ($data)
                        {
                            $join->on('cart_submenus.menu_id', '=', 'menu_list.id');
                            $join->where('cart_submenus.user_id', $data['user_id']);
                            $join->where('cart_submenus.cart_id',  $data['cart_exist_id']);
                            $join->where('cart_submenus.menu_id',  $data['menu_id']);
                            $join->where('cart_submenus.visibility', 0);

                        })
                    ->where('menu_list.restaurent_id', $data['restaurent_id'])
                    ->join('resto_menu_categories as mc', 'mc.id', '=', 'menu_list.menu_category_id')
                    ->leftJoin('menu_categories', function($join) use ($data)
                        {
                            $join->on('menu_categories.id', '=', 'mc.menu_category_id');

                        })
                    ->where('menu_list.visibility', 0)
                    ->where('menu_categories.visibility', 0)
                    ->select('menu_list.*',
                    'cart_submenus.quantity as quantity',
                    'cart_submenus.product_variant_id as cart_variant_id',
                    'cart_submenus.product_add_on_id as product_adds_id',
                    'menu_categories.name as cat_name')
                    ->get();
        }

        return $menu_list;

    }

    public function deleteMenu($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function visibilityOffOnOfDish($data, $visibility)
    {
        // $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update(['visibility'=> $visibility]);

        return $query_data;
    }

    public function editMenu($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }

    public function getNameAttributes($value)
    {
        return ucfirst($value);
    }
    public function getProductAddOnIDAttributes($value)
    {
        return json_decode($value);
    }
    public function getPriceAttribute($value)
    {
        if(in_array(request()->segment(1),['Restaurent', 'admifimihub'])) {
            return $value;
        } else {
            return $value +(( DB::table('service_catagories')->where('service_catagories.id', 1)->first()->commission / 100) * $value);
        }

    }

    public function getPriceOnlyAttribute($value)
    {
        return $this->price;
        // return $value +(( DB::table('service_catagories')->where('service_catagories.id', 1)->first()->commission / 100) * $value);

    }





}
