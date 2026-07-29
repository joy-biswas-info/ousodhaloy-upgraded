@extends('layouts.admin')
@section('page-title', 'Orders')
@section('content')
    <div class="space-y-4">
        {{-- Filters --}}
        <div class="bg-white rounded-xl border p-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-6 gap-3">

        <div class="md:col-span-2 xl:col-span-2">
            <label class="form-label">Search</label>
            <input
                type="text"
                name="q"
                value="{{ request('q') }}"
                class="form-input"
                placeholder="Order #, name, phone...">
        </div>

        <div>
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                    <option value="{{ $key }}" @selected(request('status') === $key)>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">Payment</label>
            <select name="payment_status" class="form-select">
                <option value="">All</option>
                @foreach(['unpaid', 'pending', 'paid', 'failed', 'refunded'] as $s)
                    <option value="{{ $s }}" @selected(request('payment_status') === $s)>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="form-label">From</label>
            <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="form-input">
        </div>

        <div>
            <label class="form-label">To</label>
            <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="form-input">
        </div>

        <div class="col-span-1 md:col-span-2 xl:col-span-6 flex flex-col sm:flex-row gap-2">
            <button type="submit" class="btn-primary w-full sm:w-auto">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>

            <a href="{{ route('admin.orders.index') }}"
               class="btn-outline w-full sm:w-auto text-center">
                <i class="fas fa-rotate-left mr-2"></i>Reset
            </a>
        </div>

    </form>
</div>

        {{-- Status tabs --}}
        <div class="flex gap-1.5 flex-wrap">
            <a href="{{ route('admin.orders.index') }}"
                class="text-xs px-3 py-1.5 rounded-lg font-semibold {{ !request('status') ? 'bg-teal-600 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
                All ({{ $statusCounts->sum() }})
            </a>
            @foreach(\App\Models\Order::STATUS_LABELS as $key => $label)
                @if($statusCounts[$key] ?? 0)
                    <a href="{{ route('admin.orders.index', ['status' => $key]) }}"
                        class="text-xs px-3 py-1.5 rounded-lg font-semibold {{ request('status') === $key ? 'bg-teal-600 text-white' : 'bg-white border text-gray-600 hover:bg-gray-50' }}">
                        {{ $label }} ({{ $statusCounts[$key] }})
                    </a>
                @endif
            @endforeach
        </div>

        {{-- Bulk actions --}}
        <div class="flex justify-end gap-2 mb-2">
            <a href="{{ route('admin.orders.trash') }}" class="btn-outline text-red-500">
                <i class="fas fa-trash-alt mr-1"></i>Trash
            </a>
            <a href="{{ route('admin.orders.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> New Manual Order
            </a>
        </div>
        {{-- Bulk action form — lives outside the table on purpose. HTML forms
             cannot nest, so the per-row status forms below need the table to
             sit outside this one; the row checkboxes reconnect to this form
             via the form="bulk-form" attribute instead of DOM nesting. --}}
        <form method="POST" action="{{ route('admin.orders.bulk') }}" id="bulk-form">
            @csrf
            <div class="flex items-center gap-3 mb-3">
                <select name="action" class="form-select w-40">
                    <option value="">Bulk action</option>
                    <option value="confirm">Confirm All</option>
                    <option value="shipped">Shipped All</option>
                    <option value="cancel">Cancel All</option>
                    <option value="trash">Move to Trash</option>
                    <option value="export">Export Excel</option>
                </select>
                <button type="submit" class="btn-secondary btn-sm"
                    onclick="return confirm('Apply bulk action?')">Apply</button>
            </div>
        </form>

        {{-- Table --}}
        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all" class="accent-teal-600"></th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Courier</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                        form="bulk-form" class="accent-teal-600 order-cb"></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="font-mono font-bold text-teal-700 hover:underline text-xs">{{ $order->order_number }}</a>
                                </td>
                                <td>
                                    <p class="font-semibold text-xs text-gray-800">{{ $order->customer_name }}</p>
                                    <a href="tel:{{ $order->customer_phone }}" class="text-[10px] text-gray-500 hover:text-teal-600 hover:underline">{{ $order->customer_phone }}</a>
                                </td>
                                <td class="text-xs text-gray-500">{{ $order->items->count() }} items</td>
                                <td class="font-bold text-teal-700 text-sm">৳{{ number_format($order->total, 0) }}</td>
                                <td>
                                    @if($order->pathao_consignment_id)
                                        <span class="text-xs bg-teal-100 text-teal-700 px-2 py-0.5 rounded font-semibold">🚚
                                            Pathao</span>
                                    @elseif($order->steadfast_consignment_id)
                                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded font-semibold">📦
                                            Steadfast</span>
                                    @else
                                        <span class="text-xs text-gray-300">—</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Quick status update — its own form, kept out of #bulk-form
                                         so this select's this.form.submit() actually targets it. --}}
                                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                                        @csrf
                                        <input type="hidden" name="notify_customer" value="1">
                                        <input type="hidden" name="confirm_courier_cancel" value="0">
                                        <select name="status" onchange="handleCourierCancelSelect(this)"
                                            data-current="{{ $order->status }}"
                                            data-courier="{{ $order->pathao_consignment_id ? 'Pathao (consignment ' . $order->pathao_consignment_id . ')' : ($order->steadfast_consignment_id ? 'Steadfast (consignment ' . $order->steadfast_consignment_id . ')' : '') }}"
                                            class="form-select text-xs py-1 px-2 w-auto">
                                            <option value="{{ $order->status }}" selected>{{ $order->status_label }}</option>
                                            @foreach(\App\Models\Order::STATUS_FLOW[$order->status] ?? [] as $key)
                                                <option value="{{ $key }}">{{ \App\Models\Order::STATUS_LABELS[$key] }}{{ $order->status === 'on_hold' && $order->held_from_status === $key ? ' ↩' : '' }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td class="text-xs text-gray-500 whitespace-nowrap">
                                    {{ $order->created_at->format('d M, h:i A') }}</td>
                                <td>
                                    <div class="flex gap-1.5 flex-wrap">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary btn-sm">View</a>
                                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}"
                                            onsubmit="return confirm('Move order {{ $order->order_number }} to trash?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm" title="Move to Trash">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-12 text-gray-400 text-sm">No orders found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $orders->withQueryString()->links() }}
    </div>

    @push('scripts')
        <script>
            document.getElementById('select-all').addEventListener('change', function () {
                document.querySelectorAll('.order-cb').forEach(cb => cb.checked = this.checked);
            });

            // Neither courier API supports cancelling a consignment — cancelling
            // here only changes our own status, so confirm the admin understands
            // they still need to cancel manually in the courier's dashboard.
            function handleCourierCancelSelect(select) {
                const form = select.form;
                const courier = select.dataset.courier;
                if (select.value === 'cancelled' && courier) {
                    if (!confirm(`This order was already pushed to ${courier}. Cancelling here only updates the order status — you must also cancel it manually in the courier's merchant dashboard. Continue?`)) {
                        select.value = select.dataset.current;
                        return;
                    }
                    form.querySelector('[name=confirm_courier_cancel]').value = '1';
                }
                form.submit();
            }
        </script>
    @endpush
@endsection