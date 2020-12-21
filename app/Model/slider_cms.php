<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Exception;

class slider_cms extends Model
{
    public function getSlider($data)
    {
        try {
            $notification_data = DB::table('slider_cms')
                ->where('slider_type', $data['slider_type'])
                ->where('user_id', $data['user_id'])
                ->where('visibility', 0)
                ->orderBy('listing_order', 'desc')
                ->get();

            return $notification_data;
        } catch (Exception $e) {
            dd($e);
        }
    }
    public function getMediaAttribute($value)
    {
        if ($value) {
            if(env('APP_ENV')=='staged'){
                $path =
            }else{
                $path = url()
            }
            $path = public_path($value);
            if (file_exists($path)) {
                return url($path);
            } else {
                return null;
            }
        }
    }
}
