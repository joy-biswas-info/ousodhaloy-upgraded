<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{LandingPage, Setting};
use App\Services\{OrderService, SslCommerzService};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Cache};

class LandingPageController extends Controller
{
    public function __construct(
        private OrderService $orderService,
        private SslCommerzService $ssl,
    ) {
    }

    public function show(string $slug)
    {
        $landingPage = Cache::remember("landing_page:{$slug}", now()->addHours(6), function () use ($slug) {
            return LandingPage::with('product')->where('slug', $slug)->first();
        });

        $isPreview = $landingPage && $landingPage->status !== 'published';
        $canPreview = Auth::check() && Auth::user()->isManager();

        if (!$landingPage || !$landingPage->product || !$landingPage->product->is_active) {
            abort(404);
        }
        if ($isPreview && !$canPreview) {
            abort(404);
        }

        $landingPage->increment('views');

        $product = $landingPage->product;
        $reviews = $landingPage->sectionEnabled('reviews')
            ? $product->reviews()->where('is_approved', true)->latest()->take(6)->get()
            : collect();

        $codEnabled = Setting::get('cod_enabled', 'true') === 'true';
        $sslEnabled = Setting::get('ssl_enabled', 'true') === 'true';

        return view('shop.landingpage.dynamic', compact('landingPage', 'product', 'reviews', 'codEnabled', 'sslEnabled', 'isPreview'));
    }

    /**
     * Live delivery-charge preview. Deliberately separate from the cart-based
     * checkout.delivery-charge endpoint — that one reads session('cart'), which
     * this same-page flow never touches, so it can't see this product's own
     * custom delivery charge. Calling calculateDeliveryForCart the same way
     * quickOrder() does keeps the preview and the final charge in sync.
     */
    public function deliveryCharge(Request $request, LandingPage $landingPage)
    {
        $qty = max(1, min(10, (int) $request->qty ?: 1));
        $price = $landingPage->effective_price;
        $subtotal = $price * $qty;
        $division = $request->division ?? '';
        $district = $request->district ?? '';

        $charge = $this->orderService->calculateDeliveryForCart(
            [['product_id' => $landingPage->product_id, 'qty' => $qty]],
            $subtotal,
            $division,
            $district
        );

        return response()->json([
            'delivery_charge' => $charge,
            'subtotal' => $subtotal,
            'total' => $subtotal + $charge,
        ]);
    }

    public function quickOrder(Request $request, LandingPage $landingPage)
    {
        if ($landingPage->status !== 'published' && !(Auth::check() && Auth::user()->isManager())) {
            abort(404);
        }

        $product = $landingPage->product;
        if (!$product || !$product->is_active) {
            return response()->json(['success' => false, 'message' => 'This product is no longer available.'], 422);
        }

        $codEnabled = Setting::get('cod_enabled', 'true') === 'true';
        $sslEnabled = Setting::get('ssl_enabled', 'true') === 'true';
        $allowedMethods = array_filter([$codEnabled ? 'cod' : null, $sslEnabled ? 'ssl_commerz' : null]);

        $rules = [
            'shipping_name' => 'required|string|max:100',
            'shipping_phone' => 'required|regex:/^01[3-9]\d{8}$/',
            'shipping_division' => 'required|string|max:50',
            'shipping_district' => 'required|string|max:50',
            'shipping_address' => 'required|string|max:500',
            'payment_method' => ['required', 'in:' . implode(',', $allowedMethods ?: ['cod'])],
            'qty' => 'required|integer|min:1|max:10',
        ];
        if ($product->requires_prescription) {
            $rules['prescription'] = 'required|file|mimes:jpg,jpeg,png,webp,pdf|max:5120';
        }

        $validated = $request->validate($rules, [
            'shipping_phone.regex' => 'Please enter a valid Bangladeshi phone number (01XXXXXXXXX)',
        ]);

        $prescriptionPath = null;
        if ($request->hasFile('prescription')) {
            $prescriptionPath = $request->file('prescription')->store('prescriptions', 'public');
        }

        try {
            $order = $this->orderService->create([
                'shipping_name' => $validated['shipping_name'],
                'shipping_phone' => $validated['shipping_phone'],
                'shipping_email' => null,
                'shipping_division' => $validated['shipping_division'],
                'shipping_district' => $validated['shipping_district'],
                'shipping_upazila' => '',
                'shipping_address' => $validated['shipping_address'],
                'shipping_postcode' => null,
                'payment_method' => $validated['payment_method'],
                'notes' => null,
                'promo_code' => null,
                'landing_page_id' => $landingPage->id,
                'items' => [['product_id' => $product->id, 'qty' => (int) $validated['qty']]],
                'guest_name' => !Auth::check() ? $validated['shipping_name'] : null,
                'guest_email' => null,
                'prescription_image' => $prescriptionPath,
            ], Auth::id());
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }

        if ($order->payment_method === 'ssl_commerz') {
            $result = $this->ssl->initiatePayment($order);
            if ($result['success']) {
                return response()->json(['success' => true, 'gateway_url' => $result['gateway_url']]);
            }
            return response()->json([
                'success' => false,
                'message' => $result['error'] ?? 'Payment gateway error. Please try Cash on Delivery instead.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'order_number' => $order->order_number,
            'order_id' => $order->id,
            'track_url' => route('orders.show', $order->id),
        ]);
    }
}
