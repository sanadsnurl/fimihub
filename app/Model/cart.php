<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use App\Model\cart_submenu as subCart;

class cart extends Model
{
    // protected $table = 'carts';
    public function makeCart($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('carts')->insertGetId($data);
        return $query_data;
    }

    public function checkCartAvaibility($data)
    {
        try {
            $carts=DB::table('carts')
                ->where('visibility', 0)
                ->where('user_id', $data)
                ->first();

            return $carts;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getCartData($data)
    {
        try {
            $carts=DB::table('carts')
                ->where('id', $data)
                ->first();

            return $carts;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function updateCart($data)
    {
        try {
            $carts=DB::table('carts')
                ->where('visibility', 0)
                ->where('id', $data['id'])
                ->update($data);

            return $carts;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function deleteCart($data)
    {
        $cart_delete = array();
        $cart_delete ['deleted_at'] = now();
        $cart_delete ['user_id'] = $data;

        $query_data = DB::table('carts')
                    ->where('user_id', $cart_delete['user_id'])
                    ->update(['visibility'=>2]);
        $query_type="update";
    }

    public function deleteCartAndMenu($data){
        $cart_delete = array();
        $cart_delete ['deleted_at'] = now();
        $cart_delete ['user_id'] = $data;

        $query_data = DB::table('carts')
            ->where('user_id', $cart_delete['user_id'])
            ->update(['visibility'=>2]);
        $query_data = DB::table('cart_submenus')
            ->where('user_id', $cart_delete['user_id'])
            ->update(['visibility'=>2]);
        $query_data = DB::table('cart_customizations')
            ->where('user_id', $cart_delete['user_id'])
            ->update(['visibility'=>2]);

        return $query_data;
    }

    public function cartItems()
    {
        return $this->hasMany(subCart::class, 'cart_id');
    }

    public function menuItems()
    {
        return $this->hasMany(subCart::class, 'menu_id');
    }
}
