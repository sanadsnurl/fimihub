<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;
use Exception;

class ServiceCategory extends Model
{
    protected $table = 'service_catagories';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'listing_order',
        'visibility',
        'deleted_at',
    ];

    public function getServiceCategory($id = false) {
        $query = $this->where('visibility', 0)->whereNull('deleted_at');
        if($id) {
            $query = $query->where('id', $id);
        }
        return $query;
    }

    public function getAllServices() {
        $order_data=DB::table('service_catagories')
                ->where('visibility', 0);

            return $order_data;
    }

    public function getServiceById($id) {
        $order_data=DB::table('service_catagories')
                ->where('visibility', 0)
                ->where('id', $id)
                ->first();

            return $order_data;
    }

    public function editService($data)
    {
        $data['updated_at'] = now();
        unset($data['_token']);

        $query_data = DB::table('service_catagories')
            ->where('id', $data['id'])
            ->update($data);

        return $query_data;
    }


}
