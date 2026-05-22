@extends('layouts.shop')
@section('title', 'Payment Failed')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4">
    <div class="text-center max-w-md">
        <div class="text-7xl mb-4">😔</div>
        <h1 class="text-2xl font-black text-gray-800 mb-2">Payment Failed</h1>
        <p class="text-gray-500 mb-6">We couldn't process your payment. Don't worry — no money was charged. You can try again or place your order with Cash on Delivery.</p>
        <div class="flex flex-wrap gap-3 justify-center">
            <a href="{{ route('checkout.index') }}" class="btn-primary">Try Again</a>
            <a href="{{ route('home') }}" class="btn-outline">Go Home</a>
        </div>
    </div>
</div>
@endsection
