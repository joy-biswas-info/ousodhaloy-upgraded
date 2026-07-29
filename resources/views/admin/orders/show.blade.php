@extends('layouts.admin')
@section('page-title', 'Order #' . $order->order_number)

@section('content')
    <div class="space-y-5">
        {{-- Header bar --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.orders.index') }}" class="btn-outline btn-sm"><i
                        class="fas fa-arrow-left mr-1"></i>Back</a>
                <span class="status-badge status-{{ $order->status }} text-sm">{{ $order->status_label }}</span>
                <span class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, h:i A') }}</span>
            </div>
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.orders.invoice', $order) }}" class="btn-outline btn-sm" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i>Invoice
                </a>
                @if($order->pathao_consignment_id || $order->steadfast_consignment_id)
                    <a href="{{ route('admin.orders.shipping-label', $order) }}" class="btn-outline btn-sm" target="_blank">
                        <i class="fas fa-tag mr-1"></i>Print Label
                    </a>
                @endif

                {{-- Pathao --}}
                @if(!$order->pathao_consignment_id && !$order->steadfast_consignment_id)
                    <button type="button" onclick="openPathaoModal()" class="btn-secondary btn-sm">🚚 Pathao</button>
                @elseif($order->pathao_consignment_id)
                    <form action="{{ route('admin.orders.sync-pathao', $order) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="btn-outline btn-sm">↻ Sync Pathao</button>
                    </form>
                @endif

                {{-- Steadfast --}}
                @if($steadfastEnabled)
                    @if(!$order->steadfast_consignment_id && !$order->pathao_consignment_id)
                        <form action="{{ route('admin.orders.steadfast', $order) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-secondary btn-sm"
                                onclick="return confirm('Push to Steadfast courier?')">📦 Steadfast</button>
                        </form>
                    @elseif($order->steadfast_consignment_id)
                        <form action="{{ route('admin.orders.sync-steadfast', $order) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn-outline btn-sm">↻ Sync Steadfast</button>
                        </form>
                    @endif
                @endif

                {{-- Undo a mistaken cancel/return. Admin-only — see routes/web.php
                     and OrderService::reopenOrder() for why this is a separate,
                     narrow action rather than just another status option. --}}
                @if(in_array($order->status, ['cancelled', 'returned']) && auth()->user()->isAdmin())
                    <form action="{{ route('admin.orders.reopen', $order) }}" method="POST" class="inline"
                        onsubmit="var note = prompt('This re-deducts stock for every item and moves the order back to Processing. Reason (optional):'); if (note === null) return false; document.getElementById('reopen-note').value = note; return true;">
                        @csrf
                        <input type="hidden" name="note" id="reopen-note">
                        <button type="submit" class="btn-secondary btn-sm">
                            <i class="fas fa-unlock mr-1"></i>Reopen Order
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.orders.destroy', $order) }}" method="POST" class="inline"
                    onsubmit="return confirm('Move order {{ $order->order_number }} to trash?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-danger btn-sm"><i class="fas fa-trash-alt mr-1"></i>Trash</button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- Left --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Items --}}
                <div class="bg-white rounded-xl border overflow-hidden">
                    <div class="px-5 py-4 border-b font-bold text-gray-800">Order Items</div>
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <p class="font-semibold text-sm">{{ $item->product_name }}</p>
                                        @if($item->product_sku)
                                        <p class="text-xs text-gray-400">SKU: {{ $item->product_sku }}</p>@endif
                                    </td>
                                    <td class="text-sm">৳{{ number_format($item->price, 2) }}</td>
                                    <td class="text-sm font-bold">{{ $item->quantity }}</td>
                                    <td class="font-bold text-teal-700">৳{{ number_format($item->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="text-right text-sm text-gray-600 px-4 py-2">Subtotal</td>
                                <td class="font-bold px-4 py-2">৳{{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-right text-sm text-gray-600 px-4 py-2">Delivery</td>
                                <td class="font-bold px-4 py-2">
                                    {{ $order->delivery_charge > 0 ? '৳' . number_format($order->delivery_charge, 2) : 'FREE' }}
                                </td>
                            </tr>
                            @if($order->discount > 0)
                                <tr>
                                    <td colspan="3" class="text-right text-sm text-green-600 px-4 py-2">Discount
                                        ({{ $order->promo_code }})</td>
                                    <td class="font-bold text-green-600 px-4 py-2">−৳{{ number_format($order->discount, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr class="text-base">
                                <td colspan="3" class="text-right font-black px-4 py-2.5">Total</td>
                                <td class="font-black text-teal-700 px-4 py-2.5">৳{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Update status --}}
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Update Status</h3>
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="space-y-3"
                        id="status-form"
                        data-courier="{{ $order->pathao_consignment_id ? 'Pathao (consignment ' . $order->pathao_consignment_id . ')' : ($order->steadfast_consignment_id ? 'Steadfast (consignment ' . $order->steadfast_consignment_id . ')' : '') }}">
                        @csrf
                        <input type="hidden" name="confirm_courier_cancel" id="confirm-courier-cancel" value="0">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="form-label">New Status</label>
                                <select name="status" class="form-select" id="status-select">
                                    <option value="{{ $order->status }}" selected>{{ $order->status_label }} (current)</option>
                                    @foreach(\App\Models\Order::STATUS_FLOW[$order->status] ?? [] as $key)
                                        <option value="{{ $key }}">{{ \App\Models\Order::STATUS_LABELS[$key] }}{{ $order->status === 'on_hold' && $order->held_from_status === $key ? ' — resume here' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="form-label">Note (optional)</label>
                                <input type="text" name="note" class="form-input" placeholder="Internal or customer note">
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <input type="checkbox" name="notify_customer" value="1" checked id="notify"
                                class="accent-teal-600">
                            <label for="notify" class="text-sm text-gray-600 cursor-pointer">Send SMS to customer</label>
                        </div>
                        <button type="submit" class="btn-primary btn-sm" onclick="return confirmCourierCancel()">Update Status</button>
                    </form>
                    <div class="border-t mt-4 pt-4">
                        <form method="POST" action="{{ route('admin.orders.payment', $order) }}"
                            class="flex items-center gap-3">
                            @csrf
                            <label class="form-label mb-0">Payment Status</label>
                            <select name="payment_status" class="form-select w-auto">
                                @foreach(['unpaid', 'pending', 'paid', 'failed', 'refunded'] as $s)
                                    <option value="{{ $s }}" @selected($order->payment_status === $s)>{{ ucfirst($s) }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="btn-secondary btn-sm">Update</button>
                        </form>
                    </div>
                </div>

                {{-- Admin note --}}
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-3">Admin Note</h3>
                    <form method="POST" action="{{ route('admin.orders.note', $order) }}" class="flex gap-3">
                        @csrf
                        <textarea name="admin_note" rows="2" class="form-input flex-1 resize-none"
                            placeholder="Private note (not visible to customer)">{{ $order->admin_note }}</textarea>
                        <button type="submit" class="btn-secondary btn-sm self-start">Save</button>
                    </form>
                </div>

                {{-- Timeline --}}
                <div class="bg-white rounded-xl border p-5">
                    <h3 class="font-bold text-gray-800 mb-4">Status Timeline</h3>
                    <div class="space-y-3">
                        @foreach($order->statusHistory as $h)
                            <div class="flex gap-3">
                                <div class="flex flex-col items-center">
                                    <div class="w-2.5 h-2.5 rounded-full bg-teal-500 mt-1 flex-shrink-0"></div>
                                    @if(!$loop->last)
                                    <div class="w-0.5 flex-1 bg-gray-200 mt-1"></div>@endif
                                </div>
                                <div class="pb-3">
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ \App\Models\Order::STATUS_LABELS[$h->status] ?? ucfirst($h->status) }}</p>
                                    @if($h->note)
                                    <p class="text-xs text-gray-500">{{ $h->note }}</p>@endif
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $h->created_at->format('d M Y h:i A') }} ·
                                        {{ $h->changed_by }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Right sidebar --}}
            <div class="space-y-4">

                {{-- Customer --}}
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Customer</h3>
                    <p class="font-semibold text-sm text-gray-800">{{ $order->customer_name }}</p>
                    <a href="tel:{{ $order->customer_phone }}"
                        class="text-xs text-gray-500 hover:text-teal-600 hover:underline flex items-center gap-1 mt-1 w-fit"><i
                            class="fas fa-phone text-teal-500 w-3"></i>{{ $order->customer_phone }}</a>
                    @if($order->user)
                        <p class="text-xs text-gray-500 flex items-center gap-1 mt-1"><i
                                class="fas fa-envelope text-teal-500 w-3"></i>{{ $order->user->email }}</p>
                        <p class="text-xs text-gray-400 mt-1">{{ $order->user->orders()->count() }} total orders</p>
                    @else
                        <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded mt-1 inline-block">Guest</span>
                    @endif

                    {{-- Pathao delivery history — fetched as soon as the page loads, not
                         just when pushing, so it's visible while deciding anything about
                         this order. See PathaoService::getUserSuccessRate() for the caveat
                         about this endpoint not being from official Pathao docs. --}}
                    <div id="pathao-success-rate" class="mt-2 pt-2 border-t rounded-lg text-xs text-gray-400">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Checking Pathao delivery history…
                    </div>

                    @if($order->landingPage)
                        <div class="mt-2 pt-2 border-t">
                            <p class="text-[10px] text-gray-400 uppercase font-semibold mb-1">Came from</p>
                            <a href="{{ route('admin.landing-pages.edit', $order->landingPage) }}"
                                class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded font-semibold hover:underline inline-flex items-center gap-1">
                                <i class="fas fa-bolt text-[10px]"></i> {{ $order->landingPage->headline }}
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Shipping --}}
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Delivery Address</h3>
                    <div class="text-xs text-gray-600 space-y-0.5">
                        <p class="font-semibold text-gray-800">{{ $order->shipping_name }}</p>
                        <p>{{ $order->shipping_phone }}</p>
                        <p>{{ $order->shipping_address }}</p>
                        <p>{{ $order->shipping_upazila }}, {{ $order->shipping_district }}</p>
                        <p>{{ $order->shipping_division }} {{ $order->shipping_postcode }}</p>
                    </div>
                    @if($order->customer_note)
                        <div class="mt-2 bg-yellow-50 rounded p-2 text-xs text-yellow-800">📝 {{ $order->customer_note }}</div>
                    @endif
                </div>

                {{-- Payment --}}
                <div class="bg-white rounded-xl border p-4">
                    <h3 class="font-bold text-gray-800 text-sm mb-3">Payment</h3>
                    <div class="text-xs space-y-1.5 text-gray-600">
                        <div class="flex justify-between"><span>Method</span><span
                                class="font-semibold capitalize">{{ str_replace('_', ' ', $order->payment_method) }}</span>
                        </div>
                        <div class="flex justify-between"><span>Status</span><span
                                class="font-semibold capitalize {{ $order->payment_status === 'paid' ? 'text-green-600' : '' }}">{{ ucfirst($order->payment_status) }}</span>
                        </div>
                        @if($order->ssl_transaction_id)
                            <div class="flex justify-between"><span>Txn</span><span
                        class="font-mono text-xs">{{ $order->ssl_transaction_id }}</span></div>@endif
                    </div>
                </div>

                {{-- Pathao tracking --}}
                @if($order->pathao_consignment_id)
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-teal-800 text-sm">🚚 Pathao</h3>
                            <a href="{{ route('admin.orders.shipping-label', $order) }}" target="_blank"
                                class="text-xs bg-teal-600 text-white px-2 py-1 rounded-lg">
                                <i class="fas fa-print mr-1"></i>Label
                            </a>
                        </div>
                        <div class="text-xs text-teal-700 space-y-1">
                            <p>Consignment: <span class="font-mono font-bold">{{ $order->pathao_consignment_id }}</span></p>
                            <p>Tracking: <span class="font-mono font-bold">{{ $order->pathao_tracking_code }}</span></p>
                            <p>Status: <span class="font-bold">{{ $order->pathao_status ?? 'Pending' }}</span></p>
                        </div>
                    </div>
                @endif

                {{-- Steadfast tracking --}}
                @if($order->steadfast_consignment_id)
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-4">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-indigo-800 text-sm">📦 Steadfast</h3>
                            <a href="{{ route('admin.orders.shipping-label', $order) }}" target="_blank"
                                class="text-xs bg-indigo-600 text-white px-2 py-1 rounded-lg">
                                <i class="fas fa-print mr-1"></i>Label
                            </a>
                        </div>
                        <div class="text-xs text-indigo-700 space-y-1">
                            <p>Consignment: <span class="font-mono font-bold">{{ $order->steadfast_consignment_id }}</span></p>
                            <p>Tracking: <span class="font-mono font-bold">{{ $order->steadfast_tracking_code }}</span></p>
                            <p>Status: <span class="font-bold">{{ $order->steadfast_status ?? 'Pending' }}</span></p>
                        </div>
                        {{-- Real courier-side action, separate from the local "Cancel" status
                             — for a package that's already left the warehouse. --}}
                        <form action="{{ route('admin.orders.steadfast-return', $order) }}" method="POST" class="mt-2"
                            onsubmit="document.getElementById('sf-return-reason').value = prompt('Reason for return (optional):') || ''; return true;">
                            @csrf
                            <input type="hidden" name="reason" id="sf-return-reason">
                            <button type="submit" class="text-xs bg-indigo-600 text-white px-2 py-1 rounded-lg w-full">
                                <i class="fas fa-undo mr-1"></i>Request Return
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Prescription --}}
                @if($order->prescription_image)
                    <div class="bg-white rounded-xl border p-4">
                        <h3 class="font-bold text-gray-800 text-sm mb-3">Prescription</h3>
                        <a href="{{ asset('storage/' . $order->prescription_image) }}" target="_blank">
                            <img src="{{ asset('storage/' . $order->prescription_image) }}" class="w-full rounded-lg border"
                                alt="Prescription">
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

{{-- Pathao modal --}}
@if(!$order->pathao_consignment_id && !$order->steadfast_consignment_id)
    <div id="pathao-modal" class="fixed inset-0 bg-black/60 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-800 text-lg">🚚 Push to Pathao</h3>
                <button onclick="closePathaoModal()"
                    class="text-gray-400 hover:text-gray-600 text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('admin.orders.pathao', $order) }}" method="POST" class="space-y-4">
                @csrf
                <div class="bg-gray-50 rounded-xl p-3 text-xs text-gray-600 space-y-1">
                    <p><b>Order:</b> {{ $order->order_number }}</p>
                    <p><b>Recipient:</b> {{ $order->shipping_name }} · {{ $order->shipping_phone }}</p>
                    <p><b>Address:</b> {{ $order->shipping_district }}, {{ $order->shipping_division }}</p>
                    <p><b>COD:</b>
                        {{ $order->payment_status !== 'paid' ? '৳' . number_format($order->total, 2) : 'Prepaid — no collection' }}
                    </p>
                </div>

                <div>
                    <label class="form-label">Recipient City *</label>
                    <select name="pathao_city_id" id="pathao-city" class="form-select"
                        onchange="loadPathaoZones(this.value)" required>
                        <option value="">Loading cities…</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Recipient Zone *</label>
                    <select name="pathao_zone_id" id="pathao-zone" class="form-select"
                        onchange="loadPathaoAreas(this.value)" required disabled>
                        <option value="">Select city first</option>
                    </select>
                </div>
                <div>
                    <label class="form-label">Recipient Area <span
                            class="text-gray-400 font-normal normal-case">(optional)</span></label>
                    <select name="pathao_area_id" id="pathao-area" class="form-select" disabled>
                        <option value="">Select zone first</option>
                    </select>
                </div>
                <p class="text-xs text-blue-700 bg-blue-50 border border-blue-200 rounded-lg p-2">
                    💡 City and zone are pre-filled by matching this order's own address — check the "auto-matched" hints
                    below the fields before pushing. Falls back to your <a href="{{ route('admin.settings.index') }}"
                        class="underline font-semibold">Settings → Pathao</a> defaults if nothing matches.
                </p>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closePathaoModal()" class="btn-outline flex-1">Cancel</button>
                    <button type="submit" class="btn-primary flex-1"><i class="fas fa-truck mr-1"></i>Push Order</button>
                </div>
            </form>
        </div>
    </div>
@endif

@push('scripts')
    <script>
        // ← PHP data safely serialised to JS
        const pathaoDefaults = @json($pathaoDefaults ?? []);
        const orderShippingDistrict = @json($order->shipping_district ?? '');
        const orderShippingUpazila = @json($order->shipping_upazila ?? '');

        // Neither courier API supports cancelling a consignment — cancelling
        // here only changes our own status, so confirm the admin understands
        // they still need to cancel manually in the courier's dashboard.
        function confirmCourierCancel() {
            const form = document.getElementById('status-form');
            const select = document.getElementById('status-select');
            const courier = form.dataset.courier;
            if (select.value === 'cancelled' && courier) {
                if (!confirm(`This order was already pushed to ${courier}. Cancelling here only updates the order status — you must also cancel it manually in the courier's merchant dashboard. Continue?`)) {
                    return false;
                }
                document.getElementById('confirm-courier-cancel').value = '1';
            }
            return true;
        }

        function openPathaoModal() {
            const m = document.getElementById('pathao-modal');
            if (!m) return;
            m.classList.remove('hidden');
            m.classList.add('flex');
            loadPathaoCities();
        }

        // Confirmed real response shape (from a live test):
        // { customer_rating: "excellent_customer", customer: { total_delivery: 27, successful_delivery: 27 }, ... }
        function formatRatingLabel(rating) {
            if (!rating) return null;
            return rating.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        async function loadPathaoSuccessRate() {
            const box = document.getElementById('pathao-success-rate');
            if (!box) return;
            const base = 'mt-2 pt-2 border-t text-xs';
            box.className = `${base} text-gray-400`;
            box.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Checking Pathao delivery history…';

            try {
                const res = await fetch(`{{ route('admin.orders.pathao-success-rate', $order) }}`);
                const json = await res.json();

                if (!json.success) {
                    box.className = `${base} text-gray-400`;
                    box.innerHTML = `<i class="fas fa-circle-question mr-1"></i> Pathao history unavailable — ${json.error || 'try again later'}.`;
                    return;
                }

                const d = json.data || {};
                const customer = d.customer || {};
                const total = customer.total_delivery ?? null;
                const successful = customer.successful_delivery ?? null;
                const ratingLabel = formatRatingLabel(d.customer_rating);

                if (total === null || successful === null) {
                    box.className = `${base} text-gray-400`;
                    box.innerHTML = '<i class="fas fa-info-circle mr-1"></i> Got a response but couldn\'t read it — raw: '
                        + '<code class="text-[10px] break-all">' + JSON.stringify(d).slice(0, 200) + '</code>';
                    return;
                }

                if (total === 0) {
                    box.className = `${base} text-gray-400`;
                    box.innerHTML = '<i class="fas fa-circle-info mr-1"></i> New to Pathao — no delivery history yet.';
                    return;
                }

                const pct = Math.round((successful / total) * 1000) / 10; // one decimal place
                const level = pct >= 80 ? 'good' : pct >= 50 ? 'warn' : 'bad';
                const styles = { good: 'text-green-700', warn: 'text-yellow-700', bad: 'text-red-700' };
                const icons = { good: 'fa-circle-check', warn: 'fa-triangle-exclamation', bad: 'fa-circle-exclamation' };

                box.className = `${base} ${styles[level]}`;
                box.innerHTML = `<i class="fas ${icons[level]} mr-1"></i> <strong>${pct}% success rate</strong> on Pathao`
                    + ` (${successful}/${total} delivered)`
                    + (ratingLabel ? ` · Pathao rates them "${ratingLabel}"` : '')
                    + (level === 'bad' ? '. Consider calling to confirm before dispatch.' : '.');
            } catch (e) {
                box.className = `${base} text-gray-400`;
                box.innerHTML = '<i class="fas fa-circle-question mr-1"></i> Pathao history unavailable right now.';
            }
        }

        document.addEventListener('DOMContentLoaded', loadPathaoSuccessRate);

        function closePathaoModal() {
            const m = document.getElementById('pathao-modal');
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        async function pathaoLookup(type, params = {}) {
            const qs = new URLSearchParams({ type, ...params });
            const res = await fetch(`/admin/orders/pathao-lookup?${qs}`);
            const json = await res.json();
            if (!json.success) throw new Error(json.error || 'Lookup failed');
            return json.data || [];
        }

        function normalizeCityName(s) {
            return (s || '').toLowerCase().replace(/[^a-z]/g, '');
        }

        async function loadPathaoCities() {
            const sel = document.getElementById('pathao-city');
            sel.innerHTML = '<option value="">Loading cities…</option>';
            sel.disabled = true;

            try {
                const cities = await pathaoLookup('cities');
                sel.innerHTML = '<option value="">— Select city —</option>';

                // Prefer matching this specific order's own shipping district over the
                // generic store-wide default — most orders don't all ship to the same
                // place, so guessing from the actual address is more often correct.
                const districtNorm = normalizeCityName(orderShippingDistrict);
                const districtMatch = districtNorm
                    ? cities.find(c => normalizeCityName(c.city_name) === districtNorm)
                    : null;

                let defaultSelected = false;
                cities.forEach(c => {
                    const o = document.createElement('option');
                    o.value = c.city_id;
                    o.textContent = c.city_name;
                    const matchesDistrict = districtMatch && String(c.city_id) === String(districtMatch.city_id);
                    const matchesStoreDefault = !districtMatch && String(c.city_id) === String(pathaoDefaults.city);
                    if (matchesDistrict || matchesStoreDefault) {
                        o.selected = true;
                        defaultSelected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;

                if (districtMatch) {
                    const hint = document.createElement('p');
                    hint.className = 'text-[10px] text-teal-600 mt-1';
                    hint.textContent = `Auto-matched from order address (${orderShippingDistrict})`;
                    sel.parentElement.appendChild(hint);
                }

                // Auto-load zones for whichever city ended up selected above (district
                // match takes priority; falls back to the store-wide default city) —
                // read sel.value rather than pathaoDefaults.city directly, since those
                // can differ once the district match wins.
                if (defaultSelected && sel.value) {
                    await loadPathaoZones(sel.value);
                }
            } catch (e) {
                sel.innerHTML = `<option value="">⚠ ${e.message}</option>`;
                sel.disabled = false;
            }
        }

        async function loadPathaoZones(cityId) {
            if (!cityId) return;
            const sel = document.getElementById('pathao-zone');
            sel.disabled = true;
            sel.innerHTML = '<option value="">Loading zones…</option>';

            try {
                const zones = await pathaoLookup('zones', { city_id: cityId });
                sel.innerHTML = '<option value="">— Select zone —</option>';

                // Upazila is free text at checkout (not a fixed list like district), so
                // this match is looser than the city one: normalize both sides and check
                // either contains the other, to catch things like "Mirpur 10, Dhaka"
                // matching a zone literally named "Mirpur". Still just a pre-selection —
                // never trust it blindly, always shown so it can be corrected.
                const upazilaNorm = normalizeCityName(orderShippingUpazila);
                const upazilaMatch = upazilaNorm
                    ? zones.find(z => {
                        const zn = normalizeCityName(z.zone_name);
                        return zn && (upazilaNorm.includes(zn) || zn.includes(upazilaNorm));
                    })
                    : null;

                let defaultSelected = false;
                zones.forEach(z => {
                    const o = document.createElement('option');
                    o.value = z.zone_id;
                    o.textContent = z.zone_name;
                    const matchesUpazila = upazilaMatch && String(z.zone_id) === String(upazilaMatch.zone_id);
                    const matchesStoreDefault = !upazilaMatch && String(z.zone_id) === String(pathaoDefaults.zone);
                    if (matchesUpazila || matchesStoreDefault) {
                        o.selected = true;
                        defaultSelected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;

                const existingHint = sel.parentElement.querySelector('.zone-match-hint');
                if (existingHint) existingHint.remove();
                if (upazilaMatch) {
                    const hint = document.createElement('p');
                    hint.className = 'zone-match-hint text-[10px] text-teal-600 mt-1';
                    hint.textContent = `Guessed from order address (${orderShippingUpazila}) — please double-check`;
                    sel.parentElement.appendChild(hint);
                }

                // Auto-load areas for whichever zone ended up selected above
                if (defaultSelected && sel.value) {
                    await loadPathaoAreas(sel.value);
                }
            } catch (e) {
                sel.innerHTML = `<option value="">⚠ ${e.message}</option>`;
                sel.disabled = false;
            }
        }

        async function loadPathaoAreas(zoneId) {
            if (!zoneId) return;
            const sel = document.getElementById('pathao-area');
            sel.disabled = true;
            sel.innerHTML = '<option value="">Loading areas…</option>';

            try {
                const areas = await pathaoLookup('areas', { zone_id: zoneId });
                sel.innerHTML = '<option value="">(Optional) Select area</option>';
                areas.forEach(a => {
                    const o = document.createElement('option');
                    o.value = a.area_id;
                    o.textContent = a.area_name;
                    if (String(a.area_id) === String(pathaoDefaults.area)) {
                        o.selected = true;
                    }
                    sel.appendChild(o);
                });
                sel.disabled = false;
            } catch (e) {
                sel.innerHTML = '<option value="">(Area lookup unavailable)</option>';
                sel.disabled = false;
            }
        }

        document.getElementById('pathao-modal')?.addEventListener('click', closePathaoModal);
    </script>
@endpush