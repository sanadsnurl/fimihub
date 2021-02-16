<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
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
     * ============ Restaurent status ================
     * 1. Accept
     * 2. Reject
     * 3. Packed
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
        'order_comment',
        'order_feedback',
        'feedback_comment',
        'reason_id',
        'user_type',
        'visibility',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    public function updateStatus($orderId, $data) {
        $id = Auth::id();
        $orderEvent = $this->where('order_id', $orderId)->where('user_type', 1)->where('user_id', $id)->first();
        if(empty($orderEvent)) {
            $orderEvent = $this->create($data);
        } else {
            unset($data['order_id']);
            unset($data['user_id']);
            $orderEvent = $orderEvent->update($data);
        }

        return $orderEvent;
    }

    public function orderAlreadyAssigned($orderId) {
        $userId = Auth::id();
        return $this->where('order_id', $orderId)->where('user_type', 1)->where('user_id', '!=', $userId);
    }

    public function makeOrderEvent($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
        unset($data['_token']);
        $query_data = DB::table('order_events')->insertGetId($data);
        return $query_data;
    }

    public function makeUpdateOrderEvent($data)
    {
        $value=DB::table('order_events')->where('user_id', $data['user_id'])
                                    ->where('order_id', $data['order_id'])
                                    ->where('user_type', 2)
                                    ->get();
        if($value->count() == 0)
        {
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('order_events')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('order_events')
                        ->where('user_id', $data['user_id'])
                        ->where('order_id', $data['order_id'])
                        ->where('user_type', 2)
                        ->update($data);
        }

        return $query_data;
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);

    }

    public function getOrderEvent($data)
    {
        try {
            $user_address=DB::table('order_events')
                ->where('visibility', 0)
                ->where('order_id', $data)
                ->get();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function updateOrderEvent($data)
    {
        try {
            $query_data = DB::table('order_events')
                        ->where('id', $data['id'])
                        ->update($data);

            return $query_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getOrderEventRatingData($data)
    {
        try {
            $query_data = DB::table('order_events')
                        ->where('user_id', $data['user_id'])
                        ->where('user_type', $data['user_type'])
                        ->where('visibility', 0)
                        ->whereNotNull('order_feedback')
                        ->select(DB::raw('Count(order_feedback) AS rating_count , Round(AVG(order_feedback),1) AS rating'));

            return $query_data;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

}
