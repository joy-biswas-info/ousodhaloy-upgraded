<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['phone', 'code', 'purpose', 'is_used', 'expires_at'];
    protected $casts = ['is_used' => 'boolean', 'expires_at' => 'datetime'];
    public function isValid(): bool
    {
        return !$this->is_used && $this->expires_at->isFuture();
    }
}
