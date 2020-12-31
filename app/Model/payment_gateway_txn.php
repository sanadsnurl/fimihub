<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class payment_gateway_txn extends Model
{
    public function insertUpdateTxn($data)
    {
        $value=DB::table('payment_gateway_txns')->where('order_id', $data['order_id'])
                                    ->get();
        if($value->count() == 0)
        {
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('payment_gateway_txns')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('payment_gateway_txns')
                        ->where('user_id', $data['user_id'])
                        ->where('order_id', $data['order_id'])
                        ->update($data);
        }

        return $query_data;

    }
}
