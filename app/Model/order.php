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
use Auth;

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
     */
    public function makeOrder($data)
    {
        $count=$this->max('id');
        $unique_id=10000000001+$count;
        $data['order_id']='FF'.$unique_id;
        $data['updated_at'] = now();
        $data['created_at'] = now();
        unset($data['_token']);
        $query_data = $this->insertGetId($data);
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
                // ->where('oe.user_id', Auth::id());
            })->select('orders.*')
            ->whereNull('oe.order_id')->orderBy('orders.id', 'DESC')->groupBy('orders.id');
        }
        // dd($query->toSql());
        return $query;
    }

    public function getActiveOrders($orderId = false) {
        $query = $this;
        if($orderId) {
            $query = $this->where('orders.id', $orderId)
            ->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')->where('oe.user_type', 1);
            })
            ->where('oe.user_id', Auth::id())

            ->select('orders.*');
        } else {
            //->leftjoin('order_event_controls as oec', 'orders.id', '=', 'oec.order_id')
            $query = $this->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')->where('oe.user_type', 1);
            })->select('orders.*')
            ->where(function($query) {
                $query->orWhere('oe.order_status', 1)
                ->orWhere('oe.order_status', 2)
                ->orWhere('oe.order_status', 3)
                ->orWhere('oe.order_status', 4);
            })
            ->whereNotNull('oe.order_id')
            ->where('oe.user_id', Auth::id())
            ->orderBy('orders.id', 'DESC')->groupBy('orders.id');
        }
        return $query;
    }

    public function getMyPreviusOrders($orderId = false)
    {
        $query = $this;
        if($orderId) {
            $query = $this->where('orders.id', $orderId)
            ->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')->where('oe.user_type', 1);
            })
            ->where('oe.user_id', Auth::id())
            ->select('orders.*');
        } else {
            $query = $this
            ->leftjoin('order_events as oe',function($query){
                $query->on('orders.id', '=', 'oe.order_id')->where('oe.user_type', 1);
            })
            ->select('orders.*')
            ->where('oe.user_id', Auth::id())
            ->where(function($query) {
                $query->orWhere('orders.order_status', 8)
                ->orWhere('orders.order_status', 9);
            })
            ->orderBy('orders.id', 'DESC')->groupBy('orders.id');
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
        return $this->belongsTo(user_address::class, 'user_id');
    }
    public function orderEvent()
    {
        return $this->hasOne(OrderEvent::class, 'order_id')->where('user_type', 1);
    }

    public function updateStatus($orderId, $status){
        return $this->where('id', $orderId)->update(['order_status'=> $status]);
    }

    public function updateOrderStatus($orderId, $status){
        return $this->where('id', $orderId)->update(['order_status'=> $status]);
    }

    public function updatePaymentStatus($orderId, $payment_status){
        if($payment_status == 3){
            return $this->where('id', $orderId)->update(['payment_status'=> $payment_status,'order_status'=> 1]);
        }else{
            return $this->where('id', $orderId)->update(['payment_status'=> $payment_status]);
        }
    }

    public function getOrderData($order_id)
    {
        try {
            $order_data=$this
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
            $order_data=$this
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
            $order_data=$this
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

    public function customerOrderPaginationData($data)
    {
        $menu_list=$this
                ->where('orders.visibility', 0)
                ->where('orders.payment_status',2)
                ->where('orders.restaurent_id', $data)
                ->select('orders.*')
                ->orderBy('orders.created_at','DESC');

        return $menu_list;

    }

    public function allOrderPaginationData()
    {
        $menu_list=$this->select('orders.*')
                ->where('orders.visibility', 0)
                ->orderBy('orders.created_at','DESC');

        return $menu_list;

    }


    public function allOrderPaginationDataByID($order_id)
    {
        $menu_list=$this->select('orders.*')
                ->where('orders.id', $order_id)
                ->orderBy('orders.created_at','DESC');

        return $menu_list;

    }
    public function getDeliveryAtAttribute($value)
    {
        $delivery_time = strtotime("+40 minutes", strtotime($this->created_at));
        return date('h:i', $delivery_time);


    }

    public function deleteCustomerOrder($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('orders')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);
        $query_data = DB::table('order_events')
            ->where('order_id', $data['id'])
            ->update(['visibility'=> 2,'deleted_at' => $data['deleted_at']]);
        $query_data = DB::table('my_earnings')
            ->where('order_id', $data['id'])
            ->update(['is_active'=> 2,'updated_at' => $data['deleted_at']]);

        return $query_data;
    }

    public function allUserOrderPastDataApp($user_id)
    {
        try {
            $order_data=$this
                ->join('restaurent_details as rd', 'rd.id', '=', 'orders.restaurent_id')
                ->where('orders.visibility', 0)
                ->whereIn('orders.order_status', [1,2,4,8,9,10])
                ->where('orders.user_id', $user_id)
                ->select('orders.*','rd.name as resto_name','rd.address as resto_address','rd.picture as resto_picture')
                ->orderBy('orders.created_at' ,'DESC');

            return $order_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function allUserCurrentAppOrderData($user_id)
    {
        try {
            $order_data=$this
                ->join('restaurent_details as rd', 'rd.id', '=', 'orders.restaurent_id')
                ->where('orders.visibility', 0)
                ->whereIn('orders.order_status', [3,5,6,7])
                ->where('orders.user_id', $user_id)
                ->select('orders.*','rd.name as resto_name','rd.address as resto_address','rd.picture as resto_picture')
                ->orderBy('orders.created_at' ,'DESC');

            return $order_data;
        }
        catch (Exception $e) {
            dd($e);
        }

    }
}
