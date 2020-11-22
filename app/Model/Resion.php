<?php

namespace App\Model;

use Exception;
use Illuminate\Database\Eloquent\Model;

class Resion extends Model
{
    /**
     * ========== Resion ============
     * 1-Admin
     * 2-Rider
     * 3-User
     * 4-Restaurent
     */

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'resion',
        'is_active',
        'user_type'
    ];

    public function getResions($userType = 0, $isActive = 1) {
        try {
            return $this->where('user_type', $userType)->where('is_active', $isActive);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
