@extends('layouts.shop')
@section('title', 'Return Policy – ' . \App\Models\Setting::get('site_name'))
@section('meta_description', 'Learn about our 7-day return and refund policy at ' .
    \App\Models\Setting::get('site_name') . '.')

@section('content')
    @php
        $site = \App\Models\Setting::get('site_name', 'Ousodhaloy');
        $email = \App\Models\Setting::get('site_email', 'info@ousodhaloy.com');
        $phone = \App\Models\Setting::get('site_phone', '09610016778');
    @endphp
    <div class="max-w-3xl mx-auto px-4 py-8">

        <nav class="text-xs text-gray-400 mb-6 flex items-center gap-1.5">
            <a href="{{ route('home') }}" class="hover:text-teal-600">Home</a>
            <i class="fas fa-chevron-right text-[9px]"></i>
            <span class="text-gray-600">Return Policy</span>
        </nav>

        <div class="bg-white rounded-2xl border shadow-sm p-6 sm:p-8">
            <h1 class="text-2xl font-black text-gray-900 mb-1">Return & Refund Policy</h1>
            <p class="text-xs text-gray-400 mb-6">Last updated: June 2025</p>

            {{-- Quick summary cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-8">
                @foreach ([['fas fa-calendar-check', '7-Day Window', 'Request within 7 days of delivery', 'teal'], ['fas fa-ban', 'Non-Returnable', 'Medicines, opened items, cold-chain products', 'red'], ['fas fa-undo', 'Easy Process', 'Call or WhatsApp us to start a return', 'blue']] as [$icon, $title, $sub, $color])
                    <div class="bg-{{ $color }}-50 border border-{{ $color }}-100 rounded-xl p-4 text-center">
                        <i class="{{ $icon }} text-{{ $color }}-600 text-lg mb-2 block"></i>
                        <p class="font-bold text-gray-800 text-sm">{{ $title }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ $sub }}</p>
                    </div>
                @endforeach
            </div>

            <div class="prose max-w-none">

                <h2>1. Eligibility for Return</h2>
                <p>You may request a return within <strong>7 days</strong> of the delivery date if any of the following
                    apply:</p>
                <ul>
                    <li>You received the wrong product</li>
                    <li>The product was damaged or defective on arrival</li>
                    <li>The product was expired at the time of delivery</li>
                    <li>The sealed packaging was broken before delivery</li>
                </ul>

                <h2>2. Non-Returnable Items</h2>
                <p>The following items cannot be returned for health and safety reasons:</p>
                <ul>
                    <li>Medicines and pharmaceutical products (unless wrong, expired, or damaged)</li>
                    <li>Opened or partially used products</li>
                    <li>Cold-chain or temperature-sensitive items</li>
                    <li>Personal hygiene products once opened</li>
                    <li>Products without original packaging</li>
                </ul>

                <h2>3. How to Request a Return</h2>
                <p>To initiate a return, contact us within 7 days of delivery:</p>
                <ul>
                    <li>📞 Call or WhatsApp: <a href="tel:{{ $phone }}">{{ $phone }}</a></li>
                    <li>📧 Email: <a href="mailto:{{ $email }}">{{ $email }}</a></li>
                </ul>
                <p>Please have your order number, a description of the issue, and clear photos of the product and packaging
                    ready when you contact us.</p>

                <h2>4. Refund Process</h2>
                <p>Once your return is approved, we will arrange collection of the item at no cost to you. Refunds are
                    processed within <strong>3–5 business days</strong> after we receive and inspect the returned product.
                </p>
                <ul>
                    <li><strong>bKash / Nagad / Card:</strong> Refunded to the original payment method</li>
                    <li><strong>Cash on Delivery:</strong> Refunded via bKash or bank transfer</li>
                </ul>

                <h2>5. Cancellations</h2>
                <p>You may cancel your order before it is dispatched. Once an order is handed to the courier, cancellation
                    is no longer possible — you would need to follow the return process after delivery. To cancel, contact
                    us as soon as possible at {{ $phone }}.</p>

                <h2>6. Damaged in Transit</h2>
                <p>If your order arrives visibly damaged, please refuse delivery and contact us immediately. If you notice
                    damage after opening, contact us within 24 hours with photos and we will arrange a replacement or
                    refund.</p>

            </div>

            <div class="mt-8 pt-6 border-t flex flex-wrap gap-3">
                <a href="{{ route('legal.privacy') }}" class="btn-secondary btn-sm">Privacy Policy →</a>
                <a href="{{ route('legal.terms') }}" class="btn-secondary btn-sm">Terms of Use →</a>
            </div>
        </div>
    </div>
@endsection
