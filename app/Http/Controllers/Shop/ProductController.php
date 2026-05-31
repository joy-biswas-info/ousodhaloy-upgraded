<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand, ProductReview};
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('brand', 'category');

        if ($search = $request->q) {
            $query->search($search);
        }
        if ($cat = $request->category) {
            $category = Category::where('slug', $cat)->first();
            if ($category) {
                // Match products where this is the primary category OR any additional category
                $query->where(function ($q) use ($category) {
                    $q->where('category_id', $category->id)
                        ->orWhereHas('categories', fn($c) => $c->where('categories.id', $category->id));
                });
            }
        }
        if ($brand = $request->brand) {
            $b = Brand::where('slug', $brand)->first();
            if ($b)
                $query->where('brand_id', $b->id);
        }
        if ($request->flash_sale)
            $query->flashSale();
        if ($request->featured)
            $query->featured();
        if ($request->in_stock)
            $query->inStock();
        if ($request->no_rx)
            $query->where('requires_prescription', false);
        if ($request->min_price)
            $query->where('price', '>=', $request->min_price);
        if ($request->max_price)
            $query->where('price', '<=', $request->max_price);

        $sort = $request->sort ?? 'newest';
        match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            'discount' => $query->orderByDesc('discount_percent'),
            'top_selling' => $query->orderByDesc('total_sold'),
            'rating' => $query->orderByDesc('average_rating'),
            default => $query->latest(),
        };

        $products = $query->paginate(20)->withQueryString();
        $categories = Category::active()->withCount(['products' => fn($q) => $q->active()])->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $currentCat = $request->category ? Category::where('slug', $request->category)->first() : null;

        return view('shop.products.index', compact('products', 'categories', 'brands', 'currentCat', 'sort'));
    }

    public function show(string $slug)
    {
        $product = Product::active()
            ->with(['brand', 'category', 'reviews' => fn($q) => $q->where('is_approved', true)->with('user')->latest()])
            ->where('slug', $slug)
            ->firstOrFail();

        $product->incrementViews();

        // Related products — same generic name first, then same category
        $related = Product::active()
            ->where('id', '!=', $product->id)
            ->inStock()
            ->when(
                $product->generic_name,
                fn($q) =>
                $q->where('generic_name', $product->generic_name)
            )
            ->take(5)
            ->get();

        // If fewer than 3 by generic name, backfill from same category
        if ($related->count() < 3 && $product->category_id) {
            $existingIds = $related->pluck('id')->push($product->id);
            $backfill = Product::active()
                ->where('category_id', $product->category_id)
                ->whereNotIn('id', $existingIds)
                ->inStock()
                ->take(8 - $related->count())
                ->get();
            $related = $related->merge($backfill);
        }

        // Same brand
        $sameBrand = Product::active()
            ->where('brand_id', $product->brand_id)
            ->where('id', '!=', $product->id)
            ->inStock()
            ->take(5)
            ->get();

        return view('shop.products.show', compact('product', 'related', 'sameBrand'));
    }

    public function storeReview(Request $request, string $slug)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:120',
            'body' => 'required|string|max:2000',
        ]);

        $product = Product::where('slug', $slug)->firstOrFail();

        // Prevent duplicate reviews
        $existing = ProductReview::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product.');
        }

        ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'is_approved' => false, // admin approval required
        ]);

        return back()->with('review_success', 'Your review has been submitted and is pending approval. Thank you!');
    }

    // Live search endpoint (AJAX)
    public function search(Request $request)
    {
        $term = $request->q ?? '';
        if (strlen($term) < 2)
            return response()->json([]);

        $products = Product::active()
            ->search($term)
            ->with('brand', 'category')
            ->inStock()
            ->take(8)
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'slug' => $p->slug,
                'generic_name' => $p->generic_name,
                'brand' => $p->brand?->name,
                'price' => $p->effective_price,
                'mrp' => $p->mrp,
                'discount' => $p->discount_percentage,
                'thumbnail_url' => $p->thumbnail_url,
                'in_stock' => $p->is_in_stock,
                'requires_rx' => $p->requires_prescription,
            ]);

        return response()->json($products);
    }
}