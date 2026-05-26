<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Storage, DB};
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'brand');
        if ($q = $request->q)
            $query->search($q);
        if ($status = $request->status) {
            match ($status) {
                'active' => $query->where('is_active', true),
                'inactive' => $query->where('is_active', false),
                'featured' => $query->where('is_featured', true),
                'flash_sale' => $query->where('is_flash_sale', true),
                'low_stock' => $query->where('stock', '<=', DB::raw('low_stock_alert'))->where('stock', '>', 0),
                'out_of_stock' => $query->where('stock', 0),
                default => null,
            };
        }
        $products = $query->latest()->paginate(20)->withQueryString();
        $categories = Category::active()->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories', 'brands'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $product = null;
        return view('admin.products.form', compact('categories', 'brands', 'product'));
    }

    public function store(Request $request)
    {
        $data = $this->validateAndPrepare($request);
        $data['slug'] = $data['slug'] ?: Str::slug($data['name']);

        // Check slug uniqueness
        $data['slug'] = $this->uniqueSlug($data['slug']);

        $product = Product::create($data);
        $this->handleImages($request, $product);
        $this->syncCategories($product, $request);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created successfully!');
    }

    public function edit(Product $product)
    {
        $product->load('brand', 'category');
        try {
            $product->load('categories');
        } catch (\Throwable $e) {
            $product->setRelation('categories', collect());
        }
        $categories = Category::active()->orderBy('sort_order')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories', 'brands'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateAndPrepare($request, $product->id);
        if (!$data['slug'])
            $data['slug'] = Str::slug($data['name']);
        $data['slug'] = $this->uniqueSlug($data['slug'], $product->id);

        $product->update($data);
        $this->handleImages($request, $product);
        $this->syncCategories($product, $request);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product updated!');
    }

    public function destroy(Product $product)
    {
        // Soft delete — product goes to trash, can be restored
        $product->delete();
        return redirect()->route('admin.products.index')
            ->with('success', "Product \"{$product->name}\" moved to trash.");
    }

    public function bulkTrash(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer']);
        $count = Product::whereIn('id', $request->ids)->delete();
        return back()->with('success', "{$count} product(s) moved to trash.");
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->with('category', 'brand')->latest('deleted_at')->paginate(20);
        return view('admin.products.trash', compact('products'));
    }

    public function restore(int $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        $product->restore();
        return back()->with('success', "Product \"{$product->name}\" restored.");
    }

    public function forceDelete(int $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);
        if ($product->thumbnail)
            \Illuminate\Support\Facades\Storage::disk('public')->delete($product->thumbnail);
        $product->forceDelete();
        return back()->with('success', 'Product permanently deleted.');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|mimes:jpeg,png,webp,gif|max:5120']);

        $file = $request->file('image');
        $seoName = \App\Models\Media::seoFilename($file->getClientOriginalName(), 'products');
        $path = $file->storeAs('products', $seoName, 'public');

        // Dimensions
        $width = $height = null;
        try {
            [$width, $height] = getimagesize($file->getRealPath());
        } catch (\Throwable $e) {
        }

        $media = \App\Models\Media::create([
            'filename' => $seoName,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'width' => $width,
            'height' => $height,
            'alt_text' => pathinfo($seoName, PATHINFO_FILENAME),
            'folder' => 'products',
            'uploaded_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'url' => $media->url,
            'path' => $media->path,
            'filename' => $media->filename,
            'media_id' => $media->id,
        ]);
    }

    private function validateAndPrepare(Request $request, ?int $excludeId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'sku' => 'nullable|string|max:100|unique:products,sku' . ($excludeId ? ",{$excludeId}" : ''),
            'generic_name' => 'nullable|string|max:255',
            'brand_id' => 'nullable|exists:brands,id',
            'category_id' => 'nullable|exists:categories,id',
            'category_ids' => 'nullable|array',
            'category_ids.*' => 'exists:categories,id',
            'price' => 'required|numeric|min:0',
            'mrp' => 'nullable|numeric|min:0',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'stock' => 'required|integer|min:0',
            'low_stock_alert' => 'nullable|integer|min:0',
            'min_order_qty' => 'nullable|integer|min:1',
            'max_order_qty' => 'nullable|integer|min:1',
            'unit' => 'required|string',
            'pack_size' => 'nullable|string|max:100',
            'strength' => 'nullable|string|max:100',
            'form' => 'nullable|string|max:100',
            'tabs' => 'nullable|json',
            'tags' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];

        $validated = $request->validate($rules);

        return [
            ...$validated,
            'requires_prescription' => $request->boolean('requires_prescription'),
            'is_active' => $request->boolean('is_active', true),
            'is_featured' => $request->boolean('is_featured'),
            'is_flash_sale' => $request->boolean('is_flash_sale'),
            'express_delivery' => $request->boolean('express_delivery'),
            'flash_sale_price' => $request->is_flash_sale ? $request->flash_sale_price : null,
            'flash_sale_ends_at' => $request->is_flash_sale ? $request->flash_sale_ends_at : null,
            'tabs' => $request->tabs ? json_decode($request->tabs, true) : [],
            'tags' => $request->tags
                ? array_map('trim', explode(',', $request->tags))
                : [],
            'low_stock_alert' => $request->low_stock_alert ?? 10,
        ];
    }

    private function handleImages(Request $request, Product $product): void
    {
        // Thumbnail — accept either a file upload OR a media path from the picker
        if ($request->hasFile('thumbnail')) {
            // Delete old file from disk only if not referenced in media_library
            if ($product->thumbnail && !\App\Models\Media::where('path', $product->thumbnail)->exists()) {
                Storage::disk('public')->delete($product->thumbnail);
            }

            $file = $request->file('thumbnail');
            $seoName = \App\Models\Media::seoFilename($file->getClientOriginalName(), 'products');
            $path = $file->storeAs('products', $seoName, 'public');

            $width = $height = null;
            try {
                [$width, $height] = getimagesize($file->getRealPath());
            } catch (\Throwable $e) {
            }

            \App\Models\Media::create([
                'filename' => $seoName,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'width' => $width,
                'height' => $height,
                'alt_text' => pathinfo($seoName, PATHINFO_FILENAME),
                'folder' => 'products',
                'uploaded_by' => auth()->id(),
            ]);

            $product->update(['thumbnail' => $path]);

        } elseif ($request->filled('thumbnail_media_path')) {
            // Picked from media library — just store the path
            $product->update(['thumbnail' => $request->thumbnail_media_path]);
        }

        // Gallery images (JSON array of paths from ajax upload — already in media library)
        if ($request->images_json) {
            $paths = json_decode($request->images_json, true) ?? [];
            $product->update(['images' => $paths]);
        }
    }

    private function syncCategories(Product $product, \Illuminate\Http\Request $request): void
    {
        $primaryId = $request->input('category_id');
        $allIds = $request->input('category_ids', []);

        // Always include primary in the pivot
        if ($primaryId && !in_array($primaryId, $allIds)) {
            $allIds[] = $primaryId;
        }

        // Build pivot data: mark primary
        $pivot = collect($allIds)->filter()->mapWithKeys(fn($id) => [
            (int) $id => ['is_primary' => (int) $id === (int) $primaryId]
        ])->toArray();

        $product->categories()->sync($pivot);

        // Keep category_id column in sync with primary
        if ($primaryId) {
            $product->update(['category_id' => $primaryId]);
        }
    }

    private function uniqueSlug(string $slug, ?int $excludeId = null): string
    {
        $original = $slug;
        $i = 1;
        while (Product::where('slug', $slug)->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))->exists()) {
            $slug = $original . '-' . $i++;
        }
        return $slug;
    }
}