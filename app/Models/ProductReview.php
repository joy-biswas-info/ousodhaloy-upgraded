<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class ProductReview extends Model
{
    protected $fillable = ['product_id', 'user_id', 'order_item_id', 'rating', 'title', 'body', 'is_approved'];
    protected $casts = ['is_approved' => 'boolean', 'rating' => 'integer'];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
