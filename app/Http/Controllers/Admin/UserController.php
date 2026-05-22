<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', 'customer')
            ->when($request->q, fn($q, $s) => $q->where('name', 'like', "%$s%")
                ->orWhere('phone', 'like', "%$s%")
                ->orWhere('email', 'like', "%$s%"))
            ->withCount('orders')
            ->latest()
            ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['orders' => fn($q) => $q->latest()->take(10)]);
        return view('admin.users.show', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $user->update([
            'is_active' => $request->boolean('is_active', true),
            'role' => $request->role ?? $user->role,
        ]);
        return back()->with('success', 'User updated.');
    }
}