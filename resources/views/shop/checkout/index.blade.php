@extends('layouts.checkout')
@php
    $cart   = session('cart', []);
    $sub    = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
    $charge = $sub >= (float)\App\Models\Setting::get('free_delivery_min', 500)
                ? 0
                : (float)\App\Models\Setting::get('delivery_charge', 60);

    // Checkout field settings
    $cf = json_decode(\App\Models\Setting::get('checkout_fields', '{}'), true) ?: [];
    $showEmail       = $cf['shipping_email']['visible']    ?? true;
    $requireEmail    = $cf['shipping_email']['required']   ?? false;
    $showPostcode    = $cf['shipping_postcode']['visible']  ?? true;
    $requirePostcode = $cf['shipping_postcode']['required'] ?? false;
    $showUpazila     = $cf['shipping_upazila']['visible']   ?? true;
    $requireUpazila  = $cf['shipping_upazila']['required']  ?? true;
    $showPrescription= $cf['prescription']['visible']       ?? true;
    $showNotes       = $cf['customer_note']['visible']      ?? true;
    $showPromo       = $cf['promo_code']['visible']         ?? true;

    // Payment method visibility from settings
    $codEnabled   = \App\Models\Setting::get('cod_enabled',   'true') === 'true';
    $sslEnabled   = \App\Models\Setting::get('ssl_enabled',   'true') === 'true';
    $pixelCheckout = \App\Models\Setting::get('meta_pixel_initiate_checkout', 'true') === 'true';
@endphp
@section('title', 'Checkout')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-black text-gray-800 mb-5">🔒 Checkout</h1>

    <form method="POST" action="{{ route('checkout.store') }}" enctype="multipart/form-data" id="checkout-form">
        @csrf
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Left: Shipping + Payment --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Guest notice --}}
                @guest
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm">
                    <p class="font-semibold text-blue-800 mb-1">Checking out as guest</p>
                    <p class="text-blue-700 text-xs">
                        <a href="{{ route('auth.login', ['redirect' => url()->current()]) }}" class="font-bold underline">Login</a> or
                        <a href="{{ route('auth.register') }}" class="font-bold underline">register</a> to track your orders easily.
                    </p>
                </div>
                @endguest

                {{-- Delivery Info --}}
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-map-marker-alt text-teal-600"></i> Delivery Information
                    </h2>

                    @if(auth()->user()?->addresses->count() > 0)
                    <div class="mb-4">
                        <label class="form-label">Saved Addresses</label>
                        <select onchange="fillAddress(this)" class="form-select mb-2">
                            <option value="">Select saved address</option>
                            @foreach(auth()->user()->addresses as $addr)
                            <option value="{{ json_encode($addr) }}">{{ $addr->label }}: {{ $addr->name }}, {{ $addr->district }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Name --}}
                        <div>
                            <label class="form-label">Full Name *</label>
                            <input type="text" name="shipping_name"
                                value="{{ old('shipping_name', auth()->user()?->name) }}"
                                class="form-input @error('shipping_name') border-red-400 @enderror" required>
                            @error('shipping_name') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Phone --}}
                        <div>
                            <label class="form-label">Phone Number *</label>
                            <input type="tel" name="shipping_phone"
                                value="{{ old('shipping_phone', auth()->user()?->phone) }}"
                                class="form-input @error('shipping_phone') border-red-400 @enderror"
                                placeholder="01XXXXXXXXX" required>
                            @error('shipping_phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Email (configurable) --}}
                        @if($showEmail)
                        <div class="sm:col-span-2">
                            <label class="form-label">Email Address{{ $requireEmail ? ' *' : '' }}</label>
                            <input type="email" name="shipping_email"
                                value="{{ old('shipping_email', auth()->user()?->email) }}"
                                class="form-input" placeholder="optional@email.com"
                                {{ $requireEmail ? 'required' : '' }}>
                        </div>
                        @endif

                        {{-- Division --}}
                        <div>
                            <label class="form-label">Division *</label>
                            <select name="shipping_division"
                                class="form-select @error('shipping_division') border-red-400 @enderror"
                                id="division-select"
                                onchange="updateDistricts(this.value); recalcDelivery();"
                                required>
                                <option value="">Select division</option>
                                @foreach(config('bd.divisions') as $div)
                                <option value="{{ $div }}" @selected(old('shipping_division', $address?->division) === $div)>{{ $div }}</option>
                                @endforeach
                            </select>
                            @error('shipping_division') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- District --}}
                        <div>
                            <label class="form-label">District *</label>
                            <select name="shipping_district" class="form-select @error('shipping_district') border-red-400 @enderror"
                                id="district-select"
                                onchange="recalcDelivery()"
                                required>
                                <option value="">Select district</option>
                                @if(old('shipping_division', $address?->division))
                                    @foreach(config('bd.districts.' . old('shipping_division', $address?->division), []) as $dist)
                                    <option value="{{ $dist }}" @selected(old('shipping_district', $address?->district) === $dist)>{{ $dist }}</option>
                                    @endforeach
                                @endif
                            </select>
                            @error('shipping_district') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Upazila (configurable) --}}
                        @if($showUpazila)
                        <div>
                            <label class="form-label">Upazila / Area{{ $requireUpazila ? ' *' : '' }}</label>
                            <input type="text" name="shipping_upazila"
                                value="{{ old('shipping_upazila', $address?->upazila) }}"
                                class="form-input @error('shipping_upazila') border-red-400 @enderror"
                                placeholder="Mirpur, Dhanmondi..."
                                {{ $requireUpazila ? 'required' : '' }}>
                            @error('shipping_upazila') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        @endif

                        {{-- Postcode (configurable) --}}
                        @if($showPostcode)
                        <div>
                            <label class="form-label">Postal Code{{ $requirePostcode ? ' *' : '' }}</label>
                            <input type="text" name="shipping_postcode"
                                value="{{ old('shipping_postcode') }}"
                                class="form-input" placeholder="1216" maxlength="10"
                                {{ $requirePostcode ? 'required' : '' }}>
                        </div>
                        @endif

                        {{-- Full Address --}}
                        <div class="sm:col-span-2">
                            <label class="form-label">Full Address *</label>
                            <textarea name="shipping_address" rows="2"
                                class="form-input resize-none @error('shipping_address') border-red-400 @enderror"
                                placeholder="House, Road, Block..." required>{{ old('shipping_address', $address?->address) }}</textarea>
                            @error('shipping_address') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        {{-- Order Notes (configurable) --}}
                        @if($showNotes)
                        <div class="sm:col-span-2">
                            <label class="form-label">Order Notes (optional)</label>
                            <textarea name="notes" rows="2" class="form-input resize-none"
                                placeholder="Special delivery instructions...">{{ old('notes') }}</textarea>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Prescription upload (configurable) --}}
                @if($showPrescription)
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <i class="fas fa-file-prescription text-blue-600"></i> Prescription Upload
                        @php $hasPrescriptionItems = collect(session('cart', []))->where('requires_rx', true)->count() > 0; @endphp
                        @if($hasPrescriptionItems)
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-semibold">Required</span>
                        @else
                            <span class="text-xs text-gray-400">(optional)</span>
                        @endif
                    </h2>
                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-teal-400 transition-colors cursor-pointer"
                        onclick="document.getElementById('prescription-input').click()">
                        <i class="fas fa-file-medical text-3xl text-gray-400 mb-2"></i>
                        <p class="text-sm text-gray-600 font-semibold">Upload Prescription</p>
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG or PDF up to 5MB</p>
                    </div>
                    <input type="file" id="prescription-input" name="prescription" accept="image/*,.pdf" class="hidden"
                        onchange="document.getElementById('rx-name').textContent = this.files[0]?.name || ''">
                    <p id="rx-name" class="text-xs text-teal-600 font-semibold mt-2 text-center"></p>
                </div>
                @endif

                {{-- Payment method --}}
                <div class="bg-white rounded-xl border p-5">
                    <h2 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card text-teal-600"></i> Payment Method
                    </h2>
                    <div class="space-y-2">

                        @if($codEnabled)
                        <label class="payment-method-label flex items-center gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition-colors hover:border-teal-300 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                            <input type="radio" name="payment_method" value="cod" checked class="accent-teal-600">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-money-bill text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Cash on Delivery</p>
                                <p class="text-xs text-gray-500">Pay when you receive</p>
                            </div>
                        </label>
                        @endif

                        @if($sslEnabled)
                        <label class="payment-method-label flex items-center gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition-colors hover:border-teal-300 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                            <input type="radio" name="payment_method" value="ssl_commerz" class="accent-teal-600">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-credit-card text-blue-600"></i>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-gray-800">Card / bKash / Nagad / Rocket</p>
                                <p class="text-xs text-gray-500">Visa, Mastercard, bKash, Nagad, Rocket, Net Banking</p>
                            </div>
                            <div class="flex gap-1.5 flex-shrink-0 items-center">
                                {{-- bKash --}}
                                <span style="background:#E2136E;color:#fff;font-size:9px;font-weight:800;padding:2px 6px;border-radius:3px;letter-spacing:.3px">bKash</span>
                                {{-- Nagad --}}
                                <span style="background:#F16322;color:#fff;font-size:9px;font-weight:800;padding:2px 6px;border-radius:3px;letter-spacing:.3px">Nagad</span>
                            </div>
                        </label>
                        @endif

                        @if(\App\Models\Setting::get('bank_enabled','true') === 'true')
                        <label class="payment-method-label flex items-center gap-3 p-3.5 border-2 rounded-xl cursor-pointer transition-colors hover:border-teal-300 has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50">
                            <input type="radio" name="payment_method" value="bank" class="accent-teal-600">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-university text-gray-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-sm text-gray-800">Bank Transfer</p>
                                <p class="text-xs text-gray-500">Pay via bank transfer (confirm with admin)</p>
                            </div>
                        </label>
                        @endif

                        @if(!$codEnabled && !$sslEnabled)
                        <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                            <i class="fas fa-exclamation-circle mr-1"></i>
                            No payment methods are currently available. Please contact support.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Order summary --}}
            <div>
                <div class="bg-white rounded-xl border p-5 sticky top-20">
                    <h3 class="font-bold text-gray-800 mb-4">Order Summary</h3>

                    {{-- Cart items --}}
                    @foreach(session('cart', []) as $id => $item)
                    <div class="flex items-center gap-3 mb-3 pb-3 border-b last:border-0 last:mb-0 last:pb-0">
                        <div class="w-12 h-12 bg-gray-50 rounded-lg border flex-shrink-0 flex items-center justify-center text-xl">💊</div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500">Qty: {{ $item['qty'] }}</p>
                        </div>
                        <span class="text-xs font-bold text-teal-700">৳{{ number_format($item['price'] * $item['qty'], 2) }}</span>
                    </div>
                    @endforeach

                    {{-- Promo code (configurable) --}}
                    @if($showPromo)
                    <div class="mt-3 flex gap-2">
                        <input type="text" id="promo-input" placeholder="Promo code"
                            class="flex-1 border border-gray-200 rounded-lg px-3 py-2 text-xs uppercase outline-none focus:border-teal-500">
                        <button type="button" onclick="validatePromo()" class="btn-secondary btn-sm px-3">Apply</button>
                    </div>
                    <p id="promo-msg" class="text-xs mt-1 hidden"></p>
                    <input type="hidden" name="promo_code" id="promo-value">
                    @endif

                    {{-- Totals --}}
                    <div class="space-y-2 text-sm mt-4 pt-4 border-t" id="checkout-summary" data-subtotal="{{ $sub }}">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">৳{{ number_format($sub, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Delivery</span>
                            <span id="delivery-amt" class="font-semibold {{ $charge == 0 ? 'text-green-600' : '' }}">
                                {{ $charge == 0 ? 'FREE 🎉' : '৳' . number_format($charge, 2) }}
                            </span>
                        </div>
                        <p id="zone-info" class="text-xs text-teal-600 font-semibold hidden"></p>
                        <div class="flex justify-between text-base font-black border-t pt-2">
                            <span>Total</span>
                            <span class="text-teal-700" id="total-amt">৳{{ number_format($sub + $charge, 2) }}</span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-3.5 mt-4 text-base">
                        <i class="fas fa-lock mr-2"></i>Place Order
                    </button>
                    <p class="text-center text-xs text-gray-400 mt-2">
                        <i class="fas fa-shield-alt mr-1 text-teal-500"></i>Secure & encrypted checkout
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const BD_DISTRICTS = @json(config('bd.districts')??[]);

// Populate districts when division changes — also triggers delivery recalc
function updateDistricts(division) {
    const select = document.getElementById('district-select');
    select.innerHTML = '<option value="">Select district</option>';
    (BD_DISTRICTS[division] || []).forEach(d => {
        const opt = document.createElement('option');
        opt.value = d;
        opt.textContent = d;
        select.appendChild(opt);
    });
    // District cleared, recalc with empty district (will use division-level default)
    recalcDelivery();
}

// AJAX: recalculate delivery charge when division or district changes
async function recalcDelivery() {
    const division = document.getElementById('division-select')?.value;
    const district = document.getElementById('district-select')?.value;
    if (!division) return;

    const cartSub = parseFloat(document.getElementById('checkout-summary')?.dataset.subtotal || 0);

    try {
        const res = await fetch('/checkout/delivery-charge', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ division, district, subtotal: cartSub })
        });
        const data = await res.json();

        const deliveryEl = document.getElementById('delivery-amt');
        const totalEl    = document.getElementById('total-amt');
        const zoneEl     = document.getElementById('zone-info');

        if (deliveryEl) {
            deliveryEl.textContent = data.delivery_charge == 0
                ? 'FREE 🎉'
                : '৳' + parseFloat(data.delivery_charge).toFixed(2);
            deliveryEl.className = 'font-semibold' + (data.delivery_charge == 0 ? ' text-green-600' : '');
        }
        if (totalEl) {
            totalEl.textContent = '৳' + parseFloat(data.total).toFixed(2);
        }
        if (zoneEl) {
            zoneEl.textContent = data.zone_name + ' · Est. ' + data.estimated_days + ' day(s)';
            zoneEl.classList.remove('hidden');
        }
    } catch(e) {
        // fail silently
    }
}

// Fill saved address into form fields
function fillAddress(select) {
    if (!select.value) return;
    const addr = JSON.parse(select.value);
    document.querySelector('[name=shipping_name]').value     = addr.name     || '';
    document.querySelector('[name=shipping_phone]').value    = addr.phone    || '';
    const upazilaEl = document.querySelector('[name=shipping_upazila]');
    if (upazilaEl) upazilaEl.value = addr.upazila || '';
    document.querySelector('[name=shipping_address]').value  = addr.address  || '';
    const divSel = document.getElementById('division-select');
    divSel.value = addr.division || '';
    updateDistricts(addr.division);
    setTimeout(() => {
        document.getElementById('district-select').value = addr.district || '';
        recalcDelivery();
    }, 50);
}

// Promo code validation
async function validatePromo() {
    const code = document.getElementById('promo-input')?.value;
    if (!code) return;
    const res = await fetch('/cart/validate-promo', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
        },
        body: JSON.stringify({ code })
    });
    const data = await res.json();
    const msg = document.getElementById('promo-msg');
    if (msg) {
        msg.textContent = data.message;
        msg.className = 'text-xs mt-1 ' + (data.valid ? 'text-green-600' : 'text-red-600');
        msg.classList.remove('hidden');
    }
    if (data.valid) {
        const promoVal = document.getElementById('promo-value');
        if (promoVal) promoVal.value = code;
    }
}
</script>

@if($pixelCheckout)
<script>
document.addEventListener('DOMContentLoaded', () => {
    if (window.fbTrack) {
        window.fbTrack('InitiateCheckout', {
            num_items: {{ collect($cart)->sum('qty') }},
            value: {{ $sub }},
            currency: 'BDT'
        });
    }
});
</script>
@endif
@endpush