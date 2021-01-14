<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
//custom import
use Illuminate\Support\Facades\DB;

class EnvSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'key',
        'type',
        'value',
    ];

    static public function getEnvVar()
    {
        try {
            $env_data = self::all();

            return $env_data;
        } catch (\Exception $e) {
            dd($e);
        }
    }

}
