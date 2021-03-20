<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class Cms extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['content', 'heading', 'is_active', 'type'];


    public function getCms(int $type = 0, $isActive = 1) {
        $query = $this;
        if($type) {
            return $query->where('type', $type)->where('is_active', $isActive);
        }
        if($isActive) {
            return $query->where('is_active', $isActive);
        }
    }

    public function makeFaq($data)
    {
        $data['updated_at'] = now();
        $data['created_at'] = now();
        unset($data['_token']);
        $query_data = DB::table('cms')->insertGetId($data);
        return $query_data;
    }

    public function deleteCms($data)
    {
        $data['deleted_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('cms')
            ->where('id', $data['id'])
            ->update(['is_active'=> 2]);

        return $query_data;
    }
}
