<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
}
