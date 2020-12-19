<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

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
}
