<?php
namespace App\Http\Controllers\Shop;
use App\Http\Controllers\Controller;
use App\Models\{DeliveryZone, LandingPage, Product, Setting};
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

    public function index(Request $request)
    {
        $cart = session('cart', []);

        // Fallback for a lost session on the "Buy Now" hop (see
        // LandingPageController::buyNow() / LandingController::buyNow()) —
        // both write the cart to session then redirect here, a GET-then-GET
        // round trip that depends on the session cookie surviving between
        // them. Facebook/Instagram's in-app browser — the near-universal
        // entry point for these ad landing pages — is notorious for
        // breaking exactly that pattern. Rebuilding from the URL means
        // checkout still works even if the session write never reached the
        // browser. Only trusted enough to look up a real, active, in-stock
        // product fresh from the DB — never trust price/name from the
        // request, same as the normal session-based path never did.
        if (empty($cart) && $request->filled('buy_product')) {
            $product = Product::active()->find($request->integer('buy_product'));
            if ($product) {
                $qty = max(1, min(10, $request->integer('buy_qty', 1)));
                if ($product->stock >= $qty) {
                    $landingPage = $request->filled('buy_lp') ? LandingPage::find($request->integer('buy_lp')) : null;
                    $cart = [
                        $product->id => [
                            'product_id' => $product->id,
                            'name' => $product->name,
                            'price' => $landingPage?->effective_price ?? $product->effective_price,
                            'qty' => $qty,
                            'thumbnail' => $product->thumbnail_url,
                            'requires_rx' => $product->requires_prescription,
                        ],
                    ];
                    session(['cart' => $cart, 'landing_page_id' => $landingPage?->id]);
                }
            }
        }

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

        // This page's content is entirely session-specific (one customer's
        // cart). If anything upstream ever cached it, the worst case isn't
        // just a broken checkout — it's serving one customer's cart/address
        // to a different visitor. Never cacheable, no exceptions.
        return response()
            ->view('shop.checkout.index', compact('address', 'BD_DIVISIONS', 'codEnabled', 'sslEnabled'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
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

        // The checkout page shows a "Required" badge when the cart has a
        // prescription item, but until now that was cosmetic only — nothing
        // stopped the order from completing without a file actually attached.
        if ($this->prescriptionRequired($cart) && !$request->hasFile('prescription')) {
            return back()
                ->withErrors(['prescription' => 'This order includes a prescription-required medicine — please upload a valid prescription to continue.'])
                ->withInput();
        }

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
                    'landing_page_id' => session('landing_page_id'),
                ]),
                Auth::id()
            );
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        session()->forget(['cart', 'landing_page_id']);

        if ($order->payment_method === 'ssl_commerz') {
            $result = $this->ssl->initiatePayment($order);
            if ($result['success'])
                return redirect($result['gateway_url']);
            return redirect($order->signedShowUrl())
                ->with('error', $result['error'] ?? 'Payment gateway error.');
        }

        return redirect($order->signedShowUrl())
            ->with('success', 'Order placed successfully! 🎉');
    }

    private function upazilaRequired(): bool
    {
        $cf = json_decode(Setting::get('checkout_fields', '{}'), true) ?: [];
        // Default: visible and required unless admin explicitly turned it off
        return ($cf['shipping_upazila']['visible'] ?? true)
            && ($cf['shipping_upazila']['required'] ?? true);
    }

    private function prescriptionRequired(array $cart): bool
    {
        $cf = json_decode(Setting::get('checkout_fields', '{}'), true) ?: [];
        // If the admin has hidden the upload field entirely there's no way for a
        // customer to satisfy this, so don't demand something the form can't collect.
        $fieldVisible = $cf['prescription']['visible'] ?? true;
        if (!$fieldVisible)
            return false;

        return collect($cart)->contains('requires_rx', true);
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

