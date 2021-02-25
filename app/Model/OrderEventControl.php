<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderEventControl extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'order_id',
        'status',
        'order_comment',
        'reason_id'
    ];
    public function orderAlreadyAssigned($orderId) {
        return $this->where('order_id', $orderId)->where('status', 1);
    }


    public function updateStatus($orderId, $data) {
        $id = Auth::id();
        $orderEventControl = $this->where('order_id', $orderId)->where('user_id', $id)->first();
        if(empty($orderEventControl)) {
            $orderEventControl = $this->create($data);
        } else {
            unset($data['order_id']);
            unset($data['user_id']);
            $orderEventControl = $orderEventControl->update($data);
        }

        return $orderEventControl;
    }

    public function orderEventControlDelete($orderId) {
        $userId = Auth::id();
        $orderDetails = $this->where('order_id', $orderId)->where('user_id', $userId)->where('status', 1)->first();
        if($orderDetails) {
            return $this->where('order_id', $orderId)->where('user_id', $userId)->where('status', 1)->delete();
        }
        return false;
    }
}
