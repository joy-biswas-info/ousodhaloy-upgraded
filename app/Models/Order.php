<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_number',
        'user_id',
        'landing_page_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'status',
        'held_from_status',
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
        'courier',
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

    // 'on_hold' is reachable from every active pipeline state — a courier can
    // signal "hold" (see Order::mapCourierStatus()) at any point before the
    // order is delivered/cancelled/refunded/returned, not just pre-shipment.
    const STATUS_FLOW = [
        'pending' => ['confirmed', 'cancelled', 'on_hold'],
        'confirmed' => ['processing', 'cancelled', 'on_hold'],
        'processing' => ['ready_to_ship', 'cancelled', 'on_hold'],
        'ready_to_ship' => ['shipped', 'cancelled', 'on_hold'],
        'shipped' => ['out_for_delivery', 'returned', 'on_hold'],
        'out_for_delivery' => ['delivered', 'returned', 'on_hold'],
        'delivered' => ['refunded', 'returned'],
        'cancelled' => [],
        'refunded' => [],
        'returned' => ['refunded'],
        // Wider than the entrance list on purpose — on_hold can be entered
        // from any active pipeline state (see above), so resuming needs to
        // reach back to whichever stage the order actually left off at, not
        // just "processing". held_from_status (set in OrderService::
        // updateStatus()) tells the admin which one that was.
        'on_hold' => ['processing', 'ready_to_ship', 'shipped', 'out_for_delivery', 'cancelled'],
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
    public function landingPage(): BelongsTo
    {
        return $this->belongsTo(LandingPage::class);
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

    /**
     * Single source of truth for courier raw-status → internal-status.
     * Union of what used to be 4 separate, drifting copies (PathaoService
     * polling, SteadfastService polling, and both webhook handlers) — Pathao's
     * polling and webhook endpoints use genuinely different raw vocabularies
     * (Picked_Up vs Pickup_Completed), so both are kept rather than assumed
     * to be duplicates.
     */
    public static function mapCourierStatus(string $courier, string $raw): ?string
    {
        $maps = [
            'pathao' => [
                'Delivered' => 'delivered',
                'Cancelled' => 'cancelled',
                'Picked_Up' => 'shipped',
                'Out_For_Delivery' => 'out_for_delivery',
                'In_Transit' => 'shipped',
                'Return_Picked_Up' => 'returned',
                'Pickup_Completed' => 'shipped',
                'Delivery_Completed' => 'delivered',
                'Delivery_Cancelled' => 'cancelled',
                'Return_Completed' => 'returned',
            ],
            'steadfast' => [
                'delivered' => 'delivered',
                'partial_delivered' => 'delivered',
                'cancelled' => 'cancelled',
                'hold' => 'on_hold',
                'delivered_approval_pending' => 'delivered',
                'partial_delivered_approval_pending' => 'delivered',
                'cancelled_approval_pending' => 'cancelled',
            ],
        ];

        return $maps[$courier][$raw] ?? null;
    }

    /**
     * Signed link to the customer-facing order page for a guest order (no
     * user_id to check ownership against). See Shop/OrderController::show()
     * — this is the only way a guest order is reachable, so every place that
     * hands a customer their confirmation link should use this, not route().
     */
    public function signedShowUrl(): string
    {
        return \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'orders.show',
            now()->addDays(30),
            ['id' => $this->id]
        );
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
        // withTrashed() matters here: order_number has a DB-level unique constraint
        // that a soft-deleted (trashed) row still occupies, so checking only
        // non-trashed rows could generate a number that collides at INSERT time.
        do {
            $num = 'OUS-' . date('ymd') . '-' . strtoupper(Str::random(4));
        } while (self::withTrashed()->where('order_number', $num)->exists());
        return $num;
    }
}
