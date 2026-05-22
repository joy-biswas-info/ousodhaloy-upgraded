<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryZone extends Model
{
    protected $fillable = ['name', 'division', 'districts', 'delivery_charge', 'free_delivery_above', 'estimated_days', 'express_available', 'express_charge', 'is_active'];
    protected $casts = ['districts' => 'array', 'is_active' => 'boolean', 'express_available' => 'boolean'];
}
