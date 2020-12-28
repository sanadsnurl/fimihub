<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class cart_customization extends Model
{
    public function getCartCustomData($data)
    {
        $add_on_list=DB::table('cart_customizations')
                    ->join('menu_list as ml', 'ml.id', '=', 'cart_customizations.menu_id')
                    ->where('cart_customizations.visibility', 0)
                    ->where('cart_customizations.user_id', $data['user_id'])
                    ->where('cart_customizations.cart_id', $data['cart_id'])
                    ->select('ml.*','cart_customizations.quantity as quantity')
                    ->orderBy('listing_order','DESC');

        return $add_on_list;

    }

    public function getCartCustomDataBySubMenu($data)
    {
        $add_on_list=DB::table('cart_customizations')
                    ->join('menu_list as ml', 'ml.id', '=', 'cart_customizations.menu_id')
                    ->where('cart_customizations.visibility', 0)
                    ->where('cart_customizations.user_id', $data['user_id'])
                    ->where('cart_customizations.cart_id', $data['cart_id'])
                    ->where('cart_customizations.custom_id', $data['custom_id'])
                    ->select('ml.*','cart_customizations.quantity as quantity')
                    ->orderBy('listing_order','DESC');

        return $add_on_list;

    }

    public function makeCustomCartSubMenu($data)
    {

        $value=DB::table('cart_customizations')
                ->where('menu_id', $data['menu_id'])
                ->where('user_id', $data['user_id'])
                ->where('cart_id', $data['cart_id'])
                ->where('custom_id', $data['custom_id'])
                ->where('visibility', 0);

        if($value->count() == 0)
        {

            $data['quantity']=1;
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('cart_customizations')->insert($data);
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
            $query_data = DB::table('cart_customizations')
                        ->where('menu_id', $data['menu_id'])
                        ->where('user_id', $data['user_id'])
                        ->where('cart_id', $data['cart_id'])
                        ->where('custom_id', $data['custom_id'])
                        ->update($data);
        }

        return $query_data;
    }

    public function getCartCustomDataByID($data)
    {
        $add_on_list=DB::table('cart_customizations')
                    ->join('menu_list as ml', 'ml.id', '=', 'cart_customizations.menu_id')
                    ->where('cart_customizations.visibility', 0)
                    ->where('cart_customizations.custom_id', $data)
                    ->select('ml.*','cart_customizations.quantity as quantity')
                    ->orderBy('listing_order','DESC');

        return $add_on_list;

    }

    public function removeCustomCartSubMenu($data)
    {

        $value=DB::table('cart_customizations')
                ->where('menu_id', $data['menu_id'])
                ->where('user_id', $data['user_id'])
                ->where('cart_id', $data['cart_id'])
                ->where('custom_id', $data['custom_id'])
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
                $query_data = DB::table('cart_customizations')
                        ->where('menu_id', $data['menu_id'])
                        ->where('user_id', $data['user_id'])
                        ->where('cart_id', $data['cart_id'])
                        ->where('custom_id', $data['custom_id'])
                        ->update($data);
            }else{
                $data['quantity']= 0;
                $data['updated_at'] = now();
                $data['visibility'] = 2;
                unset($data['_token']);
                $query_data = DB::table('cart_customizations')
                            ->where('menu_id', $data['menu_id'])
                            ->where('user_id', $data['user_id'])
                            ->where('cart_id', $data['cart_id'])
                            ->where('custom_id', $data['custom_id'])
                            ->update($data);
            }

        }else{
            $query_data=0;
        }

        return $query_data;
    }


}
