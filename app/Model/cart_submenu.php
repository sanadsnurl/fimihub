<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use App\Model\menu_list as menuList;

class cart_submenu extends Model
{

    public function makeCartSubMenu($data)
    {
        
        $value=DB::table('cart_submenus')
                ->where('menu_id', $data['menu_id'])
                ->where('user_id', $data['user_id'])
                ->where('visibility', 0);

        if($value->count() == 0)
        {
            
            $data['quantity']=1;
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('cart_submenus')->insert($data);
            $query_type="insert";
            
        }
        else
        {
            $values = $value->first();
            $quantity =  $values->quantity;
            $quantity += 1;
            $data['quantity']= $quantity;
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('cart_submenus')
                        ->where('menu_id', $data['menu_id'])
                        ->where('user_id', $data['user_id'])
                        ->update($data);
        }
        
        return $query_data;
    }

    public function getCartValue($data)
    {
        try {
            $carts=DB::table('cart_submenus')
                ->where('visibility', 0)
                ->where('cart_id', $data['cart_id'])
                ->where('menu_id', $data['menu_id'])
                ->first();
            
            return $carts;
        }
        catch (Exception $e) {
            dd($e);
        }
    }


    public function removeCartSubMenu($data)
    {
        
        $value=DB::table('cart_submenus')
                ->where('menu_id', $data['menu_id'])
                ->where('user_id', $data['user_id'])
                ->where('visibility', 0);

        if($value->count() != 0)
        {
            
            $values = $value->first();
            $quantity =  $values->quantity;
            if($quantity >1){
                $quantity -= 1;
                $data['quantity']= $quantity;
                $data['updated_at'] = now();
                unset($data['_token']);
                $query_data = DB::table('cart_submenus')
                        ->where('menu_id', $data['menu_id'])
                        ->where('user_id', $data['user_id'])
                        ->update($data);
            }else{
                $data['quantity']= 0;
                $data['updated_at'] = now();
                $data['visibility'] = 2;
                unset($data['_token']);
                $query_data = DB::table('cart_submenus')
                ->where('menu_id', $data['menu_id'])
                ->where('user_id', $data['user_id'])
                ->update($data);
            }
            
        }else{
            $query_data=0;
        }
        
        return $query_data;
    }

    public function getCartMenuList($data)
    {
        try {
            $cart_menu_list = DB::table('cart_submenus')
                ->Join('menu_list', function($join) use ($data)
                {
                    $join->on('menu_list.id', '=', 'cart_submenus.menu_id');
                    $join->where('menu_list.visibility', 0);
                    $join->where('menu_list.restaurent_id', $data['restaurent_id']);
                    
                })
                ->where('cart_submenus.cart_id',  $data['cart_id'])
                ->where('cart_submenus.user_id',  $data['user_id'])
                ->where('cart_submenus.visibility', 0)
                ->select('menu_list.*','cart_submenus.quantity as quantity')
                ->get();
            return $cart_menu_list;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function menuItems()
    {
        return $this->belongsTo(menuList::class, 'menu_id');
    }
}
