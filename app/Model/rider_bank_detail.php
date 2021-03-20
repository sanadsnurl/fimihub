<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use App\User;

class rider_bank_detail extends Model
{
    public function insertUpdateBankData($data)
    {
        $value=DB::table('rider_bank_details')->where('user_id', $data['user_id'])->get();
        if($value->count() == 0)
        {
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('rider_bank_details')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('rider_bank_details')
                        ->where('user_id', $data['user_id'])
                        ->update($data);
        }

        return $query_data;
    }

    public function getBankData($userid)
    {
        try {
            $user_address=DB::table('rider_bank_details')
                ->where('visibility', 0)
                ->where('user_id', $userid)
                ->first();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }


}
