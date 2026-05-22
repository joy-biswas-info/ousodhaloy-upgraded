<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
{
    protected $fillable = ['name', 'slug', 'logo', 'country', 'description', 'is_active', 'sort_order'];
    protected $casts = ['is_active' => 'boolean'];
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
    public function getLogoUrlAttribute(): string
    {
        return $this->logo ? asset('storage/' . $this->logo) : '';
    }
}
