<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('brand', 'category')->latest();

        if ($request->filled('q'))
            $query->where('name', 'like', "%{$request->q}%")
                ->orWhere('sku', 'like', "%{$request->q}%");

        if ($request->filled('low_stock'))
            $query->whereRaw('stock <= low_stock_alert');

        $products = $query->paginate(30);

        return response()->json([
            'data' => $products->map(fn($p) => $this->formatProduct($p)),
            'total' => $products->total(),
            'last_page' => $products->lastPage(),
        ]);
    }

    public function show(Product $product)
    {
        $product->load('brand', 'category');
        return response()->json(['product' => $this->formatProduct($product)]);
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:255',
        ]);

        $old = $product->stock;
        $product->update(['stock' => $request->stock]);

        return response()->json([
            'message' => "Stock updated from $old to {$request->stock}",
            'old_stock' => $old,
            'new_stock' => $request->stock,
        ]);
    }

    public function lowStock()
    {
        $products = Product::whereRaw('stock <= low_stock_alert')
            ->where('is_active', true)
            ->orderByRaw('stock - low_stock_alert ASC')
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return response()->json(['data' => $products]);
    }

    public function expiring()
    {
        // Products whose expiry_date is within 90 days — add expiry_date column if needed
        $products = Product::where('is_active', true)
            ->whereNotNull('expiry_date')
            ->where('expiry_date', '<=', now()->addDays(90))
            ->orderBy('expiry_date')
            ->get()
            ->map(fn($p) => $this->formatProduct($p));

        return response()->json(['data' => $products]);
    }

    private function formatProduct(Product $p): array
    {
        return [
            'id' => $p->id,
            'name' => $p->name,
            'sku' => $p->sku,
            'generic_name' => $p->generic_name,
            'brand' => $p->brand?->name,
            'category' => $p->category?->name,
            'price' => (float) $p->price,
            'stock' => $p->stock,
            'low_stock_alert' => $p->low_stock_alert,
            'is_low_stock' => $p->stock <= $p->low_stock_alert,
            'is_active' => $p->is_active,
            'thumbnail_url' => $p->thumbnail_url,
            'unit' => $p->unit,
        ];
    }
}