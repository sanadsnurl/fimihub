<?php

namespace App\Model;

use App\Http\Traits\MediaUploadTrait;
use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Exception;

class slider_cms extends Model
{
    use MediaUploadTrait;
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
            $path = public_path($value);
            // dd($path);
            if (file_exists($path)) {
                return checkEnv($value);
            } else {
                return NULL;
            }
        }
        return NULL;
    }



    public function getallSlider($data)
    {
        try {
            $notification_data = DB::table('slider_cms')
                ->where('slider_type', $data['slider_type'])
                ->where('visibility', 0)
                ->orderBy('listing_order', 'desc');

            return $notification_data;
        } catch (Exception $e) {
            dd($e);
        }
    }

    public function makeSlider($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('slider_cms')->insertGetId($data);
        return $query_data;
    }

    public function getText1Attribute($value)
    {
        return ucfirst($value);
    }

    public function getText2Attribute($value)
    {
        return ucfirst($value);
    }
/**
    * Get the user's first name.
    *
    * @param  string  $value
    * @return string
    */

    public function getLinkAttribute($value)
    {
        return ucfirst($value);
    }
}
