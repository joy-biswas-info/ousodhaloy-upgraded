@extends('layouts.shop')
@section('title', 'My Addresses')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">🗺️ My Addresses</h1>

    @php $addresses = auth()->user()->addresses; @endphp

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
        @forelse($addresses as $addr)
        <div class="bg-white rounded-2xl border p-5 {{ $addr->is_default ? 'border-teal-400 ring-2 ring-teal-100' : '' }}">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-2">
                    <span class="text-sm font-bold text-gray-800">{{ $addr->label }}</span>
                    @if($addr->is_default)
                    <span class="text-xs bg-teal-100 text-teal-700 font-semibold px-2 py-0.5 rounded-full">Default</span>
                    @endif
                </div>
            </div>
            <div class="text-sm text-gray-600 space-y-0.5">
                <p class="font-semibold text-gray-800">{{ $addr->name }}</p>
                <p><i class="fas fa-phone text-xs text-teal-500 mr-1"></i>{{ $addr->phone }}</p>
                <p>{{ $addr->address }}</p>
                <p>{{ $addr->upazila }}, {{ $addr->district }}</p>
                <p>{{ $addr->division }}</p>
            </div>
        </div>
        @empty
        <div class="sm:col-span-2 bg-white rounded-2xl border p-10 text-center text-gray-400">
            <div class="text-5xl mb-3">📍</div>
            <p class="font-semibold">No saved addresses</p>
            <p class="text-sm mt-1">Save an address when you checkout</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
