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

    // A courier consignment can only be cancelled here before the physical
    // package has actually been picked up — once it has, cancelling in-app
    // doesn't stop the courier, it just desyncs our status from reality (see
    // updateStatus()'s existing confirm_courier_cancel warning for the
    // related "already pushed to courier" case). These are the stages that
    // precede pickup.
    const PRE_PICKUP_STATUSES = ['pending', 'confirmed', 'processing', 'ready_to_ship'];

    // The subset of STATUS_FLOW targets an admin may pick from the manual
    // "Update Status" dropdown. Deliberately excludes ready_to_ship (set only
    // by the "Push to Pathao/Steadfast" action), shipped/out_for_delivery/
    // delivered/returned (courier-driven only, via mapCourierStatus() /
    // webhook / polling) — those are shown read-only (status badge + the
    // courier's own raw status panel) rather than offered as manual choices.
    // refunded stays admin-only since no courier ever reports it.
    const ADMIN_EDITABLE_STATUSES = ['confirmed', 'processing', 'on_hold', 'cancelled', 'refunded'];

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
        if (!in_array($newStatus, self::STATUS_FLOW[$this->status] ?? [], true)) {
            return false;
        }

        // STATUS_FLOW['on_hold'] lists every stage a hold could have started
        // from, as a static superset — it can't know which one *this* order
        // was actually held from. Narrow it here: resuming must go back to
        // exactly held_from_status (not any other listed stage), and
        // cancelling from hold is still subject to the pickup rule below.
        if ($this->status === 'on_hold') {
            return $newStatus === 'cancelled'
                ? $this->canCancel()
                : $newStatus === $this->held_from_status;
        }

        return true;
    }

    /**
     * Whether this order can still be cancelled — only true before the
     * courier has actually picked up the package. For an order currently on
     * hold, that's determined by the stage it was held *from*, not the fact
     * that it's on_hold right now (on_hold itself carries no pickup
     * information — see STATUS_FLOW's comment on why it's reachable from
     * every active stage).
     */
    public function canCancel(): bool
    {
        $effectiveStatus = $this->status === 'on_hold'
            ? ($this->held_from_status ?? 'pending')
            : $this->status;

        return in_array($effectiveStatus, self::PRE_PICKUP_STATUSES, true);
    }

    /**
     * Statuses to offer in the admin's manual "Update Status" dropdown right
     * now — the intersection of what the state machine allows (STATUS_FLOW,
     * via canTransitionTo()) and what's meant to be admin-settable at all
     * (ADMIN_EDITABLE_STATUSES). Courier-driven stages (ready_to_ship via the
     * push-to-courier action, shipped/out_for_delivery/delivered/returned via
     * webhook/polling) are deliberately never offered here — they're shown
     * read-only via status_label + the courier's raw status panel instead.
     */
    public function adminSelectableStatuses(): array
    {
        if ($this->status === 'on_hold') {
            return array_values(array_filter([
                $this->held_from_status,
                $this->canCancel() ? 'cancelled' : null,
            ]));
        }

        return array_values(array_intersect(
            self::STATUS_FLOW[$this->status] ?? [],
            self::ADMIN_EDITABLE_STATUSES
        ));
    }

    /**
     * Single source of truth for courier raw-status → internal-status.
     * Union of what used to be 4 separate, drifting copies (PathaoService
     * polling, SteadfastService polling, and both webhook handlers) — Pathao's
     * polling and webhook endpoints use genuinely different raw vocabularies
     * (Picked_Up vs Pickup_Completed), so both are kept rather than assumed
     * to be duplicates.
     *
     * Pathao's raw value is normalized (lowercased, non-alphanumeric runs
     * collapsed to a single underscore) before lookup, so "Pickup Requested",
     * "pickup_requested", and "PICKUP-REQUESTED" all hit the same key — we
     * only have Pathao's dashboard documentation for the full status list
     * (not a captured real payload for every one of them), so matching on
     * normalized text is more robust than betting on exact casing. Steadfast's
     * map is untouched — its raw values are already confirmed from real
     * payloads and compared as-is.
     */
    public static function mapCourierStatus(string $courier, string $raw): ?string
    {
        if ($courier === 'pathao') {
            $normalized = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '_', $raw), '_'));

            $map = [
                // Pre-pickup
                'pickup_requested' => 'ready_to_ship',
                'assigned_for_pickup' => 'ready_to_ship',
                'pickup_failed' => 'on_hold',
                'pickup_cancelled' => 'on_hold',
                // Picked up / in transit — no finer internal stage than
                // "shipped" exists between pickup and out-for-delivery, so
                // every in-transit variant collapses to it. The precise raw
                // value still shows to the admin via pathao_status.
                'picked_up' => 'shipped',
                'pickup_completed' => 'shipped',
                'in_transit' => 'shipped',
                'at_the_sorting_hub' => 'shipped',
                // Out for delivery
                'assigned_for_delivery' => 'out_for_delivery',
                'out_for_delivery' => 'out_for_delivery',
                // Delivered
                'delivered' => 'delivered',
                'delivery_completed' => 'delivered',
                'partial_delivery' => 'delivered',
                // Needs admin attention, but still pre-pickup outcomes above
                // handle their own on_hold cases — this one is post-attempt
                'delivery_failed' => 'on_hold',
                'on_hold' => 'on_hold',
                // Cancelled
                'cancelled' => 'cancelled',
                'delivery_cancelled' => 'cancelled',
                // Return (any stage) — no finer internal stage than
                // "returned" exists for the return sub-flow either
                'return' => 'returned',
                'return_picked_up' => 'returned',
                'return_completed' => 'returned',
                'paid_return' => 'returned',
                'return_id_created' => 'returned',
                'return_in_transit' => 'returned',
                'returned_to_merchant' => 'returned',
            ];

            return $map[$normalized] ?? null;
        }

        $maps = [
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
