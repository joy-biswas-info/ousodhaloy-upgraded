@php use App\Models\Order; @endphp
@extends('layouts.shop')
@section('title', 'Order #' . $order->order_number)

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-6">

        {{-- Success message --}}
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-5 text-center">
                <div class="text-4xl mb-2">🎉</div>
                <h2 class="font-black text-green-800 text-xl mb-1">Order Placed Successfully!</h2>
                <p class="text-green-700 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Order header --}}
        <div class="bg-white rounded-2xl border p-5 mb-4">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div>
                    <h1 class="font-black text-xl text-gray-800">#{{ $order->order_number }}</h1>
                    <p class="text-xs text-gray-500 mt-0.5">Placed {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                    <span
                        class="status-badge {{ $order->payment_status === 'paid' ? 'status-delivered' : ($order->payment_status === 'failed' ? 'status-cancelled' : 'status-pending') }}">
                        💳 {{ ucfirst($order->payment_status) }}
                    </span>
                </div>
            </div>

            {{-- Progress bar --}}
            @php
                $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
                $currentStep = array_search($order->status, $steps);
                if ($currentStep === false)
                    $currentStep = -1;
            @endphp
            @if(!in_array($order->status, ['cancelled', 'refunded', 'returned']))
                <div class="mt-5">
                    <div class="flex items-center">
                        @foreach($steps as $i => $step)
                            <div class="flex flex-col items-center {{ $i < count($steps) - 1 ? 'flex-1' : '' }}">
                                <div
                                    class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all
                                    {{ $currentStep >= $i ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                                    @if($currentStep > $i) <i class="fas fa-check text-[10px]"></i>
                                    @else {{ $i + 1 }} @endif
                                </div>
                                <span
                                    class="text-[9px] text-center mt-1 font-medium {{ $currentStep >= $i ? 'text-teal-700' : 'text-gray-400' }}"
                                    style="max-width:50px">
                                    {{ ucfirst($step) }}
                                </span>
                            </div>
                            @if($i < count($steps) - 1)
                                <div class="flex-1 h-0.5 mx-1 rounded {{ $currentStep > $i ? 'bg-teal-600' : 'bg-gray-200' }}"></div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
            {{-- Items --}}
            <div class="bg-white rounded-2xl border p-5 sm:col-span-2">
                <h3 class="font-bold text-gray-800 mb-3">Order Items</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="flex items-center gap-3 pb-3 border-b last:border-0 last:pb-0">
                            <div
                                class="w-12 h-12 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center text-xl">
                                @if($item->product?->thumbnail)
                                    <img src="{{ $item->product->thumbnail_url }}" class="max-h-full max-w-full object-contain">
                                @else 💊 @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-gray-800">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-500">৳{{ number_format($item->price, 2) }} × {{ $item->quantity }}
                                </p>
                            </div>
                            <span class="font-bold text-sm text-teal-700">৳{{ number_format($item->subtotal, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-4 pt-3 border-t space-y-1.5 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span><span>৳{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Delivery</span>
                        <span>{{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}</span>
                    </div>
                    @if($order->discount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount ({{ $order->promo_code }})</span>
                            <span>−৳{{ number_format($order->discount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between font-black text-base border-t pt-2">
                        <span>Total</span>
                        <span class="text-teal-700">৳{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Shipping --}}
            <div class="bg-white rounded-2xl border p-5">
                <h3 class="font-bold text-gray-800 mb-3">Delivery Address</h3>
                <div class="text-sm text-gray-600 space-y-0.5">
                    <p class="font-semibold text-gray-800">{{ $order->shipping_name }}</p>
                    <p><i class="fas fa-phone text-xs text-teal-500 mr-1"></i>{{ $order->shipping_phone }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_upazila }}, {{ $order->shipping_district }}</p>
                    <p>{{ $order->shipping_division }}</p>
                </div>
                @if($order->pathao_tracking_code)
                    <div class="mt-3 bg-teal-50 rounded-lg p-2.5 text-xs">
                        <p class="font-semibold text-teal-700">Tracking: {{ $order->pathao_tracking_code }}</p>
                        <p class="text-teal-600">Pathao: {{ $order->pathao_status ?? 'Processing' }}</p>
                    </div>
                @endif
            </div>

            {{-- Payment --}}
            <div class="bg-white rounded-2xl border p-5">
                <h3 class="font-bold text-gray-800 mb-3">Payment</h3>
                <div class="text-sm text-gray-600 space-y-1.5">
                    <div class="flex justify-between">
                        <span>Method</span>
                        <span class="font-semibold capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status</span>
                        <span
                            class="font-semibold capitalize {{ $order->payment_status === 'paid' ? 'text-green-600' : '' }}">{{ ucfirst($order->payment_status) }}</span>
                    </div>
                    @if($order->ssl_transaction_id)
                        <div class="flex justify-between">
                            <span>Txn ID</span>
                            <span class="font-mono text-xs">{{ $order->ssl_transaction_id }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Status history --}}
        <div class="bg-white rounded-2xl border p-5">
            <h3 class="font-bold text-gray-800 mb-4">Order Timeline</h3>
            <div class="space-y-3">
                @foreach($order->statusHistory as $h)
                    <div class="flex gap-3">
                        <div class="flex flex-col items-center">
                            <div class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0 mt-1"></div>
                            @if(!$loop->last)
                            <div class="w-0.5 flex-1 bg-gray-200 my-1"></div> @endif
                        </div>
                        <div class="pb-3">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status) }}</p>
                            @if($h->note)
                            <p class="text-xs text-gray-500">{{ $h->note }}</p> @endif
                            <p class="text-xs text-gray-400 mt-0.5">{{ $h->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-wrap gap-3 mt-4">
            <a href="{{ route('track') }}" class="btn-secondary btn-sm">
                <i class="fas fa-search-location mr-1"></i>Track Order
            </a>
            @auth
                <a href="{{ route('account.orders') }}" class="btn-outline btn-sm">
                    <i class="fas fa-list mr-1"></i>My Orders
                </a>
            @endauth
            <a href="{{ route('home') }}" class="btn-outline btn-sm">
                <i class="fas fa-home mr-1"></i>Continue Shopping
            </a>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        @if(session('success') && str_contains(session('success', ''), 'Order placed') || str_contains(session('success', ''), 'Payment successful'))
            @php $pixelPurchase = \App\Models\Setting::get('meta_pixel_purchase', 'true') === 'true'; @endphp
            @if($pixelPurchase)
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.fbTrack) {
                        window.fbTrack('Purchase', {
                            content_ids: {!! json_encode($order->items->pluck('product_id')->toArray()) !!},
                            content_type: 'product',
                            value: {{ $order->total }},
                            currency: 'BDT',
                            num_items: {{ $order->items->sum('quantity') }},
                            order_id: '{{ $order->order_number }}'
                        });
                    }
                });
            @endif
        @endif
    </script>
@endpush