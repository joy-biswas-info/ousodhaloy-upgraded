<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = ['user_id', 'guest_phone', 'image', 'status', 'admin_note', 'order_id'];
    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}
