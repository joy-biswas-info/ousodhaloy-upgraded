@extends('layouts.admin')
@section('page-title', 'Customer: ' . $user->name)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Profile --}}
    <div class="space-y-4">
        <div class="bg-white rounded-xl border p-5 text-center">
            <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center text-teal-700 font-black text-2xl mx-auto mb-3">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <h2 class="font-bold text-gray-800 text-lg">{{ $user->name }}</h2>
            <p class="text-sm text-gray-500">{{ $user->email ?? $user->phone }}</p>
            <div class="flex justify-center gap-2 mt-2">
                <span class="text-xs font-semibold px-2 py-1 rounded-full {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                    {{ $user->is_active ? 'Active' : 'Suspended' }}
                </span>
                <span class="text-xs bg-blue-100 text-blue-700 font-semibold px-2 py-1 rounded-full capitalize">{{ $user->role }}</span>
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Contact Info</h3>
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-phone text-teal-500 w-4 text-center"></i>
                    {{ $user->phone ?? '—' }}
                    @if($user->phone_verified_at)<i class="fas fa-check-circle text-green-500 text-xs ml-1"></i>@endif
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-envelope text-teal-500 w-4 text-center"></i>
                    {{ $user->email ?? '—' }}
                </div>
                <div class="flex items-center gap-2">
                    <i class="fas fa-calendar text-teal-500 w-4 text-center"></i>
                    Joined {{ $user->created_at->format('d M Y') }}
                </div>
                @if($user->referral_code)
                <div class="flex items-center gap-2">
                    <i class="fas fa-tag text-teal-500 w-4 text-center"></i>
                    Ref: <span class="font-mono font-bold">{{ $user->referral_code }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Loyalty Points</h3>
            <p class="text-3xl font-black text-teal-700">{{ number_format($user->total_loyalty_points) }}</p>
            <p class="text-xs text-gray-400 mt-0.5">≈ ৳{{ number_format($user->total_loyalty_points / 10, 2) }} value</p>
        </div>

        <div class="bg-white rounded-xl border p-5">
            <h3 class="font-bold text-gray-800 text-sm mb-3">Actions</h3>
            <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-2">
                @csrf @method('PATCH')
                <select name="role" class="form-select text-sm">
                    @foreach(['customer','manager','admin'] as $role)
                    <option value="{{ $role }}" @selected($user->role === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }} class="accent-teal-600">
                    <span class="text-sm text-gray-700">Account Active</span>
                </label>
                <button type="submit" class="btn-primary w-full btn-sm">Update Account</button>
            </form>
        </div>
    </div>

    {{-- Orders --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold text-gray-800">
                Order History ({{ $user->orders->count() }})
            </div>
            <table class="admin-table">
                <thead>
                    <tr><th>Order</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Date</th><th></th></tr>
                </thead>
                <tbody>
                    @forelse($user->orders as $order)
                    <tr>
                        <td class="font-mono text-xs font-bold text-teal-700">{{ $order->order_number }}</td>
                        <td class="text-xs text-gray-500">{{ $order->items->count() }}</td>
                        <td class="font-bold text-sm">৳{{ number_format($order->total, 0) }}</td>
                        <td>
                            <span class="text-xs font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                        <td class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline btn-sm">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-8 text-gray-400 text-sm">No orders yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
