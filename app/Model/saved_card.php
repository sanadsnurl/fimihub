<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Exception;

class saved_card extends Model
{
    protected $appends =['person_card_number'];
    public function makeSaveCards($data)
    {

        $value = $this
            ->where('card_number', $data['card_number'])
            ->where('user_id', $data['user_id'])
            ->where('visibility', 0);

        if ($value->count() == 0) {

            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = $this->insert($data);
            $query_type = "insert";
        } else {
            $query_data = -1;
        }

        return $query_data;
    }

    public function getUserCardList($user_id){
        $query = $this
                ->where('user_id',$user_id)
                ->where('visibility',0)
                ->get();

        return $query;
    }
    public function getCardById($data){
        $query = DB::table('saved_cards')
        ->where('id',$data['id'])
        ->where('user_id',$data['user_id'])
        ->where('visibility',0)
        ->first();

        return $query;
    }
    public function getPersonNameAttribute($value)
    {
        return ucfirst($value);
    }

    public function getCardNumberAttribute($value)
    {
        $card_explode =  substr(base64_decode($value),0,4);

        return $card_explode." XXXX XXXX XXXX";
    }
    public function getPersonCardNumberAttribute()
    {
        // dd($this->attributes['card_number']);
        return base64_decode($this->attributes['card_number']);
    }

}
