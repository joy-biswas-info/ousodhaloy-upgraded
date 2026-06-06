@extends('layouts.shop')
@section('title', 'Terms of Use – ' . \App\Models\Setting::get('site_name'))
@section('meta_description', 'Read the terms and conditions for using ' . \App\Models\Setting::get('site_name') . ' —
    Bangladesh\'s trusted online pharmacy.')

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
            <span class="text-gray-600">Terms of Use</span>
        </nav>

        <div class="bg-white rounded-2xl border shadow-sm p-6 sm:p-8">
            <h1 class="text-2xl font-black text-gray-900 mb-1">Terms of Use</h1>
            <p class="text-xs text-gray-400 mb-6">Last updated: June 2025</p>

            <div class="prose max-w-none">

                <p>By accessing or using <strong>ousodhaloy.com</strong>, you agree to these Terms of Use. If you do not
                    agree, please do not use the site.</p>

                <h2>1. Eligibility</h2>
                <p>You must be at least 18 years old to register and place orders. By using this site you confirm you meet
                    this requirement. Orders for prescription medicines require a valid prescription from a registered
                    physician.</p>

                <h2>2. Account Responsibility</h2>
                <p>You are responsible for keeping your login credentials secure. Do not share your account with others.
                    {{ $site }} is not liable for losses resulting from unauthorised use of your account. Notify us
                    immediately if you suspect unauthorised access.</p>

                <h2>3. Product Information</h2>
                <p>We strive to display accurate product information, including names, descriptions, images, and prices.
                    However, errors may occur. We reserve the right to correct errors and cancel orders affected by
                    incorrect pricing or stock information — you will be notified and refunded promptly.</p>

                <h2>4. Orders and Payment</h2>
                <p>Placing an order constitutes an offer to purchase. An order is confirmed once you receive a confirmation
                    SMS or email. We accept cash on delivery (COD), bKash, Nagad, and card payments. Payment must be
                    completed before dispatch for online payment methods.</p>

                <h2>5. Prescription Medicines</h2>
                <p>Certain medicines require a valid prescription. By ordering such products, you confirm you hold a valid
                    prescription and you consent to provide it at checkout or on delivery. We reserve the right to cancel
                    orders where a valid prescription cannot be verified.</p>

                <h2>6. Delivery</h2>
                <p>We deliver across Bangladesh via partnered courier services. Delivery times are estimates only and are
                    not guaranteed. {{ $site }} is not responsible for delays caused by couriers, natural events, or
                    circumstances beyond our control.</p>

                <h2>7. Returns and Refunds</h2>
                <p>Please review our separate <a href="{{ route('legal.returns') }}">Return Policy</a> for full details on
                    eligibility, timelines, and the return process.</p>

                <h2>8. Prohibited Use</h2>
                <p>You may not use this site to: submit false orders or fraudulent payment information; scrape or copy
                    content without permission; attempt to gain unauthorised access to any part of the site; or use the site
                    in any way that violates applicable Bangladesh law.</p>

                <h2>9. Intellectual Property</h2>
                <p>All content on this website — including logos, product images, text, and design — is the property of
                    {{ $site }} or its licensors. You may not reproduce or redistribute content without written
                    permission.</p>

                <h2>10. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, {{ $site }} is not liable for any indirect, incidental,
                    or consequential damages arising from your use of this site or from any products purchased. Our total
                    liability shall not exceed the amount you paid for the relevant order.</p>

                <h2>11. Governing Law</h2>
                <p>These terms are governed by the laws of Bangladesh. Any dispute shall be subject to the jurisdiction of
                    the courts of Dhaka, Bangladesh.</p>

                <h2>12. Contact</h2>
                <p>For questions about these terms, contact us at <a
                        href="mailto:{{ $email }}">{{ $email }}</a> or call <a
                        href="tel:{{ $phone }}">{{ $phone }}</a>.</p>

            </div>

            <div class="mt-8 pt-6 border-t flex flex-wrap gap-3">
                <a href="{{ route('legal.privacy') }}" class="btn-secondary btn-sm">Privacy Policy →</a>
                <a href="{{ route('legal.returns') }}" class="btn-secondary btn-sm">Return Policy →</a>
            </div>
        </div>
    </div>
@endsection
