<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Wishlist};
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function toggleWishlist(Request $request, Product $product)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $wishlisted = false;
            $msg = 'Removed from wishlist';
        } else {
            Wishlist::create(['user_id' => auth()->id(), 'product_id' => $product->id]);
            $wishlisted = true;
            $msg = 'Added to wishlist';
        }

        if ($request->wantsJson()) {
            return response()->json(['wishlisted' => $wishlisted, 'message' => $msg]);
        }
        return back()->with('success', $msg);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|unique:users,email,' . auth()->id(),
        ]);

        auth()->user()->update($request->only('name', 'email'));
        return back()->with('success', 'Profile updated.');
    }
}