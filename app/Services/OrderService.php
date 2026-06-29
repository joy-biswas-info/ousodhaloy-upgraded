<?php
namespace App\Services;
use App\Mail\NewOrderMail;
use App\Models\PromoCodeUsage;
use Illuminate\Support\Facades\Mail;
use App\Models\{DeliveryZone, Order, OrderItem, OrderStatusHistory, Product, PromoCode, Setting, LoyaltyPoint};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function __construct(
        private SmsService $sms,
    ) {
    }

    /**
     * Create an order from cart data.
     */
    public function create(array $data, ?int $userId = null): Order
    {
        return DB::transaction(function () use ($data, $userId) {

            // 1. Validate items & calculate subtotal
            $orderItems = [];
            $subtotal = 0;

            foreach ($data['items'] as $item) {
                $product = Product::active()->lockForUpdate()->findOrFail($item['product_id']);

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

            // 8. Loyalty points (earn 1 point per ৳10)
            if ($userId) {
                $points = (int) ($total / 10);
                if ($points > 0) {
                    LoyaltyPoint::create([
                        'user_id' => $userId,
                        'points' => $points,
                        'type' => 'earn',
                        'description' => "Earned for order #{$order->order_number}",
                        'order_id' => $order->id,
                    ]);
                }
            }

            // 9. Send confirmation SMS
            $this->sms->orderPlaced($order);

            $adminEmail = Setting::get('admin_notification_email') ?: Setting::get('site_email');
            if ($adminEmail && Setting::get('email_new_order', 'true') === 'true') {
                try {
                    Mail::to($adminEmail)->send(new NewOrderMail($order->load('items')));
                } catch (\Exception $e) {
                    logger()->error('Admin order email failed: ' . $e->getMessage());
                }
            }
            return $order->fresh(['items']);
        });
    }

    /**
     * Update order status with history + SMS.
     */
    public function updateStatus(Order $order, string $newStatus, string $note = '', bool $notifyCustomer = true): bool
    {
        if ($order->status === $newStatus)
            return false;

        $order->update([
            'status' => $newStatus,
            'delivered_at' => $newStatus === 'delivered' ? now() : $order->delivered_at,
        ]);

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $newStatus,
            'note' => $note ?: null,
            'changed_by' => Auth::user()?->name ?? 'system',
            'notify_customer' => $notifyCustomer,
        ]);

        // Restore stock on cancel
        if ($newStatus === 'cancelled') {
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