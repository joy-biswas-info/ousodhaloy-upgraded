{{-- resources/views/admin/settings/promos.blade.php --}}
@extends('layouts.admin')
@section('page-title', 'Promo Codes')
@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-4 border-b font-bold text-gray-800">All Promo Codes</div>
            <table class="admin-table">
                <thead><tr><th>Code</th><th>Type</th><th>Value</th><th>Min Order</th><th>Used/Limit</th><th>Expires</th><th>Status</th><th>Action</th></tr></thead>
                <tbody>
                    @forelse($promos as $p)
                    <tr>
                        <td class="font-mono font-bold text-teal-700">{{ $p->code }}</td>
                        <td><span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-semibold">{{ $p->type }}</span></td>
                        <td class="font-semibold">{{ $p->type === 'percent' ? $p->value.'%' : '৳'.$p->value }}</td>
                        <td class="text-xs text-gray-500">৳{{ $p->min_order }}</td>
                        <td class="text-xs text-gray-500">{{ $p->used_count }} / {{ $p->usage_limit ?? '∞' }}</td>
                        <td class="text-xs text-gray-500">{{ $p->expires_at?->format('d M Y') ?? 'Never' }}</td>
                        <td><span class="text-xs font-bold {{ $p->is_active ? 'text-green-600' : 'text-gray-400' }}">{{ $p->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td>
                            <div class="flex gap-1.5">
                                <form action="{{ route('admin.settings.promos.toggle', $p) }}" method="POST" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="{{ $p->is_active ? 'btn-danger' : 'btn-secondary' }} btn-sm">{{ $p->is_active ? 'Disable' : 'Enable' }}</button>
                                </form>
                                <form action="{{ route('admin.settings.promos.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Delete?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-outline btn-sm text-red-600">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-8 text-gray-400">No promo codes yet</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $promos->links() }}</div>
        </div>
    </div>

    <div class="bg-white rounded-xl border p-5">
        <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Create Promo Code</h2>
        <form method="POST" action="{{ route('admin.settings.promos.store') }}" class="space-y-3">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>
            @endif
            <div><label class="form-label">Code *</label><input type="text" name="code" class="form-input uppercase" placeholder="WELCOME20" required></div>
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select"><option value="percent">Percent (%)</option><option value="fixed">Fixed (৳)</option></select>
                </div>
                <div><label class="form-label">Value *</label><input type="number" name="value" step="0.01" class="form-input" placeholder="20" required></div>
                <div><label class="form-label">Min Order (৳)</label><input type="number" name="min_order" class="form-input" placeholder="0"></div>
                <div><label class="form-label">Max Discount (৳)</label><input type="number" name="max_discount" class="form-input" placeholder="Optional"></div>
                <div><label class="form-label">Usage Limit</label><input type="number" name="usage_limit" class="form-input" placeholder="∞"></div>
                <div><label class="form-label">Per User</label><input type="number" name="per_user_limit" value="1" class="form-input"></div>
            </div>
            <div><label class="form-label">Expires At</label><input type="datetime-local" name="expires_at" class="form-input"></div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="first_order_only" value="1" class="accent-teal-600">
                <span class="text-sm text-gray-700">First order only</span>
            </label>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
            <button type="submit" class="btn-primary w-full">Create Promo Code</button>
        </form>
    </div>
</div>
@endsection
