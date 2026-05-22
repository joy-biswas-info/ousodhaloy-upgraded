{{-- resources/views/shop/orders/my_orders.blade.php --}}
@extends('layouts.shop')
@section('title', 'My Orders')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">📦 My Orders</h1>

    @if($orders->isEmpty())
    <div class="bg-white rounded-2xl border p-16 text-center">
        <div class="text-7xl mb-4">📭</div>
        <h3 class="font-bold text-gray-700 text-xl mb-2">No orders yet</h3>
        <p class="text-gray-500 text-sm mb-5">You haven't placed any orders.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary">Browse Products</a>
    </div>
    @else
    <div class="space-y-3">
        @foreach($orders as $order)
        <div class="bg-white rounded-2xl border p-5 hover:shadow-md transition-shadow">
            <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                <div>
                    <a href="{{ route('orders.show', $order->id) }}" class="font-bold text-teal-700 hover:underline font-mono">
                        #{{ $order->order_number }}
                    </a>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    <span class="text-xs font-semibold {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }} bg-{{ $order->payment_status === 'paid' ? 'green' : 'yellow' }}-50 px-2 py-0.5 rounded-full">
                        {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            {{-- Items preview --}}
            <div class="flex items-center gap-2 mb-3 overflow-x-auto scrollbar-hide pb-1">
                @foreach($order->items->take(4) as $item)
                <div class="w-10 h-10 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center text-lg">💊</div>
                @endforeach
                @if($order->items->count() > 4)
                <span class="text-xs text-gray-400 flex-shrink-0">+{{ $order->items->count() - 4 }} more</span>
                @endif
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500">{{ $order->items->count() }} items · {{ ucwords(str_replace('_',' ',$order->payment_method)) }}</p>
                    <p class="font-black text-teal-700">৳{{ number_format($order->total, 2) }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('orders.show', $order->id) }}" class="btn-secondary btn-sm">View Details</a>
                    @if($order->pathao_tracking_code)
                    <a href="{{ route('track') }}?order={{ $order->order_number }}" class="btn-outline btn-sm">Track</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-5">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
