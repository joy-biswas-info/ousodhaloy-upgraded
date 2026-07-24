<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'order_item_id',
        'rating',
        'title',
        'body',
        'is_approved',
        'reviewer_name',
        'source',
        'reviewed_at',
    ];
    protected $casts = [
        'is_approved' => 'boolean',
        'rating' => 'integer',
        'reviewed_at' => 'datetime',
    ];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Imported reviews have no linked user account — fall back to the stored name.
    public function getDisplayNameAttribute(): string
    {
        return $this->user?->name ?? $this->reviewer_name ?? 'Verified Customer';
    }

    public function getIsImportedAttribute(): bool
    {
        return $this->source === 'imported';
    }
}
