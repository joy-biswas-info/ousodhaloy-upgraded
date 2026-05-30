@extends('layouts.admin')
@section('page-title', 'Brands')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Brands list --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-4 border-b border-teal-100 font-bold text-gray-800 flex justify-between items-center">
                <span>All Brands ({{ $brands->total() }})</span>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Brand</th>
                        <th>Country</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($brands as $brand)
                    <tr x-data="{ editing: false }">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if($brand->logo)
                                        <img src="{{ $brand->logo_url }}" class="max-h-full max-w-full object-contain">
                                    @else
                                        <i class="fas fa-flask text-gray-400 text-xs"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-gray-800">{{ $brand->name }}</p>
                                    <p class="text-xs text-gray-400 font-mono">{{ $brand->slug }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="text-sm text-gray-500">{{ $brand->country ?? '—' }}</td>
                        <td class="text-sm text-gray-600">{{ $brand->products_count }}</td>
                        <td>
                            <span class="text-xs font-semibold {{ $brand->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $brand->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1.5" x-show="!editing">
                                <button @click="editing = true" class="btn-secondary btn-sm">Edit</button>
                                <form method="POST" action="{{ route('admin.brands.update', $brand) }}" x-show="false" x-ref="toggleForm">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="name" value="{{ $brand->name }}">
                                    <input type="hidden" name="is_active" :value="{{ $brand->is_active ? 'false' : 'true' }}">
                                </form>
                                <button @click="$refs.toggleForm.submit()" class="btn-outline btn-sm text-xs">
                                    {{ $brand->is_active ? 'Hide' : 'Show' }}
                                </button>
                            </div>
                            <form x-show="editing" method="POST" action="{{ route('admin.brands.update', $brand) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $brand->name }}" class="form-input" style="width:140px">
                                <input type="text" name="country" value="{{ $brand->country }}" class="form-input" style="width:100px" placeholder="Country">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                                <button type="button" @click="editing = false" class="btn-outline btn-sm">Cancel</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-10 text-gray-400">No brands found</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="p-4">{{ $brands->links() }}</div>
        </div>
    </div>

    {{-- Add brand --}}
    <div class="bg-white rounded-xl border p-5">
        <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Brand</h2>
        <form method="POST" action="{{ route('admin.brands.store') }}" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div>
                <label class="form-label">Brand Name *</label>
                <input type="text" name="name" class="form-input" placeholder="Square Pharmaceuticals" required>
            </div>
            <div>
                <label class="form-label">Country</label>
                <input type="text" name="country" value="Bangladesh" class="form-input" placeholder="Bangladesh">
            </div>
            <div>
                <label class="form-label">Logo (optional)</label>
                <input type="file" name="logo" accept="image/*" class="form-input py-1.5">
            </div>
            <button type="submit" class="btn-primary w-full">Add Brand</button>
        </form>

        <div class="mt-5 pt-5 border-t">
            <p class="text-xs text-gray-500 mb-2 font-semibold">🇧🇩 Top BD Pharma Companies:</p>
            <div class="space-y-1 text-xs text-gray-500">
                @foreach(['Square Pharmaceuticals','Beximco Pharma','Renata Limited','ACI Limited','Incepta Pharma','Drug International','Aristopharma','Eskayef','Opsonin Pharma','Globe Pharma'] as $b)
                <p>• {{ $b }}</p>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
