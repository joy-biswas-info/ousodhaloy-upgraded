<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;

class LandingController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }
    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function show()
    {
        $product = Product::with('brand', 'category')
            ->where('id', 13)
            ->first();
        return view('shop.landingpage.magnesium', compact('product'));
    }
    public function niacinamide()
    {
        $product = Product::with('brand', 'category')
            ->where('id', 15)
            ->first();
        return view('shop.landingpage.ordinary-niacinamide', compact('product'));
    }
    public function salicylic()
    {
        $product = Product::with('brand', 'category')
            ->where('id', 15)
            ->first();
        return view('shop.landingpage.salicylic_acid_landing', compact('product'));
    }
    public function buyNow(Product $product, int $qty = 1)
    {
        $qty = max(1, min(10, $qty));

        if ($product->stock < $qty) {
            return back()->with('error', 'Insufficient stock');
        }
        $cart = [];
        $cart[$product->id] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->effective_price,
            'qty' => $qty,
            'thumbnail' => $product->thumbnail_url,
            'requires_rx' => $product->requires_prescription,
        ];
        $this->saveCart($cart);

        // Same in-app-browser fallback as LandingPageController::buyNow() —
        // carry the cart info in the URL too, since Facebook/Instagram's
        // in-app browser (the main entry point for this ad landing page)
        // doesn't reliably round-trip a session cookie set just before a
        // redirect. See CheckoutController::index()'s fallback handling.
        return redirect()->route('checkout.index', [
            'buy_product' => $product->id,
            'buy_qty' => $qty,
        ])
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

}