{{-- resources/views/shop/account/wishlist.blade.php --}}
@extends('layouts.shop')
@section('title', 'My Wishlist')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">❤️ My Wishlist</h1>

    @php
        $wishlists = auth()->user()->wishlists()->with('product.brand')->get();
    @endphp

    @if($wishlists->isEmpty())
    <div class="bg-white rounded-2xl border p-16 text-center">
        <div class="text-7xl mb-4">💔</div>
        <h3 class="font-bold text-gray-700 text-xl mb-2">Your wishlist is empty</h3>
        <p class="text-gray-500 text-sm mb-5">Save products you love to come back to them later.</p>
        <a href="{{ route('shop.index') }}" class="btn-primary">Browse Products</a>
    </div>
    @else
    <div class="products-grid">
        @foreach($wishlists as $wish)
        @if($wish->product && $wish->product->is_active)
            @include('shop.partials.product-card-grid', ['product' => $wish->product])
        @endif
        @endforeach
    </div>
    @endif
</div>
@endsection
