@extends('layouts.shop')
@section('title', 'Track Your Order')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-10">
    <div class="text-center mb-8">
        <div class="text-5xl mb-3">📦</div>
        <h1 class="text-2xl font-black text-gray-800">Track Your Order</h1>
        <p class="text-gray-500 text-sm mt-1">Enter your order number and phone to see the latest status</p>
    </div>

    <div class="bg-white rounded-2xl border p-6 mb-6">
        <form method="POST" action="{{ route('track.search') }}" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Order Number</label>
                <input type="text" name="order_number" value="{{ old('order_number', $order?->order_number) }}"
                    class="form-input text-center font-mono tracking-wider" placeholder="OUS-240101-XXXX" required>
            </div>
            <div>
                <label class="form-label">Phone Number</label>
                <input type="tel" name="phone" value="{{ old('phone') }}"
                    class="form-input" placeholder="01XXXXXXXXX" required>
            </div>
            <button type="submit" class="btn-primary w-full py-3">
                <i class="fas fa-search mr-2"></i>Track Order
            </button>
        </form>
    </div>

    @if($error)
    <div class="bg-red-50 border border-red-200 rounded-2xl p-5 text-center text-red-700">
        <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
        <p class="font-semibold">{{ $error }}</p>
    </div>
    @endif

    @if($order)
    <div class="bg-white rounded-2xl border p-5 space-y-5">
        {{-- Header --}}
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="font-black text-xl text-gray-800">#{{ $order->order_number }}</h2>
                <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y, h:i A') }}</p>
            </div>
            <span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
        </div>

        {{-- Progress --}}
        @php
            $steps = [
                ['key' => 'pending',      'label' => 'Order Placed', 'icon' => 'check'],
                ['key' => 'confirmed',    'label' => 'Confirmed',    'icon' => 'clipboard-check'],
                ['key' => 'processing',   'label' => 'Processing',   'icon' => 'cog'],
                ['key' => 'shipped',      'label' => 'Shipped',      'icon' => 'truck'],
                ['key' => 'delivered',    'label' => 'Delivered',    'icon' => 'home'],
            ];
            $statusOrder = ['pending','confirmed','processing','ready_to_ship','shipped','out_for_delivery','delivered'];
            $currentIdx = array_search($order->status, $statusOrder);
        @endphp

        @if(!in_array($order->status, ['cancelled','refunded','returned']))
        <div class="relative">
            <div class="absolute top-4 left-4 right-4 h-0.5 bg-gray-200 z-0"></div>
            <div class="flex justify-between relative z-10">
                @foreach($steps as $i => $step)
                @php $done = $currentIdx !== false && $currentIdx >= array_search($step['key'], $statusOrder); @endphp
                <div class="flex flex-col items-center gap-1.5 flex-1">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold border-2 transition-all {{ $done ? 'bg-teal-600 border-teal-600 text-white' : 'bg-white border-gray-200 text-gray-400' }}">
                        <i class="fas fa-{{ $step['icon'] }} text-xs"></i>
                    </div>
                    <span class="text-[9px] text-center font-medium {{ $done ? 'text-teal-700' : 'text-gray-400' }}" style="max-width:52px">{{ $step['label'] }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 text-center text-red-700">
            <i class="fas fa-times-circle text-2xl mb-1"></i>
            <p class="font-semibold">Order {{ ucfirst($order->status) }}</p>
        </div>
        @endif

        {{-- Pathao tracking --}}
        @if($order->pathao_tracking_code)
        <div class="bg-teal-50 rounded-xl p-3.5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-teal-700">Courier Tracking</p>
                    <p class="text-sm font-mono text-teal-800 font-bold">{{ $order->pathao_tracking_code }}</p>
                    <p class="text-xs text-teal-600">Pathao: {{ $order->pathao_status ?? 'Processing' }}</p>
                </div>
                <div class="text-3xl">🚚</div>
            </div>
        </div>
        @endif

        {{-- Items summary --}}
        <div>
            <h3 class="font-bold text-gray-800 text-sm mb-2">Items ({{ $order->items->count() }})</h3>
            @foreach($order->items as $item)
            <div class="flex justify-between text-sm py-1.5 border-b last:border-0">
                <span class="text-gray-700">{{ $item->product_name }} × {{ $item->quantity }}</span>
                <span class="font-semibold text-gray-800">৳{{ number_format($item->subtotal, 2) }}</span>
            </div>
            @endforeach
            <div class="flex justify-between font-black text-base pt-2">
                <span>Total Paid</span>
                <span class="text-teal-700">৳{{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- Timeline --}}
        <div>
            <h3 class="font-bold text-gray-800 text-sm mb-3">Status History</h3>
            <div class="space-y-3">
                @foreach($order->statusHistory as $h)
                <div class="flex gap-3 items-start">
                    <div class="flex flex-col items-center pt-1">
                        <div class="w-2.5 h-2.5 rounded-full bg-teal-500 flex-shrink-0"></div>
                        @if(!$loop->last)<div class="w-0.5 h-full min-h-[20px] bg-gray-200 mt-1"></div>@endif
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-800">{{ \App\Models\Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status) }}</p>
                        @if($h->note)<p class="text-xs text-gray-500">{{ $h->note }}</p>@endif
                        <p class="text-xs text-gray-400">{{ $h->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="{{ route('home') }}" class="btn-secondary btn-sm">🛒 Continue Shopping</a>
            @auth <a href="{{ route('account.orders') }}" class="btn-outline btn-sm">My Orders</a> @endauth
        </div>
    </div>
    @endif
</div>
@endsection
