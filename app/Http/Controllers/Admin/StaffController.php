<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index()
    {
        $staff = User::whereIn('role', ['admin', 'manager'])
            ->withCount('orders')
            ->latest()
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20|unique:users,phone',
            'role' => 'required|in:admin,manager',
            'password' => ['required', Password::min(8)],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'referral_code' => strtoupper(Str::random(8)),
        ]);

        return back()->with('success', 'Staff member created successfully.');
    }

    public function update(Request $request, User $user)
    {
        // Prevent editing customers via this controller
        if (!$user->isManager()) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20|unique:users,phone,' . $user->id,
            'role' => 'required|in:admin,manager',
        ]);

        $data = $request->only('name', 'email', 'phone', 'role');
        $data['is_active'] = $request->boolean('is_active', true);

        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate(['password' => [Password::min(8)]]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Staff updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }
        if (!$user->isManager()) {
            abort(403);
        }
        $user->delete();
        return back()->with('success', 'Staff member removed.');
    }
}