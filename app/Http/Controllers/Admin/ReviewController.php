<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ProductReview, Product};
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = ProductReview::with('product', 'user')->latest();

        if ($request->filled('status')) {
            $query->where('is_approved', $request->status === 'approved');
        }
        if ($request->filled('q')) {
            $term = $request->q;
            $query->where(function ($sub) use ($term) {
                $sub->whereHas('product', fn($q) => $q->where('name', 'like', "%{$term}%"))
                    ->orWhereHas('user', fn($q) => $q->where('name', 'like', "%{$term}%"))
                    ->orWhere('reviewer_name', 'like', "%{$term}%");
            });
        }

        $reviews = $query->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'nullable|exists:users,id',
            'reviewer_name' => 'required_without:user_id|nullable|string|max:100',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:120',
            'body' => 'required|string|max:2000',
            'is_approved' => 'boolean',
        ]);

        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id ?: null,
            'reviewer_name' => $request->user_id ? null : $request->reviewer_name,
            'source' => $request->user_id ? 'site' : 'imported',
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'is_approved' => $request->boolean('is_approved', true),
        ]);

        $review->product->updateRating();
        return back()->with('success', 'Review added.');
    }

    // ── Bulk import (e.g. real reviews carried over from a parent site) ────

    public function importCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');
        $headers = null;
        $imported = 0;
        $errors = [];
        $lineNum = 0;
        $touchedProductIds = [];

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNum++;

            if ($headers === null) {
                $headers = array_map('trim', $row);
                continue;
            }
            if (empty(array_filter($row, fn($v) => trim($v) !== ''))) {
                continue;
            }

            $headerCount = count($headers);
            $row = array_pad(array_slice($row, 0, $headerCount), $headerCount, '');
            $data = array_combine($headers, $row);

            try {
                $product = $this->resolveProduct($data);
                $rating = (int) ($data['rating'] ?? 0);
                $body = trim($data['body'] ?? '');

                if ($rating < 1 || $rating > 5) {
                    throw new \Exception('Rating must be 1-5');
                }
                if ($body === '') {
                    throw new \Exception('Review body is required');
                }

                $reviewedAt = !empty(trim($data['reviewed_at'] ?? ''))
                    ? \Illuminate\Support\Carbon::parse(trim($data['reviewed_at']))
                    : now();

                $review = ProductReview::create([
                    'product_id' => $product->id,
                    'user_id' => null,
                    'reviewer_name' => trim($data['reviewer_name'] ?? '') ?: 'Verified Customer',
                    'source' => 'imported',
                    'reviewed_at' => $reviewedAt,
                    'rating' => $rating,
                    'title' => trim($data['title'] ?? '') ?: null,
                    'body' => $body,
                    'is_approved' => !array_key_exists('is_approved', $data)
                        || in_array(strtolower(trim($data['is_approved'])), ['1', 'true', 'yes', '']),
                ]);
                // Backdate created_at to the review's real date so imported
                // batches don't all look like they landed the same day.
                $review->created_at = $reviewedAt;
                $review->save();

                $touchedProductIds[$product->id] = true;
                $imported++;
            } catch (\Exception $e) {
                $errors[] = "Row {$lineNum}: " . $e->getMessage();
                if (count($errors) >= 20) {
                    $errors[] = '… too many errors, stopping early.';
                    break;
                }
            }
        }
        fclose($handle);

        foreach (array_keys($touchedProductIds) as $productId) {
            Product::find($productId)?->updateRating();
        }

        $msg = "Import complete: {$imported} review(s) added.";
        if ($errors) {
            $msg .= "\n\nErrors:\n" . implode("\n", $errors);
            return back()->with('error', $msg);
        }
        return back()->with('success', $msg);
    }

    public function importTemplate()
    {
        $headers = ['product_sku', 'product_name', 'reviewer_name', 'rating', 'title', 'body', 'reviewed_at', 'is_approved'];
        $sample = ['NAP500', 'Napa 500mg', 'Rafiq Ahmed', '5', 'Works great', 'Fast relief, will buy again.', '2025-11-02', '1'];

        $tmp = fopen('php://temp', 'r+');
        fputcsv($tmp, $headers);
        fputcsv($tmp, $sample);
        rewind($tmp);
        $csv = stream_get_contents($tmp);
        fclose($tmp);

        return response($csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="reviews_template.csv"',
        ]);
    }

    private function resolveProduct(array $data): Product
    {
        $sku = trim($data['product_sku'] ?? '');
        if ($sku) {
            $product = Product::where('sku', $sku)->first();
            if ($product) {
                return $product;
            }
        }

        $name = trim($data['product_name'] ?? '');
        if ($name) {
            $product = Product::where('name', $name)->first();
            if ($product) {
                return $product;
            }
        }

        throw new \Exception('Product not found (checked product_sku, then product_name)');
    }

    public function update(Request $request, ProductReview $review)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:120',
            'body' => 'required|string|max:2000',
            'is_approved' => 'boolean',
        ]);

        $review->update([
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'is_approved' => $request->boolean('is_approved'),
        ]);

        $review->product->updateRating();
        return back()->with('success', 'Review updated.');
    }

    public function approve(ProductReview $review)
    {
        $review->update(['is_approved' => !$review->is_approved]);
        $review->product->updateRating();
        return back()->with('success', $review->is_approved ? 'Review approved.' : 'Review hidden.');
    }

    public function destroy(ProductReview $review)
    {
        $product = $review->product;
        $review->delete();
        $product->updateRating();
        return back()->with('success', 'Review deleted.');
    }
}