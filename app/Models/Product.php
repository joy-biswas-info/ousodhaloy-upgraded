<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany};
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'barcode',
        'generic_name',
        'brand_id',
        'category_id',
        'thumbnail',
        'images',
        'price',
        'mrp',
        'discount_percent',
        'stock',
        'low_stock_alert',
        'min_order_qty',
        'max_order_qty',
        'unit',
        'pack_size',
        'strength',
        'form',
        'requires_prescription',
        'is_active',
        'is_featured',
        'is_flash_sale',
        'flash_sale_price',
        'flash_sale_ends_at',
        'express_delivery',
        'custom_delivery_charge',
        'delivery_charge_per_unit',
        'tabs',
        'tags',
        'meta_title',
        'meta_description',
        'views',
        'total_sold',
        'average_rating',
        'rating_count',
    ];

    protected $casts = [
        'images' => 'array',
        'tabs' => 'array',
        'tags' => 'array',
        'requires_prescription' => 'boolean',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_flash_sale' => 'boolean',
        'express_delivery' => 'boolean',
        'custom_delivery_charge' => 'decimal:2',
        'delivery_charge_per_unit' => 'boolean',
        'flash_sale_ends_at' => 'datetime',
        'price' => 'decimal:2',
        'mrp' => 'decimal:2',
        'flash_sale_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'average_rating' => 'decimal:2',
    ];

    // ── Relationships ─────────────────────────────────────────
    // Primary category (kept for backward compat — shop filters, breadcrumbs)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // All categories via pivot
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_product')
            ->withPivot('is_primary');
    }
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ── Accessors ─────────────────────────────────────────────
    public function getEffectivePriceAttribute(): float
    {
        if ($this->is_flash_sale && $this->flash_sale_price && $this->flash_sale_ends_at?->isFuture()) {
            return (float) $this->flash_sale_price;
        }
        return (float) $this->price;
    }

    public function getDiscountAmountAttribute(): float
    {
        return $this->mrp ? (float) ($this->mrp - $this->effective_price) : 0;
    }

    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->mrp || $this->mrp <= $this->effective_price)
            return 0;
        return (int) (($this->mrp - $this->effective_price) / $this->mrp * 100);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }
    public function getIsLowStockAttribute(): bool
    {
        return $this->stock > 0 && $this->stock <= $this->low_stock_alert;
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/no-image.png');
    }

    public function getImageUrlsAttribute(): array
    {
        if (!$this->images)
            return [];
        return array_map(fn($img) => asset('storage/' . $img), $this->images);
    }

    // ── Scopes ────────────────────────────────────────────────
    public function scopeActive($q)
    {
        return $q->where('is_active', true);
    }
    public function scopeFeatured($q)
    {
        return $q->where('is_featured', true)->active();
    }
    public function scopeFlashSale($q)
    {
        return $q->where('is_flash_sale', true)->active();
    }
    public function scopeInStock($q)
    {
        return $q->where('stock', '>', 0);
    }
    public function scopeLowStock($q)
    {
        return $q->where('stock', '<=', DB::raw('low_stock_alert'))->where('stock', '>', 0);
    }
    public function scopeOutOfStock($q)
    {
        return $q->where('stock', 0);
    }
    public function scopeSearch($q, $term)
    {
        return $q->where(function ($sub) use ($term) {
            $sub->where('name', 'like', "%{$term}%")
                ->orWhere('generic_name', 'like', "%{$term}%")
                ->orWhere('sku', 'like', "%{$term}%")
                ->orWhere('tags', 'like', "%{$term}%");
        });
    }

    // ── Helpers ───────────────────────────────────────────────
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function decrementStock(int $qty): void
    {
        $this->decrement('stock', $qty);
        $this->increment('total_sold', $qty);
    }

    public function restoreStock(int $qty): void
    {
        $productId = $this->id;
        DB::transaction(function () use ($qty, $productId) {
            $product = Product::lockForUpdate()->findOrFail($productId);
            $product->increment('stock', $qty);
            $product->update([
                'total_sold' => max(0, $product->total_sold - $qty)
            ]);

        });

    }

    public function updateRating(): void
    {
        $stats = $this->reviews()->where('is_approved', true)
            ->selectRaw('AVG(rating) as avg, COUNT(*) as cnt')
            ->first();
        $this->update([
            'average_rating' => round($stats->avg ?? 0, 2),
            'rating_count' => $stats->cnt ?? 0,
        ]);
    }
}