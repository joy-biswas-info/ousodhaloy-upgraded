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
            $query->whereHas('product', fn($q) => $q->where('name', 'like', '%' . $request->q . '%'))
                ->orWhereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->q . '%'));
        }

        $reviews = $query->paginate(20)->withQueryString();
        return view('admin.reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'user_id' => 'required|exists:users,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:120',
            'body' => 'required|string|max:2000',
            'is_approved' => 'boolean',
        ]);

        $review = ProductReview::create([
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rating' => $request->rating,
            'title' => $request->title,
            'body' => $request->body,
            'is_approved' => $request->boolean('is_approved', true),
        ]);

        $review->product->updateRating();
        return back()->with('success', 'Review added.');
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