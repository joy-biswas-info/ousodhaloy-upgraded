<?php
namespace App\Services;
use App\Models\PromoCodeUsage;
use App\Models\{DeliveryZone, Order, OrderItem, OrderStatusHistory, Product, PromoCode, Setting};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        private SmsService $sms,
        private MetaConversionsApiService $capi,
        private NotificationService $notification,
    ) {
    }

    /**
     * Create an order from cart data.
     */
    public function create(array $data, ?int $userId = null): Order
    {
        $order = DB::transaction(function () use ($data, $userId) {

            // 1. Validate items & calculate subtotal
            $orderItems = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::active()->lockForUpdate()->find($item['product_id']);

                // Surface a customer-facing reason instead of letting a raw
                // ModelNotFoundException ("No query results for model...")
                // reach the checkout error banner — this happens whenever a
                // cart item's product was deactivated/deleted between the
                // customer loading the page and submitting the form.
                if (!$product) {
                    throw new \Exception('One of the items in your cart is no longer available. Please review your cart and try again.');
                }

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Insufficient stock for: {$product->name}");
                }

                $price = $product->effective_price;
                $lineTotal = round($price * $item['qty'], 2);
                $subtotal += $lineTotal;

                $orderItems[] = [
                    'product' => $product,
                    'price' => $price,
                    'mrp' => $product->mrp,
                    'qty' => $item['qty'],
                    'subtotal' => $lineTotal,
                ];
            }

            // 2. Promo code
            $discount = 0;
            $promoCode = null;
            if (!empty($data['promo_code'])) {
                $promo = PromoCode::where('code', strtoupper($data['promo_code']))->first();
                if ($promo && $promo->isValid()) {
                    // Check per-user limit
                    if ($userId) {
                        $userUsage = PromoCodeUsage::where('promo_code_id', $promo->id)
                            ->where('user_id', $userId)->count();
                        if ($userUsage < $promo->per_user_limit) {
                            $discount = $promo->calculateDiscount($subtotal);
                            $promoCode = $promo->code;
                        }
                    } else {
                        $discount = $promo->calculateDiscount($subtotal);
                        $promoCode = $promo->code;
                    }
                }
            }

            // 3. Delivery charge — respects per-product custom charges
            $deliveryCharge = $this->calculateDeliveryForCart(
                $data['items'],
                $subtotal - $discount,
                $data['shipping_division'],
                $data['shipping_district']
            );

            $total = $subtotal + $deliveryCharge - $discount;

            // 4. Create order
            $order = Order::create([
                'user_id' => $userId,
                'landing_page_id' => $data['landing_page_id'] ?? null,
                'guest_name' => $userId ? null : ($data['guest_name'] ?? $data['shipping_name']),
                'guest_email' => $userId ? null : ($data['guest_email'] ?? null),
                'guest_phone' => $userId ? null : ($data['shipping_phone']),
                'status' => 'pending',
                'payment_status' => $data['payment_method'] === 'cod' ? 'unpaid' : 'pending',
                'payment_method' => $data['payment_method'],
                'subtotal' => $subtotal,
                'delivery_charge' => $deliveryCharge,
                'discount' => $discount,
                'total' => $total,
                'promo_code' => $promoCode,
                'shipping_name' => $data['shipping_name'],
                'shipping_phone' => $data['shipping_phone'],
                'shipping_email' => $data['shipping_email'] ?? null,
                'shipping_division' => $data['shipping_division'],
                'shipping_district' => $data['shipping_district'],
                'shipping_upazila' => $data['shipping_upazila'] ?? '',
                'shipping_address' => $data['shipping_address'],
                'shipping_postcode' => $data['shipping_postcode'] ?? null,
                'customer_note' => $data['notes'] ?? null,
                'prescription_image' => $data['prescription_image'] ?? null,
            ]);

            // 5. Create items & deduct stock
            foreach ($orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->name,
                    'product_sku' => $item['product']->sku,
                    'price' => $item['price'],
                    'mrp' => $item['mrp'],
                    'quantity' => $item['qty'],
                    'subtotal' => $item['subtotal'],
                ]);
                $item['product']->decrementStock($item['qty']);
            }

            // 6. Status history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'pending',
                'note' => 'Order placed',
                'changed_by' => 'customer',
            ]);

            // 7. Update promo usage
            if ($promoCode) {
                $promo = PromoCode::where('code', $promoCode)->first();
                $promo?->increment('used_count');
                PromoCodeUsage::create([
                    'promo_code_id' => $promo->id,
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'discount_amount' => $discount,
                ]);
            }

            // 8. Send confirmation SMS
            $this->sms->orderPlaced($order);

            // 9. Server-side Purchase (CAPI) — COD orders convert immediately
            // at creation, matching the client-side Purchase pixel that fires
            // on the order confirmation page. Online payments fire this from
            // SslCommerzService once payment is actually confirmed instead.
            if ($order->payment_method === 'cod') {
                $this->fireCapiPurchase($order);
            }

            return $order->fresh(['items']);
        });

        // Fired after the transaction commits — a push should never go out
        // for an order that ends up rolling back.
        $this->notification->newOrderPush($order);

        return $order;
    }

    /**
     * Fires the server-side Purchase CAPI event for a confirmed order — see
     * create() for COD, SslCommerzService::handleSuccess()/handleIpn() for
     * online payments. Uses a deterministic event_id keyed to the order
     * number so it matches the client-side Purchase pixel event fired on the
     * order confirmation page, letting Meta deduplicate the pair instead of
     * double-counting the conversion.
     */
    public function fireCapiPurchase(Order $order, ?string $fbp = null, ?string $fbc = null): void
    {
        if (Setting::get('meta_pixel_purchase', 'true') !== 'true') {
            return;
        }

        $req = request();
        $userData = $this->capi->hashUserData([
            'email' => $order->shipping_email ?? $order->guest_email,
            'phone' => $order->shipping_phone,
            'first_name' => Str::before($order->shipping_name, ' '),
            'last_name' => Str::contains($order->shipping_name, ' ') ? Str::after($order->shipping_name, ' ') : null,
            'city' => $order->shipping_district,
            'external_id' => $order->user_id,
        ]);
        if ($req) {
            $userData['client_ip_address'] = $req->ip();
            $userData['client_user_agent'] = $req->userAgent();
        }
        // fbp/fbc: passed explicitly for the SSLCommerz IPN path, where the
        // request is server-to-server (SSLCommerz's servers, not the
        // customer's browser) so request cookies aren't the customer's own —
        // see SslCommerzService, which threads these through via value_c/value_d.
        $fbp ??= $req?->cookie('_fbp');
        $fbc ??= $req?->cookie('_fbc');
        if ($fbp) $userData['fbp'] = $fbp;
        if ($fbc) $userData['fbc'] = $fbc;

        $this->capi->send(
            'Purchase',
            'purchase-' . $order->order_number,
            [
                'content_ids' => $order->items()->pluck('product_id')->toArray(),
                'content_name' => $order->items()->pluck('product_name')->implode(', '),
                'content_type' => 'product',
                'value' => (float) $order->total,
                'currency' => 'BDT',
                'num_items' => (int) $order->items()->sum('quantity'),
                'order_id' => $order->order_number,
            ],
            $userData,
            $req?->fullUrl()
        );
    }

    /**
     * Update order status with history + SMS.
     */
    public function updateStatus(Order $order, string $newStatus, string $note = '', bool $notifyCustomer = true): bool
    {
        if (!array_key_exists($newStatus, Order::STATUS_LABELS))
            return false;
        if ($order->status === $newStatus)
            return false;
        if (!$order->canTransitionTo($newStatus)) {
            logger()->warning("Rejected illegal order status transition: order #{$order->order_number} {$order->status} -> {$newStatus}");
            return false;
        }

        $order->update([
            'status' => $newStatus,
            'delivered_at' => $newStatus === 'delivered' ? now() : $order->delivered_at,
            // Remember which stage the order was at when it goes on_hold, so
            // it can resume there instead of always landing on "processing";
            // clear it once it actually leaves on_hold.
            'held_from_status' => $newStatus === 'on_hold' ? $order->status : ($order->status === 'on_hold' ? null : $order->held_from_status),
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'note' => $note ?: null,
            'changed_by' => Auth::user()?->name ?? 'system',
            'notify_customer' => $notifyCustomer,
        ]);

        // Restore stock on cancel or return
        if (in_array($newStatus, ['cancelled', 'returned'])) {
            foreach ($order->items as $item) {
                $item->product?->restoreStock($item->quantity);
            }
        }

        // SMS notification
        if ($notifyCustomer) {
            $this->sms->orderStatusUpdate($order, $newStatus);
        }

        return true;
    }

    /**
     * Undo a mistaken cancel/return — deliberately NOT part of STATUS_FLOW.
     * Cancelled/returned are terminal there on purpose (a webhook retry or a
     * bulk action must never be able to silently un-cancel an order and
     * re-desync stock); this is a separate, narrow, admin-only escape hatch
     * gated at the route level (see routes/web.php), not reachable from any
     * automated path. Always resumes to "processing" — both cancelled and
     * returned orders are, by STATUS_FLOW's own shape, pre- or
     * post-shipment states that need to go through fulfillment again before
     * they can ship. Refunded is intentionally excluded: undoing that would
     * desync payment_status from actual money already returned to the
     * customer, which isn't something to paper over with a status flip.
     */
    public function reopenOrder(Order $order, string $note = ''): array
    {
        if (!in_array($order->status, ['cancelled', 'returned'])) {
            return ['success' => false, 'error' => "Only cancelled or returned orders can be reopened — this order is \"{$order->status_label}\"."];
        }

        return DB::transaction(function () use ($order, $note) {
            // Check every line first — fail the whole reopen if any one
            // item can't be covered, rather than partially re-deducting
            // stock and leaving the order in a half-reopened state.
            foreach ($order->items as $item) {
                $product = $item->product()->lockForUpdate()->first();
                if (!$product) {
                    return ['success' => false, 'error' => "Can't reopen — \"{$item->product_name}\" no longer exists."];
                }
                if ($product->stock < $item->quantity) {
                    return ['success' => false, 'error' => "Can't reopen — only {$product->stock} of \"{$product->name}\" left in stock (needs {$item->quantity}). Its stock was released back to inventory when this order was {$order->status} and may have since sold elsewhere."];
                }
            }

            $previousStatus = $order->status;

            foreach ($order->items as $item) {
                $item->product?->decrementStock($item->quantity);
            }

            $order->update(['status' => 'processing', 'held_from_status' => null]);

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'processing',
                'note' => $note ?: "Reopened after mistaken \"{$previousStatus}\" — stock re-deducted.",
                'changed_by' => Auth::user()?->name ?? 'system',
                'notify_customer' => false,
            ]);

            return ['success' => true];
        });
    }

    /**
     * Calculate delivery charge based on location.
     */
    public function calculateDelivery(float $orderTotal, string $division, string $district): float
    {
        $zone = DeliveryZone::where('division', $division)
            ->whereJsonContains('districts', $district)
            ->where('is_active', true)
            ->first();

        $charge = $zone ? $zone->delivery_charge : (float) Setting::get('delivery_charge', 0);
        $freeAbove = $zone ? $zone->free_delivery_above : (float) Setting::get('free_delivery_min', 1000);

        return $orderTotal >= $freeAbove ? 0 : $charge;
    }

    /**
     * Calculate delivery charge considering per-product custom charges.
     * If ANY product in the cart has a custom_delivery_charge set, that
     * overrides the global rate for those items.
     *
     * Logic:
     *  - Items WITH custom charge: sum(custom_charge × qty if per_unit, else custom_charge)
     *  - Items WITHOUT custom charge: use the global zone/setting charge (one flat fee)
     *  - If ALL items have a custom charge, no global charge is added
     *  - Free-delivery threshold still applies to the global-charge portion
     */
    public function calculateDeliveryForCart(array $cartItems, float $orderTotal, string $division, string $district): float
    {
        $zone = DeliveryZone::where('division', $division)
            ->whereJsonContains('districts', $district)
            ->where('is_active', true)
            ->first();
        $globalCharge = $zone ? $zone->delivery_charge : (float) Setting::get('delivery_charge', 0);
        $freeAbove = $zone ? $zone->free_delivery_above : (float) Setting::get('free_delivery_min', 1000);

        $customTotal = 0.0;
        $hasNonCustom = false;

        foreach ($cartItems as $item) {
            $product = Product::find($item['product_id'] ?? $item['id'] ?? null);

            if ($product && $product->custom_delivery_charge !== null) {
                // Per-unit or flat custom charge
                $customTotal += $product->delivery_charge_per_unit
                    ? (float) $product->custom_delivery_charge * ($item['qty'] ?? $item['quantity'] ?? 1)
                    : (float) $product->custom_delivery_charge;
            } else {
                $hasNonCustom = true;
            }
        }

        // Global charge for non-custom items (respects free delivery threshold)
        $globalPart = $hasNonCustom
            ? ($orderTotal >= $freeAbove ? 0 : $globalCharge)
            : 0;

        return $customTotal + $globalPart;
    }

    /**
     * Validate a promo code for a given subtotal.
     */
    public function validatePromo(string $code, float $subtotal, ?int $userId = null): array
    {
        $promo = PromoCode::where('code', strtoupper($code))->first();

        if (!$promo || !$promo->isValid()) {
            return ['valid' => false, 'message' => 'Invalid or expired promo code'];
        }
        if ($subtotal < $promo->min_order) {
            return ['valid' => false, 'message' => "Minimum order amount ৳{$promo->min_order} required"];
        }
        if ($userId && $promo->first_order_only) {
            $hasOrders = Order::where('user_id', $userId)->where('status', '!=', 'cancelled')->exists();
            if ($hasOrders) {
                return ['valid' => false, 'message' => 'This code is for first orders only'];
            }
        }

        $discount = $promo->calculateDiscount($subtotal);
        return [
            'valid' => true,
            'code' => $promo->code,
            'type' => $promo->type,
            'value' => $promo->value,
            'discount' => $discount,
            'message' => "Promo applied! You save ৳{$discount}",
        ];
    }
}