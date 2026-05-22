<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoyaltyPoint extends Model
{
    protected $fillable = ['user_id', 'points', 'type', 'description', 'order_id', 'expires_at'];
    protected $casts = ['expires_at' => 'datetime'];
}
