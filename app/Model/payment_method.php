<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;

class payment_method extends Model
{
    public function getPaymentMethodList(){
        $query = $this
                ->where('visibility',0);

        return $query;
    }

    public function getLogoAttribute($value){
        if(!empty($value)){
            return asset($value);
        }
    }

    public function updatePaymentMethod($data){
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = $this
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }

}
