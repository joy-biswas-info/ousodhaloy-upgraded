@extends('layouts.admin')
@section('page-title', 'Product Reviews')

@section('content')
<div class="space-y-5">

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-3">
        @foreach([
            ['Total Reviews',    \App\Models\ProductReview::count(),                            'blue',   'star'],
            ['Pending Approval', \App\Models\ProductReview::where('is_approved',false)->count(),'yellow', 'clock'],
            ['Approved',         \App\Models\ProductReview::where('is_approved',true)->count(), 'green',  'check-circle'],
        ] as [$label, $value, $color, $icon])
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-{{ $color }}-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fas fa-{{ $icon }} text-{{ $color }}-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">{{ $label }}</p>
                <p class="text-xl font-black text-gray-800">{{ $value }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Bulk import --}}
    <div class="bg-white rounded-xl border p-5">
        <div class="flex items-start justify-between flex-wrap gap-3 mb-3">
            <div>
                <h3 class="font-bold text-gray-800">Bulk Import Reviews</h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Carry over real reviews from your parent site — CSV, matched by SKU or product name.
                    Imported rows are tagged <span class="font-semibold text-indigo-500">Imported</span> in the list below.
                </p>
            </div>
            <a href="{{ route('admin.reviews.import-template') }}" class="btn-outline btn-sm text-xs whitespace-nowrap">
                <i class="fas fa-download mr-1"></i>Download CSV template
            </a>
        </div>
        <form method="POST" action="{{ route('admin.reviews.import-csv') }}" enctype="multipart/form-data"
            class="flex items-center gap-3 flex-wrap">
            @csrf
            <input type="file" name="csv_file" accept=".csv,.txt" required class="form-input flex-1 min-w-[200px]">
            <button type="submit" class="btn-primary btn-sm">
                <i class="fas fa-upload mr-1.5"></i>Import
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Review list --}}
        <div class="lg:col-span-2 space-y-4">

            {{-- Filters --}}
            <form method="GET" class="flex gap-2 flex-wrap">
                <input type="text" name="q" value="{{ request('q') }}"
                    class="form-input w-48" placeholder="Search product or customer…">
                <select name="status" class="form-select w-36">
                    <option value="">All Status</option>
                    <option value="pending"  @selected(request('status')==='pending')>Pending</option>
                    <option value="approved" @selected(request('status')==='approved')>Approved</option>
                </select>
                <button type="submit" class="btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.reviews') }}" class="btn-outline btn-sm">Reset</a>
            </form>

            {{-- Table --}}
            <div class="bg-white rounded-xl border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reviews as $review)
                            <tr x-data="{ editing: false }">
                                <td>
                                    <a href="{{ route('shop.product', $review->product->slug) }}"
                                        target="_blank"
                                        class="font-semibold text-sm text-gray-800 hover:text-teal-700">
                                        {{ Str::limit($review->product->name, 28) }}
                                    </a>
                                </td>
                                <td>
                                    <p class="font-semibold text-sm">{{ $review->display_name }}</p>
                                    @if($review->is_imported)
                                        <span class="text-[10px] font-semibold text-indigo-500 bg-indigo-50 rounded px-1.5 py-0.5">Imported</span>
                                    @else
                                        <p class="text-xs text-gray-400">{{ $review->user->phone ?? '—' }}</p>
                                    @endif
                                </td>
                                <td>
                                    <div class="flex gap-0.5">
                                        @for($i=1; $i<=5; $i++)
                                        <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200' }}"></i>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $review->rating }}/5</span>
                                </td>
                                <td class="max-w-xs">
                                    {{-- View mode --}}
                                    <div x-show="!editing">
                                        @if($review->title)
                                        <p class="font-semibold text-sm text-gray-800">{{ $review->title }}</p>
                                        @endif
                                        @if($review->body)
                                        <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $review->body }}</p>
                                        @endif
                                    </div>
                                    {{-- Edit mode --}}
                                    <form method="POST" action="{{ route('admin.reviews.update', $review) }}"
                                        x-show="editing" x-cloak class="space-y-2 py-1">
                                        @csrf @method('PATCH')
                                        <div class="flex gap-0.5" x-data="{ r: {{ $review->rating }} }">
                                            @for($i=1; $i<=5; $i++)
                                            <button type="button" @click="r={{ $i }}"
                                                class="text-lg leading-none focus:outline-none">
                                                <i class="fas fa-star" :class="r >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"></i>
                                            </button>
                                            @endfor
                                            <input type="hidden" name="rating" :value="r">
                                        </div>
                                        <input type="text" name="title" value="{{ $review->title }}"
                                            class="form-input py-1 text-xs w-full" placeholder="Title">
                                        <textarea name="body" rows="2"
                                            class="form-input py-1 text-xs w-full resize-none"
                                            placeholder="Review body" required>{{ $review->body }}</textarea>
                                        <label class="flex items-center gap-1.5 cursor-pointer">
                                            <input type="checkbox" name="is_approved" value="1"
                                                {{ $review->is_approved ? 'checked' : '' }}
                                                class="accent-teal-600">
                                            <span class="text-xs text-gray-600">Approved</span>
                                        </label>
                                        <div class="flex gap-1.5">
                                            <button type="submit" class="btn-primary btn-sm text-xs flex-1">Save</button>
                                            <button type="button" @click="editing=false" class="btn-outline btn-sm text-xs flex-1">Cancel</button>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <span class="text-xs font-semibold {{ $review->is_approved ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ $review->is_approved ? '✅ Approved' : '⏳ Pending' }}
                                    </span>
                                </td>
                                <td class="text-xs text-gray-400 whitespace-nowrap">{{ $review->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="flex gap-1.5 flex-wrap" x-show="!editing">
                                        <button type="button" @click="editing=true"
                                            class="btn-secondary btn-sm text-xs">
                                            <i class="fas fa-edit text-[10px]"></i>
                                        </button>
                                        <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                            @csrf @method('PATCH')
                                            <button type="submit"
                                                class="{{ $review->is_approved ? 'btn-outline' : 'btn-secondary' }} btn-sm text-xs">
                                                {{ $review->is_approved ? 'Hide' : 'Approve' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}"
                                            class="inline" onsubmit="return confirm('Delete this review?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-gray-400">No reviews yet</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-4">{{ $reviews->links() }}</div>
            </div>
        </div>

        {{-- Add new review --}}
        <div class="bg-white rounded-xl border p-5 h-fit">
            <h3 class="font-bold text-gray-800 mb-4 pb-2 border-b">Add Review</h3>
            <form method="POST" action="{{ route('admin.reviews.store') }}" class="space-y-3">
                @csrf

                <div>
                    <label class="form-label">Product *</label>
                    <select name="product_id" class="form-select" required>
                        <option value="">Select product…</option>
                        @foreach(\App\Models\Product::active()->orderBy('name')->get(['id','name']) as $p)
                        <option value="{{ $p->id }}" @selected(old('product_id')==$p->id)>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    @error('product_id') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ mode: '{{ old('reviewer_name') ? 'name' : 'customer' }}' }">
                    <label class="form-label">Reviewer *</label>
                    <div class="flex gap-2 mb-2">
                        <button type="button" @click="mode='customer'"
                            :class="mode==='customer' ? 'btn-primary' : 'btn-outline'" class="btn-sm text-xs flex-1">
                            Existing customer
                        </button>
                        <button type="button" @click="mode='name'"
                            :class="mode==='name' ? 'btn-primary' : 'btn-outline'" class="btn-sm text-xs flex-1">
                            Name only (imported)
                        </button>
                    </div>

                    <div x-show="mode==='customer'">
                        <select name="user_id" class="form-select" :required="mode==='customer'">
                            <option value="">Select customer…</option>
                            @foreach(\App\Models\User::where('role','customer')->orderBy('name')->get(['id','name','phone']) as $u)
                            <option value="{{ $u->id }}" @selected(old('user_id')==$u->id)>
                                {{ $u->name }} {{ $u->phone ? '('.$u->phone.')' : '' }}
                            </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>

                    <div x-show="mode==='name'" x-cloak>
                        <input type="text" name="reviewer_name" value="{{ old('reviewer_name') }}"
                            :required="mode==='name'" class="form-input" placeholder="e.g. Rafiq Ahmed">
                        @error('reviewer_name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="form-label">Rating *</label>
                    <div class="flex gap-1" x-data="{ rating: {{ old('rating', 5) }} }">
                        @for($i=1; $i<=5; $i++)
                        <button type="button" @click="rating={{ $i }}"
                            class="text-2xl leading-none focus:outline-none transition-colors">
                            <i class="fas fa-star" :class="rating >= {{ $i }} ? 'text-yellow-400' : 'text-gray-200'"></i>
                        </button>
                        @endfor
                        <input type="hidden" name="rating" :value="rating">
                    </div>
                    @error('rating') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="form-input" placeholder="e.g. Great product!">
                    @error('title') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="form-label">Review Body *</label>
                    <textarea name="body" rows="3" class="form-input resize-none"
                        placeholder="Write the review content…" required>{{ old('body') }}</textarea>
                    @error('body') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_approved" value="1" checked class="accent-teal-600">
                    <span class="text-sm text-gray-700">Approve immediately</span>
                </label>

                <button type="submit" class="btn-primary w-full">
                    <i class="fas fa-plus mr-1.5"></i>Add Review
                </button>
            </form>
        </div>

    </div>
</div>
@endsection