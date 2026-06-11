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
        return view('shop.landingpage.magnesium');

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

        return redirect()->route('checkout.index');
    }

}