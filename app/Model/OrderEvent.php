<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Auth;

class OrderEvent extends Model
{
    /**
     * ============ Rider status ================
     * 1. Arriving to store
     * 2. Arrived at store
     * 3. Order Picked Up
     * 4. On the way
     * 5. Delivered
     * 6. Rejected
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'order_id',
        'user_id',
        'order_status',
        'order_commment',
        'order_feedback',
        'feedback_comment',
        'user_type',
        'visibility',
        'deleted_at',
        'created_at',
        'updated_at',
    ];


    public function updateStatus($orderId, $data) {
        $id = Auth::id();
        $orderEvent = $this->where('order_id', $orderId)->where('user_id', $id)->first();
        if(empty($orderEvent)) {
            $orderEvent = $this->create($data);
        } else {
            $orderEvent = $orderEvent->update($data);
        }

        return $orderEvent;
    }

}
