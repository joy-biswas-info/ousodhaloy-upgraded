<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand, Media};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BulkProductController extends Controller
{
    // ── Pages ─────────────────────────────────────────────────────────────

    public function index()
    {
        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.bulk', compact('categories', 'brands'));
    }

    // ── CSV Upload ────────────────────────────────────────────────────────

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $handle = fopen($path, 'r');
        $headers = null;
        $imported = 0;
        $updated = 0;
        $errors = [];
        $lineNum = 0;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNum++;

            // First non-empty row = headers
            if ($headers === null) {
                $headers = array_map('trim', $row);
                continue;
            }

            // Skip blank rows
            if (empty(array_filter($row, fn($v) => trim($v) !== '')))
                continue;

            // Make row same length as headers
            $headerCount = count($headers);
            $row = array_slice($row, 0, $headerCount);           // trim excess
            $row = array_pad($row, $headerCount, '');            // pad short

            $data = array_combine($headers, $row);

            try {
                $result = $this->upsertProduct($data);
                $result === 'created' ? $imported++ : $updated++;
            } catch (\Exception $e) {
                $errors[] = "Row {$lineNum}: " . $e->getMessage();
                if (count($errors) >= 20) {
                    $errors[] = '… too many errors, stopping early.';
                    break;
                }
            }
        }

        fclose($handle);

        $msg = "Import complete: {$imported} created, {$updated} updated.";
        if ($errors) {
            $msg .= "\n\nErrors:\n" . implode("\n", $errors);
            return back()->with('error', $msg);
        }

        return back()->with('success', $msg);
    }

    // ── Spreadsheet form submit ───────────────────────────────────────────

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
            $msg .= ' Issues: ' . implode('; ', $errors);

        return back()->with('success', $msg);
    }

    // ── CSV Template Download ─────────────────────────────────────────────

    public function template(Request $request)
    {
        $base = [
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
            'thumbnail',
            'low_stock_alert',
        ];

        // Dynamic tab columns: ?tabs=Description,Usage,Side Effects
        $tabNames = ['Description'];  // default
        if ($request->filled('tabs')) {
            $parsed = array_filter(array_map('trim', explode(',', $request->tabs)));
            if (!empty($parsed))
                $tabNames = $parsed;
        }

        $headers = $base;
        foreach ($tabNames as $t) {
            $headers[] = 'tab:' . $t;
        }

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
            '',
            '20',
        ];
        foreach ($tabNames as $t) {
            $sample[] = 'Write your ' . strtolower($t) . ' content here.';
        }

        $tmp = fopen('php://temp', 'r+');
        fputcsv($tmp, $headers);
        fputcsv($tmp, $sample);
        rewind($tmp);
        $csv = stream_get_contents($tmp);
        fclose($tmp);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_template.csv"',
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────

    private function resolveThumbnail(string $value): ?string
    {
        $value = trim($value);
        if (empty($value))
            return null;

        // Already a storage path
        if (str_contains($value, '/')) {
            return Storage::disk('public')->exists($value) ? $value : null;
        }

        // Search media library by filename
        $media = Media::findByFilename($value);
        if ($media)
            return $media->path;

        // Try common folders
        foreach (['media', 'products', 'banners', 'settings'] as $folder) {
            if (Storage::disk('public')->exists("{$folder}/{$value}")) {
                return "{$folder}/{$value}";
            }
        }

        return null;
    }

    private function upsertProduct(array $data): string
    {
        $name = trim($data['name'] ?? '');
        if (empty($name))
            throw new \Exception('Name is required');

        // Brand
        $brandId = null;
        if (!empty(trim($data['brand'] ?? ''))) {
            $brand = Brand::firstOrCreate(
                ['slug' => Str::slug($data['brand'])],
                ['name' => trim($data['brand']), 'is_active' => true]
            );
            $brandId = $brand->id;
        }

        // Category
        $categoryId = null;
        if (!empty(trim($data['category'] ?? ''))) {
            $cat = Category::firstOrCreate(
                ['slug' => Str::slug($data['category'])],
                ['name' => trim($data['category']), 'icon' => '💊', 'is_active' => true]
            );
            $categoryId = $cat->id;
        }

        // Tags
        $tags = [];
        if (!empty(trim($data['tags'] ?? ''))) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $data['tags']))));
        }

        // Tabs — supports tab:Label columns and legacy description column
        $tabs = [];
        foreach ($data as $col => $val) {
            if (!str_starts_with((string) $col, 'tab:'))
                continue;
            $val = trim($val);
            if (empty($val))
                continue;
            $label = trim(substr($col, 4));
            $tabs[] = [
                'id' => Str::slug($label),
                'label' => $label,
                'content' => '<p>' . nl2br(e($val)) . '</p>',
            ];
        }
        if (empty($tabs) && !empty(trim($data['description'] ?? ''))) {
            $tabs[] = [
                'id' => 'desc',
                'label' => 'Description',
                'content' => '<p>' . e(trim($data['description'])) . '</p>',
            ];
        }

        // Prices
        $price = (float) ($data['price'] ?? 0);
        $mrp = !empty(trim($data['mrp'] ?? '')) ? (float) $data['mrp'] : null;
        $disc = ($mrp && $mrp > $price) ? round(($mrp - $price) / $mrp * 100, 2) : 0;

        // Slug — ensure uniqueness
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;

        // Find existing by SKU or slug
        $sku = !empty(trim($data['sku'] ?? '')) ? trim($data['sku']) : null;
        $exists = null;
        if ($sku) {
            $exists = Product::where('sku', $sku)->first();
        }
        if (!$exists) {
            $exists = Product::where('slug', $slug)->first();
        }

        // If creating new, make slug unique
        if (!$exists) {
            $n = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $n++;
            }
        }

        $payload = [
            'name' => $name,
            'slug' => $exists ? $exists->slug : $slug,
            'sku' => $sku,
            'generic_name' => trim($data['generic_name'] ?? '') ?: null,
            'brand_id' => $brandId,
            'category_id' => $categoryId,
            'price' => $price,
            'mrp' => $mrp,
            'discount_percent' => $disc,
            'stock' => (int) ($data['stock'] ?? 0),
            'unit' => trim($data['unit'] ?? '') ?: 'pcs',
            'strength' => trim($data['strength'] ?? '') ?: null,
            'form' => trim($data['form'] ?? '') ?: null,
            'pack_size' => trim($data['pack_size'] ?? '') ?: null,
            'requires_prescription' => in_array(strtolower(trim($data['requires_prescription'] ?? '0')), ['1', 'true', 'yes']),
            'is_active' => true,
            'is_featured' => in_array(strtolower(trim($data['is_featured'] ?? '0')), ['1', 'true', 'yes']),
            'tags' => $tags ?: null,
            'tabs' => $tabs ?: null,
            'low_stock_alert' => (int) ($data['low_stock_alert'] ?? 10),
            'thumbnail' => $this->resolveThumbnail($data['thumbnail'] ?? ''),
        ];

        if ($exists) {
            $exists->update($payload);
            if ($categoryId) {
                try {
                    $exists->categories()->syncWithoutDetaching([$categoryId => ['is_primary' => true]]);
                } catch (\Exception) {
                }
            }
            return 'updated';
        }

        $product = Product::create($payload);
        if ($categoryId) {
            try {
                $product->categories()->sync([$categoryId => ['is_primary' => true]]);
            } catch (\Exception) {
            }
        }
        return 'created';
    }
}