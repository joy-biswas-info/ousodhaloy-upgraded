<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\{DeliveryZone, Setting};
use App\Services\{OrderService, SslCommerzService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private SslCommerzService $ssl,
    ) {
    }

    public function index()
    {
        $cart = session('cart', []);
        if (empty($cart))
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');

        $guestAllowed = Setting::get('guest_checkout', 'true') === 'true';
        if (!$guestAllowed && !Auth::check()) {
            return redirect()->route('auth.login')->with('info', 'Please login to checkout.');
        }

        $address = Auth::user()?->default_address;
        $BD_DIVISIONS = config('bd.divisions', []);
        $codEnabled = Setting::get('cod_enabled', 'true') === 'true';
        $sslEnabled = Setting::get('ssl_enabled', 'true') === 'true';

        return view('shop.checkout.index', compact('address', 'BD_DIVISIONS', 'codEnabled', 'sslEnabled'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|regex:/^01[3-9]\d{8}$/',
            'shipping_division' => 'required|string|max:50',
            'shipping_district' => 'required|string|max:50',
            'shipping_upazila' => $this->upazilaRequired() ? 'required|string|max:100' : 'nullable|string|max:100',
            'shipping_address' => 'required|string|max:500',
            'shipping_postcode' => 'nullable|string|max:10',
            'shipping_email' => 'nullable|email|max:150',
            'payment_method' => 'required|in:cod,ssl_commerz,bkash,nagad',
            'notes' => 'nullable|string|max:500',
            'promo_code' => 'nullable|string|max:50',
            'prescription' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:5120',
        ], [
            'shipping_phone.regex' => 'Please enter a valid Bangladeshi phone number (01XXXXXXXXX)',
        ]);

        $cart = session('cart', []);
        if (empty($cart))
            return back()->with('error', 'Your cart is empty.');

        $items = array_map(fn($item) => [
            'product_id' => $item['product_id'],
            'qty' => $item['qty'],
        ], $cart);

        $prescriptionPath = null;
        if ($request->hasFile('prescription')) {
            $prescriptionPath = $request->file('prescription')->store('prescriptions', 'public');
        }

        try {
            $order = $this->orderService->create(
                array_merge($request->only([
                    'shipping_name',
                    'shipping_phone',
                    'shipping_email',
                    'shipping_division',
                    'shipping_district',
                    'shipping_upazila',
                    'shipping_address',
                    'shipping_postcode',
                    'payment_method',
                    'notes',
                    'promo_code',
                ]), [
                    'items' => $items,
                    'guest_name' => !Auth::check() ? $request->shipping_name : null,
                    'guest_email' => !Auth::check() ? $request->shipping_email : null,
                    'prescription_image' => $prescriptionPath,
                ]),
                Auth::id()
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget('cart');

        if ($order->payment_method === 'ssl_commerz') {
            $result = $this->ssl->initiatePayment($order);
            if ($result['success'])
                return redirect($result['gateway_url']);
            return redirect()->route('orders.show', $order->id)
                ->with('error', $result['error'] ?? 'Payment gateway error.');
        }

        return redirect()->route('orders.show', $order->id)
            ->with('success', 'Order placed successfully! 🎉');
    }

    private function upazilaRequired(): bool
    {
        $cf = json_decode(Setting::get('checkout_fields', '{}'), true) ?: [];
        // Default: visible and required unless admin explicitly turned it off
        return ($cf['shipping_upazila']['visible'] ?? true)
            && ($cf['shipping_upazila']['required'] ?? true);
    }

    /**
     * AJAX: recalculate delivery charge when district changes
     */
    public function deliveryCharge(Request $request)
    {
        $subtotal = (float) ($request->subtotal ?? 0);
        $division = $request->division ?? '';
        $district = $request->district ?? '';

        // Use cart items for per-product custom delivery charge calculation
        $cart = session('cart', []);
        $charge = !empty($cart)
            ? $this->orderService->calculateDeliveryForCart($cart, $subtotal, $division, $district)
            : $this->orderService->calculateDelivery($subtotal, $division, $district);

        $freeAbove = (float) Setting::get('free_delivery_min', 1000);
        $zone = DeliveryZone::where('division', $division)
            ->whereJsonContains('districts', $district)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'delivery_charge' => $charge,
            'free_delivery_above' => $zone?->free_delivery_above ?? $freeAbove,
            'estimated_days' => $zone?->estimated_days ?? 2,
            'zone_name' => $zone?->name ?? 'Standard Delivery',
            'total' => $subtotal + $charge,
        ]);
    }

}

