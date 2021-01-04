<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class user_address extends Model
{
    protected $table = 'user_address';
    public function makeAddress($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('user_address')->insertGetId($data);
        return $query_data;
    }

    public function insertUpdateAddress($data)
    {
        $value=DB::table('user_address')->where('user_id', $data['user_id'])
                                    ->get();
        if($value->count() == 0)
        {
            $data['updated_at'] = now();
            $data['created_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('user_address')->insert($data);
            $query_type="insert";

        }
        else
        {
            $data['updated_at'] = now();
            unset($data['_token']);
            $query_data = DB::table('user_address')
                        ->where('user_id', $data['user_id'])
                        ->update($data);
        }

        return $query_data;

    }

    public function getUserAddress($userid)
    {
        try {
            $user_address=DB::table('user_address')
                ->where('visibility', 0)
                ->where('user_id', $userid)
                ->orderBy('default_status', 'asc')
                ->get();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getDefaultAddress($userid)
    {
        try {
            $user_address=DB::table('user_address')
                ->where('visibility', 0)
                ->where('default_status', 1)
                ->where('user_id', $userid)
                ->first();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function getAddressById($addid)
    {
        try {
            $user_address=DB::table('user_address')
                ->where('id', $addid)
                ->first();

            return $user_address;
        }
        catch (Exception $e) {
            dd($e);
        }
    }

    public function changeDefault($data)
    {

        $query_data = DB::table('user_address')
            ->where('user_id', $data['user_id'])
            ->update(['default_status'=> 2]);

        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('user_address')
            ->where('id', $data['id'])
            ->update(['default_status'=> 1]);

        return $query_data;
    }

    public function deleteAddress($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('user_address')
            ->where('id', $data['id'])
            ->update(['visibility'=> 2]);

        return $query_data;
    }

    public function userDetails()
    {
        return $this->belongsTo('App\User', 'user_id',);
    }
}
