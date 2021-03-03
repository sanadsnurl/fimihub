<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Auth;

class payment_method extends Model
{
    public function getPaymentMethodList($user_id){
        $query = $this
                ->where('visibility',0);

        return $query;
    }
}
