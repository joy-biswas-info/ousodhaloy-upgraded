<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'status',
        'payment_status',
        'payment_method',
        'ssl_transaction_id',
        'ssl_val_id',
        'subtotal',
        'delivery_charge',
        'discount',
        'tax',
        'total',
        'promo_code',
        'shipping_name',
        'shipping_phone',
        'shipping_email',
        'shipping_division',
        'shipping_district',
        'shipping_upazila',
        'shipping_address',
        'shipping_postcode',
        'pathao_order_id',
        'pathao_consignment_id',
        'pathao_status',
        'pathao_tracking_code',
        'steadfast_consignment_id',
        'steadfast_tracking_code',
        'steadfast_status',
        'customer_note',
        'admin_note',
        'prescription_image',
        'estimated_delivery_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_charge' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    const STATUS_FLOW = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['processing', 'cancelled'],
        'processing' => ['ready_to_ship', 'cancelled'],
        'ready_to_ship' => ['shipped', 'cancelled'],
        'shipped' => ['out_for_delivery', 'returned'],
        'out_for_delivery' => ['delivered', 'returned'],
        'delivered' => ['refunded', 'returned'],
        'cancelled' => [],
        'refunded' => [],
        'returned' => ['refunded'],
        'on_hold' => ['processing', 'cancelled'],
    ];

    const STATUS_LABELS = [
        'pending' => 'Pending',
        'confirmed' => 'Confirmed',
        'processing' => 'Processing',
        'ready_to_ship' => 'Ready to Ship',
        'shipped' => 'Shipped',
        'out_for_delivery' => 'Out for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
        'on_hold' => 'On Hold',
        'returned' => 'Returned',
    ];

    const STATUS_COLORS = [
        'pending' => 'yellow',
        'confirmed' => 'blue',
        'processing' => 'indigo',
        'ready_to_ship' => 'cyan',
        'shipped' => 'purple',
        'out_for_delivery' => 'orange',
        'delivered' => 'green',
        'cancelled' => 'red',
        'refunded' => 'gray',
        'on_hold' => 'gray',
        'returned' => 'red',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function getCustomerNameAttribute(): string
    {
        return $this->user?->name ?? $this->guest_name ?? $this->shipping_name;
    }

    public function getCustomerPhoneAttribute(): string
    {
        return $this->user?->phone ?? $this->guest_phone ?? $this->shipping_phone;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABELS[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'gray';
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, self::STATUS_FLOW[$this->status] ?? []);
    }

    public function scopeForCustomer($q, $userId, $phone = null)
    {
        return $q->where(function ($sub) use ($userId, $phone) {
            $sub->where('user_id', $userId);
            if ($phone)
                $sub->orWhere('guest_phone', $phone)->orWhere('shipping_phone', $phone);
        });
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = self::generateOrderNumber();
            }
        });
    }

    public static function generateOrderNumber(): string
    {
        do {
            $num = 'OUS-' . date('ymd') . '-' . strtoupper(Str::random(4));
        } while (self::where('order_number', $num)->exists());
        return $num;
    }
}
