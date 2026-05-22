@extends('layouts.admin')
@section('page-title', 'Banners')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Banners list --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="px-5 py-4 border-b font-bold text-gray-800">All Banners ({{ $banners->total() }})</div>
                <div class="divide-y">
                    @forelse($banners as $banner)
                        <div class="p-4 flex items-start gap-4">
                            <div class="w-24 h-14 rounded-lg overflow-hidden flex-shrink-0 flex items-center justify-center text-white font-bold text-sm"
                                style="background: {{ $banner->bg_color }}">
                                @if($banner->image_url)
                                    <img src="{{ $banner->image_url }}" class="w-full h-full object-cover" alt="">
                                @else
                                    <span class="text-xs opacity-75">No Image</span>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2">
                                    <div>
                                        <p class="font-semibold text-sm text-gray-800">{{ $banner->title }}</p>
                                        @if($banner->subtitle)
                                        <p class="text-xs text-gray-500 truncate">{{ $banner->subtitle }}</p>@endif
                                        <div class="flex items-center gap-2 mt-1">
                                            <span
                                                class="text-[10px] bg-gray-100 text-gray-600 px-2 py-0.5 rounded font-semibold uppercase">{{ $banner->position }}</span>
                                            <span
                                                class="text-[10px] font-semibold {{ $banner->is_active ? 'text-green-600' : 'text-gray-400' }}">{{ $banner->is_active ? 'Active' : 'Hidden' }}</span>
                                            @if($banner->link_url)<span class="text-[10px] text-blue-500 truncate max-w-xs">→
                                            {{ $banner->link_url }}</span>@endif
                                        </div>
                                    </div>
                                    <form method="POST" action="{{ route('admin.settings.banners.destroy', $banner) }}"
                                        onsubmit="return confirm('Delete banner?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-danger btn-sm text-xs flex-shrink-0">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-gray-400">No banners yet. Add one →</div>
                    @endforelse
                </div>
                <div class="p-4">{{ $banners->links() }}</div>
            </div>
        </div>

        {{-- Add banner form --}}
        <div class="bg-white rounded-xl border p-5">
            <h2 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Banner</h2>
            <form method="POST" action="{{ route('admin.settings.banners.store') }}" enctype="multipart/form-data"
                class="space-y-3">
                @csrf
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
                        @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
                    </div>
                @endif

                <div>
                    <label class="form-label">Title *</label>
                    <input type="text" name="title" class="form-input" placeholder="Banner headline" required>
                </div>
                <div>
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-input" placeholder="Supporting text">
                </div>
                <div>
                    <label class="form-label">Image *</label>
                    <input type="file" name="image" accept="image/*" class="form-input py-1.5" required>
                    <p class="text-xs text-gray-400 mt-1">Recommended: 1200×400px for hero banners</p>
                </div>
                <div>
                    <label class="form-label">Position *</label>
                    <select name="position" class="form-select" required>
                        <option value="hero">Hero (Main Slider)</option>
                        <option value="promo">Promo (Mid-page)</option>
                        <option value="sidebar">Sidebar</option>
                        <option value="popup">Popup</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Link URL</label>
                    <input type="text" name="link_url" class="form-input" placeholder="/shop?category=medicine">
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="form-label">Button Text</label>
                        <input type="text" name="button_text" class="form-input" placeholder="Shop Now" value="Shop Now">
                    </div>
                    <div>
                        <label class="form-label">Badge Text</label>
                        <input type="text" name="badge_text" class="form-input" placeholder="⚡ Flash Sale">
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="form-label">Background Color</label>
                        <input type="color" name="bg_color" value="#0e7673" class="form-input h-10 p-1 cursor-pointer">
                    </div>
                    <div>
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" value="0" class="form-input">
                    </div>
                </div>
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="accent-teal-600">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <button type="submit" class="btn-primary w-full">Upload Banner</button>
            </form>
        </div>
    </div>
@endsection