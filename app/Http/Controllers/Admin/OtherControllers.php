<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{User, Prescription, ProductReview};
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request) {
        $users = User::where('role','customer')
            ->when($request->q, fn($q,$s) => $q->where('name','like',"%$s%")->orWhere('phone','like',"%$s%")->orWhere('email','like',"%$s%"))
            ->withCount('orders')->latest()->paginate(20);
        return view('admin.users.index', compact('users'));
    }
    public function show(User $user) {
        $user->load(['orders' => fn($q) => $q->latest()->take(10)]);
        return view('admin.users.show', compact('user'));
    }
    public function update(Request $request, User $user) {
        $user->update(['is_active' => $request->boolean('is_active', true), 'role' => $request->role ?? $user->role]);
        return back()->with('success', 'User updated.');
    }
}

class PrescriptionController extends Controller
{
    public function index() {
        $prescriptions = Prescription::with('order')->latest()->paginate(20);
        return view('admin.prescriptions.index', compact('prescriptions'));
    }
    public function review(Request $request, Prescription $p) {
        $p->update(['status' => $request->status, 'admin_note' => $request->note]);
        return back()->with('success', 'Prescription reviewed.');
    }
}

class ReviewController extends Controller
{
    public function index() {
        $reviews = ProductReview::with('product','user')->latest()->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }
    public function approve(ProductReview $r) {
        $r->update(['is_approved' => !$r->is_approved]);
        $r->product->updateRating();
        return back()->with('success', $r->is_approved ? 'Review approved.' : 'Review hidden.');
    }
    public function destroy(ProductReview $r) {
        $r->product->updateRating();
        $r->delete();
        return back()->with('success', 'Review deleted.');
    }
}
