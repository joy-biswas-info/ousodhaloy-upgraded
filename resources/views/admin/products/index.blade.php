{{-- ============================================================
resources/views/admin/products/index.blade.php
============================================================ --}}
@extends('layouts.admin')
@section('page-title', 'Products')
@section('content')
    <div class="space-y-4">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <form method="GET" class="flex flex-wrap gap-2">
                <input type="text" name="q" value="{{ request('q') }}" class="form-input w-56"
                    placeholder="Search products...">
                <select name="status" class="form-select w-36">
                    <option value="">All Status</option>
                    <option value="active" @selected(request('status') == 'active')>Active</option>
                    <option value="inactive" @selected(request('status') == 'inactive')>Inactive</option>
                    <option value="featured" @selected(request('status') == 'featured')>Featured</option>
                    <option value="flash_sale" @selected(request('status') == 'flash_sale')>Flash Sale</option>
                    <option value="low_stock" @selected(request('status') == 'low_stock')>Low Stock</option>
                    <option value="out_of_stock" @selected(request('status') == 'out_of_stock')>Out of Stock</option>
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.products.index') }}" class="btn-outline btn-sm">Reset</a>
            </form>
            <a href="{{ route('admin.products.trash') }}" class="btn-outline mr-2 text-red-500">
                <i class="fas fa-trash-alt mr-1"></i> Trash
            </a>
            <a href="{{ route('admin.products.bulk') }}" class="btn-outline mr-2">
                <i class="fas fa-file-import mr-1"></i> Bulk Import
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> Add Product
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Sales</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $p)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center overflow-hidden">
                                            @if($p->thumbnail)
                                                <img src="{{ $p->thumbnail_url }}" class="max-h-full max-w-full object-contain">
                                            @else <span class="text-lg">💊</span> @endif
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm text-gray-800">{{ Str::limit($p->name, 40) }}</p>
                                            <p class="text-xs text-gray-400">{{ $p->generic_name }} · {{ $p->brand?->name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-xs text-gray-500">{{ $p->category?->name ?? '—' }}</td>
                                <td>
                                    <p class="font-bold text-sm text-teal-700">৳{{ number_format($p->effective_price, 2) }}</p>
                                    @if($p->mrp && $p->mrp > $p->effective_price)
                                        <p class="text-xs text-gray-400 line-through">৳{{ number_format($p->mrp, 2) }}</p>
                                    @endif
                                </td>
                                <td>
                                    <span
                                        class="{{ $p->stock == 0 ? 'text-red-600 font-bold' : ($p->stock <= $p->low_stock_alert ? 'text-orange-600 font-bold' : 'text-gray-700') }}">
                                        {{ $p->stock }} {{ $p->unit }}
                                    </span>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1">
                                        <span
                                            class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $p->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">{{ $p->is_active ? 'Active' : 'Hidden' }}</span>
                                        @if($p->is_featured) <span
                                            class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full font-semibold">★
                                        Featured</span> @endif
                                        @if($p->is_flash_sale) <span
                                            class="text-xs bg-orange-100 text-orange-700 px-2 py-0.5 rounded-full font-semibold">⚡
                                        Sale</span> @endif
                                        @if($p->requires_prescription) <span
                                            class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">Rx</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-sm text-gray-600">{{ number_format($p->total_sold) }}</td>
                                <td>
                                    <div class="flex gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.products.edit', $p) }}" class="btn-secondary btn-sm">Edit</a>
                                        <a href="{{ route('shop.product', $p->slug) }}" target="_blank"
                                            class="btn-outline btn-sm"><i class="fas fa-external-link-alt"></i></a>
                                        <form method="POST" action="{{ route('admin.products.destroy', $p) }}"
                                            onsubmit="return confirm('Move to trash?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" title="Move to Trash"><i
                                                    class="fas fa-trash-alt"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">No products found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        {{ $products->withQueryString()->links() }}
    </div>
@endsection