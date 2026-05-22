<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BulkProductController extends Controller
{
    public function index()
    {
        $categories = Category::active()->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.bulk', compact('categories', 'brands'));
    }

    /**
     * Import products from CSV upload
     */
    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $file = $request->file('csv_file');
        $lines = array_map('str_getcsv', file($file->getRealPath()));
        $headers = array_map('trim', array_shift($lines));

        $imported = 0;
        $updated = 0;
        $errors = [];

        foreach ($lines as $lineNum => $row) {
            if (count($row) < 3)
                continue;
            $data = array_combine($headers, array_pad($row, count($headers), ''));

            try {
                $result = $this->upsertProduct($data);
                $result === 'created' ? $imported++ : $updated++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($lineNum + 2) . ": " . $e->getMessage();
            }
        }

        $msg = "Import complete: {$imported} created, {$updated} updated.";
        if ($errors)
            $msg .= ' Errors: ' . implode('; ', array_slice($errors, 0, 5));

        return back()->with('success', $msg);
    }

    /**
     * Bulk add products from JSON-like form (multiple rows)
     */
    public function storeBulk(Request $request)
    {
        $request->validate([
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric|min:0',
            'products.*.stock' => 'required|integer|min:0',
        ]);

        $imported = 0;
        $errors = [];

        foreach ($request->products as $i => $data) {
            try {
                $this->upsertProduct($data);
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row " . ($i + 1) . " ({$data['name']}): " . $e->getMessage();
            }
        }

        $msg = "{$imported} products saved.";
        if ($errors)
            $msg .= ' Errors: ' . implode('; ', $errors);

        return back()->with('success', $msg);
    }

    /**
     * Download CSV template
     */
    public function template()
    {
        $headers = [
            'name',
            'generic_name',
            'sku',
            'brand',
            'category',
            'price',
            'mrp',
            'stock',
            'unit',
            'strength',
            'form',
            'pack_size',
            'requires_prescription',
            'is_featured',
            'tags',
            'description',
            'thumbnail',
            'low_stock_alert',
        ];

        $sample = [
            'Napa 500mg',
            'Paracetamol',
            'NAP500',
            'Beximco Pharma',
            'Medicine',
            '0.90',
            '1.00',
            '500',
            'strip',
            '500mg',
            'Tablet',
            '10 tablets/strip',
            '0',
            '0',
            'fever,pain,paracetamol',
            'Paracetamol tablet for fever and pain',
            'napa-500mg.jpg',
            '20',
        ];

        $csv = implode(',', $headers) . "\n" . implode(',', $sample) . "\n";

        return response($csv, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
        ]);
    }

    // ── Private helpers ───────────────────────────────────────────────────

    /**
     * Resolve thumbnail: accepts a filename from media library, a full path, or empty.
     * e.g. "napa-500mg.jpg" → looks in media_library table → returns path like "media/napa-500mg.jpg"
     */
    private function resolveThumbnail(string $value): ?string
    {
        if (empty($value))
            return null;

        // Already a full storage path (products/..., media/...)
        if (str_contains($value, '/'))
            return $value;

        // Lookup by filename in media library
        $media = \App\Models\Media::findByFilename(trim($value));
        if ($media)
            return $media->path;

        // Try common folders
        foreach (['media', 'products', 'banners'] as $folder) {
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists("{$folder}/{$value}")) {
                return "{$folder}/{$value}";
            }
        }

        return null;
    }

    private function upsertProduct(array $data): string
    {
        // Resolve brand
        $brandId = null;
        if (!empty($data['brand'])) {
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($data['brand'])],
                ['name' => trim($data['brand']), 'is_active' => true]
            );
            $brandId = $brand->id;
        }

        // Resolve category
        $categoryId = null;
        if (!empty($data['category'])) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($data['category'])],
                ['name' => trim($data['category']), 'icon' => '💊', 'is_active' => true]
            );
            $categoryId = $cat->id;
        }

        // Parse tags
        $tags = [];
        if (!empty($data['tags'])) {
            $tags = array_map('trim', explode(',', $data['tags']));
        }

        // Build default tab
        $tabs = [];
        if (!empty($data['description'])) {
            $tabs[] = ['id' => 'desc', 'label' => 'Description', 'content' => "<p>{$data['description']}</p>"];
        }

        $slug = Str::slug($data['name']);
        $exists = Product::where('slug', $slug)
            ->orWhere('sku', $data['sku'] ?? null)
            ->first();

        $payload = [
            'name' => trim($data['name']),
            'slug' => $slug,
            'sku' => !empty($data['sku']) ? trim($data['sku']) : null,
            'generic_name' => !empty($data['generic_name']) ? trim($data['generic_name']) : null,
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'price' => (float) ($data['price'] ?? 0),
            'mrp' => !empty($data['mrp']) ? (float) $data['mrp'] : null,
            'stock' => (int) ($data['stock'] ?? 0),
            'unit' => !empty($data['unit']) ? trim($data['unit']) : 'pcs',
            'strength' => !empty($data['strength']) ? trim($data['strength']) : null,
            'form' => !empty($data['form']) ? trim($data['form']) : null,
            'pack_size' => !empty($data['pack_size']) ? trim($data['pack_size']) : null,
            'requires_prescription' => in_array(strtolower($data['requires_prescription'] ?? '0'), ['1', 'true', 'yes']),
            'is_active' => true,
            'is_featured' => in_array(strtolower($data['is_featured'] ?? '0'), ['1', 'true', 'yes']),
            'tags' => $tags ?: null,
            'tabs' => $tabs ?: null,
            'low_stock_alert' => (int) ($data['low_stock_alert'] ?? 10),
            'thumbnail' => $this->resolveThumbnail($data['thumbnail'] ?? ''),
            'discount_percent' => !empty($data['mrp']) && !empty($data['price']) && (float) $data['mrp'] > 0
                ? round(((float) $data['mrp'] - (float) $data['price']) / (float) $data['mrp'] * 100, 2)
                : 0,
        ];

        if ($exists) {
            $exists->update($payload);
            return 'updated';
        }

        Product::create($payload);
        return 'created';
    }
}