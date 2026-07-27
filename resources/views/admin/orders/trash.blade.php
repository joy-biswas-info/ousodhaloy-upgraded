@extends('layouts.admin')
@section('page-title', 'Order Trash')
@section('breadcrumb', 'Orders / Trash')

@section('content')
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <p class="text-sm text-gray-500">{{ $orders->total() }} deleted order(s) — restore or permanently delete.</p>
            <a href="{{ route('admin.orders.index') }}" class="btn-outline btn-sm">
                <i class="fas fa-arrow-left mr-1"></i>Back to Orders
            </a>
        </div>

        <div class="bg-white rounded-xl border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Deleted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="opacity-75 hover:opacity-100">
                                <td>
                                    <p class="font-mono font-bold text-gray-600 text-xs line-through">{{ $order->order_number }}</p>
                                    <p class="text-xs text-gray-400">{{ $order->items->count() }} items</p>
                                </td>
                                <td>
                                    <p class="font-semibold text-xs text-gray-700">{{ $order->customer_name }}</p>
                                    <p class="text-[10px] text-gray-400">{{ $order->customer_phone }}</p>
                                </td>
                                <td class="text-sm font-semibold text-gray-600">৳{{ number_format($order->total, 2) }}</td>
                                <td><span class="status-badge status-{{ $order->status }}">{{ $order->status_label }}</span></td>
                                <td class="text-xs text-gray-400">{{ $order->deleted_at->diffForHumans() }}</td>
                                <td>
                                    <div class="flex gap-1.5">
                                        <form method="POST" action="{{ route('admin.orders.restore', $order->id) }}">
                                            @csrf @method('PATCH')
                                            <button type="submit" class="btn-secondary btn-sm">
                                                <i class="fas fa-undo mr-1"></i>Restore
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.orders.force-delete', $order->id) }}"
                                            onsubmit="return confirm('Permanently delete order {{ $order->order_number }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-danger btn-sm">
                                                <i class="fas fa-trash mr-1"></i>Delete Forever
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-16 text-gray-400">
                                    <i class="fas fa-trash-alt text-4xl mb-3 block opacity-30"></i>
                                    <p class="font-semibold">Trash is empty</p>
                                    <p class="text-xs mt-1">Deleted orders appear here</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4">{{ $orders->links() }}</div>
        </div>
    </div>
@endsection
