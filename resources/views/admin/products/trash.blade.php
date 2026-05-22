@extends('layouts.admin')
@section('page-title', 'Product Trash')
@section('breadcrumb', 'Products / Trash')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ $products->total() }} deleted products — restore or permanently delete.</p>
            <a href="{{ route('admin.products.index') }}" class="btn-outline btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Products
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Deleted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                        <tr class="opacity-75 hover:opacity-100">
                            <td>
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-xl flex-shrink-0">
                                        @if($p->thumbnail)
                                            <img src="{{ $p->thumbnail_url }}" class="max-h-full max-w-full object-contain">
                                        @else 💊 @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm text-gray-700 line-through">{{ $p->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $p->brand?->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="text-xs text-gray-500">{{ $p->category?->name ?? '—' }}</td>
                            <td class="text-sm font-semibold text-gray-600">৳{{ number_format($p->price, 2) }}</td>
                            <td class="text-xs text-gray-400">{{ $p->deleted_at->diffForHumans() }}</td>
                            <td>
                                <div class="flex gap-1.5">
                                    <form method="POST" action="{{ route('admin.products.restore', $p->id) }}">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn-secondary btn-sm">
                                            <i class="fas fa-undo mr-1"></i>Restore
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.products.force-delete', $p->id) }}"
                                        onsubmit="return confirm('Permanently delete {{ addslashes($p->name) }}? This cannot be undone.')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm">
                                            <i class="fas fa-trash mr-1"></i>Delete Forever
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-16 text-gray-400">
                                <i class="fas fa-trash-alt text-4xl mb-3 block opacity-30"></i>
                                <p class="font-semibold">Trash is empty</p>
                                <p class="text-xs mt-1">Deleted products appear here</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $products->links() }}</div>
        </div>
    </div>
@endsection