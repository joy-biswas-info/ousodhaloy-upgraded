<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // This route is admin-only (see routes/web.php) because it can grant
        // admin/manager access — never trust that alone, validate here too.
        if ($user->is(Auth::user())) {
            return back()->with('error', "You can't change your own role.");
        }

        $data = ['is_active' => $request->boolean('is_active', true)];
        if ($request->filled('role')) {
            $data['role'] = $request->validate([
                'role' => 'in:customer,manager,admin',
            ])['role'];
        }

        $user->update($data);
        return back()->with('success', 'User updated.');
    }
}