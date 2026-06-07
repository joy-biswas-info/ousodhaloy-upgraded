<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Setting};
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
    }
    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->getCart();
        $items = $this->enrichCart($cart);
        $totals = $this->calcTotals($items);
        return view('shop.cart.index', compact('items', 'totals'));
    }

    public function add(Request $request)
    {
        $product = Product::active()->findOrFail($request->product_id);
        $qty = max(1, (int) $request->qty);

        if ($product->stock < $qty) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => "Only {$product->stock} in stock."], 422);
            }
            return back()->with('error', "Only {$product->stock} in stock.");
        }

        $cart = $this->getCart();
        $key = $product->id;

        if (isset($cart[$key])) {
            $newQty = $cart[$key]['qty'] + $qty;
            if ($newQty > $product->max_order_qty)
                $newQty = $product->max_order_qty;
            $cart[$key]['qty'] = $newQty;
        } else {
            $cart[$key] = [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->effective_price,
                'qty' => $qty,
                'thumbnail' => $product->thumbnail_url,
                'requires_rx' => $product->requires_prescription,
            ];
        }

        $this->saveCart($cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "{$product->name} added to cart",
                'count' => array_sum(array_column($cart, 'qty')),
            ]);
        }
        return back()->with('success', "{$product->name} added to cart!");
    }

    public function update(Request $request, int $productId)
    {
        $cart = $this->getCart();
        $qty = max(0, (int) $request->qty);

        if ($qty === 0) {
            unset($cart[$productId]);
        } else {
            $product = Product::find($productId);
            if ($product && $qty > $product->stock)
                $qty = $product->stock;
            if (isset($cart[$productId]))
                $cart[$productId]['qty'] = $qty;
        }
        $this->saveCart($cart);

        if ($request->ajax()) {
            $items = $this->enrichCart($cart);
            $totals = $this->calcTotals($items);
            return response()->json(['success' => true, 'count' => array_sum(array_column($cart, 'qty')), 'totals' => $totals]);
        }
        return redirect()->route('cart.index');
    }

    public function remove(int $productId)
    {
        $cart = $this->getCart();
        unset($cart[$productId]);
        $this->saveCart($cart);
        return back()->with('success', 'Item removed from cart.');
    }

    public function clear()
    {
        session()->forget('cart');
        return back()->with('success', 'Cart cleared.');
    }

    public function validatePromo(Request $request)
    {
        $cart = $this->getCart();
        $items = $this->enrichCart($cart);
        $totals = $this->calcTotals($items);

        $result = app(\App\Services\OrderService::class)
            ->validatePromo($request->code ?? '', $totals['subtotal'], auth()->id());

        return response()->json($result);
    }

    private function enrichCart(array $cart): array
    {
        if (empty($cart))
            return [];
        $productIds = array_keys($cart);
        $products = Product::active()->whereIn('id', $productIds)->get()->keyBy('id');
        $items = [];
        foreach ($cart as $id => $item) {
            $product = $products[$id] ?? null;
            if (!$product)
                continue;
            $item['product'] = $product;
            $item['price'] = $product->effective_price;
            $item['subtotal'] = round($item['price'] * $item['qty'], 2);
            $items[$id] = $item;
        }
        // Sync cart in session
        $this->saveCart(array_map(fn($i) => [
            'product_id' => $i['product_id'],
            'name' => $i['name'],
            'price' => $i['price'],
            'qty' => $i['qty'],
            'thumbnail' => $i['thumbnail'],
            'requires_rx' => $i['requires_rx'],
        ], $items));
        return $items;
    }

    private function calcTotals(array $items, float $discount = 0): array
    {
        $subtotal = array_sum(array_column($items, 'subtotal'));
        $charge = $subtotal >= (float) Setting::get('free_delivery_min', 1000) ? 0 : (float) Setting::get('delivery_charge', 0);
        $total = $subtotal + $charge - $discount;
        return [
            'subtotal' => $subtotal,
            'delivery_charge' => $charge,
            'discount' => $discount,
            'total' => max(0, $total),
            'item_count' => array_sum(array_column($items, 'qty')),
        ];
    }

    public static function getCount(): int
    {
        $cart = session('cart', []);
        return array_sum(array_column($cart, 'qty'));
    }
}