<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class contactUs extends Model
{
    public function makeContactUs($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
            unset($data['_token']);
        $query_data = DB::table('contact_us')->insertGetId($data);
        return $query_data;
    }
}
