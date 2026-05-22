@extends('layouts.admin')
@section('page-title', 'Categories')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Categories list --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="px-5 py-4 border-b flex items-center justify-between">
                <h2 class="font-bold text-gray-800">All Categories</h2>
                <span class="text-xs text-gray-400">{{ $categories->count() }} total</span>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Products</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr x-data="{ editing: false }">
                        <td class="text-2xl">{{ $cat->icon }}</td>
                        <td>
                            <span x-show="!editing" class="font-semibold text-sm text-gray-800">{{ $cat->name }}</span>
                            <form x-show="editing" method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex gap-2">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $cat->name }}" class="form-input" style="width:140px">
                                <input type="text" name="icon" value="{{ $cat->icon }}" class="form-input" style="width:50px" maxlength="5">
                                <input type="number" name="sort_order" value="{{ $cat->sort_order }}" class="form-input" style="width:60px">
                                <input type="hidden" name="is_active" value="{{ $cat->is_active ? 'true' : 'false' }}">
                                <button type="submit" class="btn-primary btn-sm">Save</button>
                            </form>
                        </td>
                        <td class="text-xs text-gray-400 font-mono">{{ $cat->slug }}</td>
                        <td class="text-sm text-gray-600">{{ $cat->products_count }}</td>
                        <td class="text-sm text-gray-500">{{ $cat->sort_order }}</td>
                        <td>
                            <span class="text-xs font-semibold {{ $cat->is_active ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $cat->is_active ? 'Active' : 'Hidden' }}
                            </span>
                        </td>
                        <td>
                            <div class="flex gap-1.5">
                                <button @click="editing = !editing" class="btn-secondary btn-sm">
                                    <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                                </button>
                                <a href="{{ route('shop.index', ['category' => $cat->slug]) }}" target="_blank" class="btn-outline btn-sm">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-10 text-gray-400">No categories found</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Add new category --}}
    <div class="bg-white rounded-xl border p-5">
        <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Category</h2>
        <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
            @csrf
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
            @endif

            <div>
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-input" placeholder="e.g. Baby Care" required>
            </div>
            <div>
                <label class="form-label">Icon (emoji)</label>
                <input type="text" name="icon" class="form-input" placeholder="👶" maxlength="5" value="💊">
            </div>
            <div>
                <label class="form-label">Slug (auto-generated)</label>
                <input type="text" name="slug" class="form-input" placeholder="baby-care">
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="2" class="form-input resize-none" placeholder="Optional description"></textarea>
            </div>
            <div>
                <label class="form-label">Sort Order</label>
                <input type="number" name="sort_order" value="0" class="form-input">
            </div>
            <label class="flex items-center gap-2 cursor-pointer">
                <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                <span class="text-sm text-gray-700">Active</span>
            </label>
            <button type="submit" class="btn-primary w-full">Add Category</button>
        </form>

        <div class="mt-5 pt-5 border-t">
            <p class="text-xs text-gray-500 mb-2 font-semibold">Common BD Pharmacy Categories:</p>
            <div class="flex flex-wrap gap-1.5">
                @foreach(['💊 Medicine','🧪 Vitamins','👶 Baby & Mother','🩸 Diabetes','❤️ Heart','🧴 Skin Care','👁️ Eye & Ear','🦷 Dental','💪 Fitness','🌿 Herbal','🩺 Devices','🛁 Personal Care'] as $c)
                <span class="text-xs bg-gray-100 px-2 py-1 rounded cursor-pointer hover:bg-teal-50" onclick="document.querySelector('[name=name]').value='{{ explode(' ',trim($c),2)[1] ?? $c }}'; document.querySelector('[name=icon]').value='{{ substr(trim($c),0,2) }}'">{{ $c }}</span>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
