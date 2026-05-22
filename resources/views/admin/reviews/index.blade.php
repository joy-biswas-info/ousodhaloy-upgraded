@extends('layouts.admin')
@section('page-title', 'Product Reviews')

@section('content')
<div class="space-y-4">

    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach([
            ['label' => 'Total Reviews',    'value' => \App\Models\ProductReview::count(),                          'color' => 'blue'],
            ['label' => 'Pending Approval', 'value' => \App\Models\ProductReview::where('is_approved',false)->count(), 'color' => 'yellow'],
            ['label' => 'Approved',         'value' => \App\Models\ProductReview::where('is_approved',true)->count(),  'color' => 'green'],
        ] as $card)
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-{{ $card['color'] }}-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-star text-{{ $card['color'] }}-500 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">{{ $card['label'] }}</p>
                <p class="text-xl font-black text-gray-800">{{ $card['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr><th>Product</th><th>Customer</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                    <tr>
                        <td>
                            <a href="{{ route('shop.product', $review->product->slug) }}" target="_blank" class="font-semibold text-sm text-gray-800 hover:text-teal-700">
                                {{ Str::limit($review->product->name, 30) }}
                            </a>
                        </td>
                        <td>
                            <p class="font-semibold text-sm">{{ $review->user->name }}</p>
                            <p class="text-xs text-gray-400">{{ $review->user->phone }}</p>
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
                            @if($review->title)<p class="font-semibold text-sm text-gray-800">{{ $review->title }}</p>@endif
                            @if($review->body)<p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $review->body }}</p>@endif
                        </td>
                        <td>
                            <span class="text-xs font-semibold {{ $review->is_approved ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ $review->is_approved ? '✅ Approved' : '⏳ Pending' }}
                            </span>
                        </td>
                        <td class="text-xs text-gray-400">{{ $review->created_at->format('d M Y') }}</td>
                        <td>
                            <div class="flex gap-1.5">
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" class="inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="{{ $review->is_approved ? 'btn-outline' : 'btn-secondary' }} btn-sm text-xs">
                                        {{ $review->is_approved ? 'Hide' : 'Approve' }}
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" class="inline" onsubmit="return confirm('Delete this review?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger btn-sm text-xs">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-12 text-gray-400">No reviews yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $reviews->links() }}</div>
    </div>
</div>
@endsection
