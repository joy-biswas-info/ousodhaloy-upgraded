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
        <div class="flex justify-end mb-2">
            <a href="{{ route('admin.orders.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-1"></i> New Manual Order
            </a>
        </div>
        <form method="POST" action="{{ route('admin.orders.bulk') }}" id="bulk-form">
            @csrf
            <div class="flex items-center gap-3 mb-3">
                <select name="action" class="form-select w-40">
                    <option value="">Bulk action</option>
                    <option value="confirm">Confirm All</option>
                    <option value="shipped">Shipped All</option>
                    <option value="cancel">Cancel All</option>
                    <option value="export">Export Excel</option>
                </select>
                <button type="submit" class="btn-secondary btn-sm"
                    onclick="return confirm('Apply bulk action?')">Apply</button>
            </div>

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
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td><input type="checkbox" name="order_ids[]" value="{{ $order->id }}"
                                            class="accent-teal-600 order-cb"></td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}"
                                            class="font-mono font-bold text-teal-700 hover:underline text-xs">{{ $order->order_number }}</a>
                                    </td>
                                    <td>
                                        <p class="font-semibold text-xs text-gray-800">{{ $order->customer_name }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $order->customer_phone }}</p>
                                    </td>
                                    <td class="text-xs text-gray-500">{{ $order->items->count() }} items</td>
                                    <td class="font-bold text-teal-700 text-sm">৳{{ number_format($order->total, 0) }}</td>
                                    <td>
                                        <span
                                            class="text-xs font-semibold capitalize {{ $order->payment_status === 'paid' ? 'text-green-600' : ($order->payment_status === 'failed' ? 'text-red-600' : 'text-yellow-600') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                        <p class="text-[10px] text-gray-400 capitalize">
                                            {{ str_replace('_', ' ', $order->payment_method) }}</p>
                                    </td>
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
                                    <td><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span>
                                    </td>
                                    <td class="text-xs text-gray-500 whitespace-nowrap">
                                        {{ $order->created_at->format('d M, h:i A') }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-secondary btn-sm">View</a>
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
        </form>

        {{ $orders->withQueryString()->links() }}
    </div>

    @push('scripts')
        <script>
            document.getElementById('select-all').addEventListener('change', function () {
                document.querySelectorAll('.order-cb').forEach(cb => cb.checked = this.checked);
            });
        </script>
    @endpush
@endsection