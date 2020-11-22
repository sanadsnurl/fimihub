<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use App\Model\cart;
use App\Model\cart_submenu;
use App\Model\restaurent_detail;
use App\Model\user_address;
use App\User;

class order extends Model
{
    /**
     * ============ order status ==============
     * 1-failed
     * 2-user_cancel
     * 3-pending
     * 4-resto_cancel
     * 5-placed
     * 6-packed
     * 7-picked
     * 8-rider_cancel
     * 9-received
     * 10-refunded
     * 11-assigned to rider
     * 12-rider on the way
     */
    public function makeOrder($data)
    {
        $count=DB::table('orders')->max('id');
        $unique_id=10000000001+$count;
        $data['order_id']='FF'.$unique_id;
        unset($data['_token']);
        $query_data = DB::table('orders')->insertGetId($data);
        return $query_data;
    }

    public function getOrder($orderId = false)
    {
        $query = $this;
        if($orderId) {
            $query = $this->where('orders.id', $orderId)
            ->select('orders.*');
        } else {
            $query = $this->where(function($query) {
                $query->orWhere('orders.order_status', 6)->orWhere('orders.order_status', 5);
            })
            ->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')
                ->where('oe.user_type', 1);
            })->select('orders.*')
            ->whereNull('oe.order_id')->orderBy('orders.order_id', 'DESC')->groupBy('orders.id');
        }
        return $query;
    }

    public function cart()
    {
        return $this->belongsTo(cart::class, 'cart_id');
    }

    public function restaurentDetails()
    {
        return $this->belongsTo(restaurent_detail::class, 'restaurent_id');
    }

    public function userAddress()
    {
        return $this->belongsTo(user_address::class, 'address_id');
    }

    public function restroAddress()
    {
        return $this->belongsTo(user_address::class, 'restaurent_id');
    }

    public function updateStatus($orderId, $status) {
        return $this->where('id', $orderId)->update(['order_status'=> $status]);

    public function getOrderData($order_id)
    {
        try {
            $order_data=DB::table('orders')
                ->where('visibility', 0)
                ->where('id', $order_id)
                ->first();
            
            return $order_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function allUserOrderPastData($user_id)
    {
        try {
            $order_data=DB::table('orders')
                ->join('restaurent_details as rd', 'rd.id', '=', 'orders.restaurent_id')
                ->where('orders.visibility', 0)
                ->whereIn('orders.order_status', [1,2,4,8,9,10])
                ->where('orders.user_id', $user_id)
                ->select('orders.*','rd.name as resto_name','rd.address as resto_address','rd.picture as resto_picture')
                ->orderBy('orders.created_at' ,'DESC')
                ->paginate(3);

            return $order_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function allUserCurrentPastData($user_id)
    {
        try {
            $order_data=DB::table('orders')
                ->join('restaurent_details as rd', 'rd.id', '=', 'orders.restaurent_id')
                ->where('orders.visibility', 0)
                ->whereIn('orders.order_status', [3,5,6,7])
                ->where('orders.user_id', $user_id)
                ->select('orders.*','rd.name as resto_name','rd.address as resto_address','rd.picture as resto_picture')
                ->orderBy('orders.created_at' ,'DESC')
                ->get();

            return $order_data;
        }
        catch (Exception $e) {
            dd($e);
        }

    }
}
