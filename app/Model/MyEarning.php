<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;
use Carbon\Carbon;


class MyEarning extends Model
{
    /**
        * The attributes that are mass assignable.
        *
        * @var array
        */
    protected $fillable = [
        'user_id',
        'order_id',
        'ride_price',
        'resto_commission',
        'cash_price',
        'is_active',
    ];

    public function orders() {
        return $this->belongsTo(order::class, 'order_id');
    }

    public function updateEarning($data, $orderId = false) {
        $id = Auth::id();
        $earning = $this->where('order_id', $orderId)->where('user_id', $id)->first();
        if(empty($earning)) {
            $earning = $this->create($data);
        } else {
            unset($data['order_id']);
            unset($data['user_id']);
            $earning = $earning->update($data);
        }

        return $earning;
    }

    public function getMyEarning($userId, $earningId = false) {
        $query = $this->where('user_id', $userId)->where('is_active', 1);
        if($earningId) {
            $query = $query->where('id', $earningId);
        }
        return $query;
    }

    public function getMyEarningOnOrder($userId) {
        $query = $this
                    ->leftJoin('orders', function($join)
                        {
                        $join->on('orders.id', '=', 'my_earnings.order_id');
                        $join->where('orders.visibility', 0);

                        })
                    ->where('my_earnings.user_id', $userId)
                    ->where('is_active', 1)
                    ->select('orders.*', 'my_earnings.ride_price as order_earning');
                    return $query;
    }
    /**
     * 1. Week
     * 2. Month
     * 3. Year
     * 4. Between two dates
     * 5. all
     */

    public function getMyEarningByWeekMonthYear($userId, $type = false, $startDate = false, $endDate = false) {
        $query = $this->where('is_active', 1);
        if($userId) {
            $query = $query->where('user_id', $userId);
        }

        if($type == 1) {
            return $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
        } else if ($type == 2) {
            return $query->whereMonth('created_at', date('m'))
            ->whereYear('created_at', date('Y'));
        } else if($type == 3) {
            return $query->whereYear('created_at', date('Y'));
        } else if($type == 4){
            if ($startDate && $endDate) {
                return $query->whereBetween('created_at', [$startDate,  $endDate]);
            }
        } else if($type == 6){
            return $query->whereDate('created_at', Carbon::today());
        } else {
            return $query;
        }
    }

    public function getMyTotalEarning($userId) {
        $query = $this
                    ->leftJoin('orders', function($join)
                        {
                        $join->on('orders.id', '=', 'my_earnings.order_id');
                        $join->where('orders.visibility', 0);

                        })
                    ->where('my_earnings.user_id', $userId)
                    ->where('is_active', 1)
                    ->select(DB::raw('SUM(my_earnings.ride_price) as order_earning'))
                    ->first();
                    return $query;
    }

    public function getMyEarningOnOrderResto($userId) {
        $query = $this
                    ->Join('orders', function($join) use($userId)
                        {
                        $join->on('orders.id', '=', 'my_earnings.order_id');
                        $join->where('orders.visibility', 0);
                        $join->where('orders.restaurent_id', $userId);

                        })
                    ->where('is_active', 1)
                    ->select('orders.*', 'my_earnings.ride_price as order_earning', 'my_earnings.resto_commission as resto_earning');
                    return $query;
    }

    public function getMyTotalEarningResto($userId) {
        $query = $this
                    ->Join('orders', function($join) use($userId)
                        {
                        $join->on('orders.id', '=', 'my_earnings.order_id');
                        $join->where('orders.visibility', 0);
                        $join->where('orders.restaurent_id', $userId);
                        })
                    ->where('is_active', 1)
                    ->select(DB::raw('SUM(my_earnings.resto_commission) as resto_earning'),
                    DB::raw('SUM((orders.total_amount - orders.delivery_fee) - ((orders.total_amount - orders.delivery_fee)/ (1 + (orders.service_tax / 100)))) as cgt_tax'))
                    ->first();
                    return $query;
    }


}
