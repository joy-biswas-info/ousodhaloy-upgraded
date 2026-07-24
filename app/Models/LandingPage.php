<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LandingPage extends Model
{
    protected $fillable = [
        'product_id',
        'slug',
        'status',
        'headline',
        'subheadline',
        'eyebrow_text',
        'badge_text',
        'hero_image',
        'price',
        'compare_at_price',
        'countdown_end_at',
        'theme',
        'sections',
        'meta_title',
        'meta_description',
        'shipping_note',
        'return_policy_note',
        'created_by',
    ];

    protected $casts = [
        'theme' => 'array',
        'sections' => 'array',
        'countdown_end_at' => 'datetime',
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'views' => 'integer',
    ];

    // Reserved top-level URL segments a landing page slug must never collide with —
    // the public catch-all route is registered last, so any of these would already
    // have matched their real route first and the landing page would just silently 404.
    public const RESERVED_SLUGS = [
        'admin', 'api', 'shop', 'cart', 'checkout', 'auth', 'account', 'payment',
        'webhooks', 'order', 'orders', 'track', 'search', 'buy-now', 'wishlist',
        'profile', 'privacy-policy', 'terms-of-use', 'return-policy', 'storage',
        'build', 'up', 'sanctum',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopePublished($q)
    {
        return $q->where('status', 'published');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price ?? $this->product?->effective_price ?? 0);
    }

    public function getEffectiveCompareAtPriceAttribute(): ?float
    {
        $val = $this->compare_at_price ?? $this->product?->mrp;
        return $val ? (float) $val : null;
    }

    public function getDiscountPercentAttribute(): int
    {
        $compare = $this->effective_compare_at_price;
        $price = $this->effective_price;
        if (!$compare || $compare <= $price) {
            return 0;
        }
        return (int) round(($compare - $price) / $compare * 100);
    }

    // A section is always an array shaped like ['enabled' => bool, 'items'|'text'|'images' => ...].
    // Missing/malformed entries fall back to disabled rather than throwing, since this data
    // is hand-edited via the admin form and must never be able to 500 the public page.
    public function section(string $key): array
    {
        $section = $this->sections[$key] ?? null;
        return is_array($section) ? $section : ['enabled' => false];
    }

    public function sectionEnabled(string $key): bool
    {
        return (bool) ($this->section($key)['enabled'] ?? false);
    }

    public static function defaultSections(): array
    {
        return [
            'problems' => ['enabled' => true, 'items' => []],
            'formula' => ['enabled' => true, 'items' => []],
            'benefits' => ['enabled' => true, 'items' => []],
            'how_to_use' => ['enabled' => true, 'items' => []],
            'ingredients' => ['enabled' => false, 'text' => '', 'caution' => ''],
            'reviews' => ['enabled' => true],
            'faq' => ['enabled' => true, 'items' => []],
            'gallery' => ['enabled' => false, 'images' => []],
            'trust_badges' => ['enabled' => true, 'items' => []],
        ];
    }

    public static function defaultTheme(): array
    {
        return ['accent' => '#B5566F', 'cta' => '#0D7674'];
    }
}
