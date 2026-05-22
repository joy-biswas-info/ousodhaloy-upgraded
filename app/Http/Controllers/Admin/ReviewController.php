<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = ProductReview::with('product', 'user')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(ProductReview $r)
    {
        $r->update(['is_approved' => !$r->is_approved]);
        $r->product->updateRating();
        return back()->with('success', $r->is_approved ? 'Review approved.' : 'Review hidden.');
    }

    public function destroy(ProductReview $r)
    {
        $r->product->updateRating();
        $r->delete();
        return back()->with('success', 'Review deleted.');
    }
}