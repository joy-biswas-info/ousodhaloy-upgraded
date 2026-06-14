@extends('layouts.admin')
@section('page-title', 'SMS Logs')

@section('content')
<div class="space-y-4">

    {{-- Stats --}}
    <div class="grid grid-cols-4 gap-3">
        @foreach([
            ['label' => 'Total',  'value' => $stats->total,  'color' => 'blue'],
            ['label' => 'Sent',   'value' => $stats->sent,   'color' => 'green'],
            ['label' => 'Failed', 'value' => $stats->failed, 'color' => 'red'],
            ['label' => 'Queued', 'value' => $stats->queued, 'color' => 'yellow'],
        ] as $card)
        <div class="bg-white rounded-xl border p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-{{ $card['color'] }}-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-sms text-{{ $card['color'] }}-600 text-sm"></i>
            </div>
            <div>
                <p class="text-xs text-gray-500">{{ $card['label'] }}</p>
                <p class="text-xl font-black text-gray-800">{{ number_format($card['value']) }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-xl border p-4">
        <form method="GET" class="flex gap-3 flex-wrap items-center">
            <select name="status" class="form-select text-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                @foreach(['sent' => 'Sent', 'failed' => 'Failed', 'queued' => 'Queued'] as $val => $label)
                <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <select name="purpose" class="form-select text-sm" onchange="this.form.submit()">
                <option value="">All Types</option>
                @foreach(['order_placed' => 'Order Placed', 'status_update' => 'Status Update', 'otp' => 'OTP', 'low_stock' => 'Low Stock'] as $val => $label)
                <option value="{{ $val }}" {{ request('purpose') === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @if(request()->hasAny(['status', 'purpose']))
            <a href="{{ route('admin.sms-logs') }}" class="btn-outline text-sm">Clear filters</a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Phone</th>
                        <th>Message</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Order</th>
                        <th>Sent At</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td class="font-mono text-sm">{{ $log->phone }}</td>
                        <td class="max-w-xs">
                            <p class="text-xs text-gray-600 line-clamp-2">{{ $log->message }}</p>
                        </td>
                        <td>
                            @php
                                $purposeLabels = [
                                    'order_placed'  => ['Order Placed',   'blue'],
                                    'status_update' => ['Status Update',  'purple'],
                                    'otp'           => ['OTP',            'orange'],
                                    'low_stock'     => ['Low Stock',      'red'],
                                    'general'       => ['General',        'gray'],
                                ];
                                [$purposeLabel, $purposeColor] = $purposeLabels[$log->purpose] ?? [ucfirst(str_replace('_', ' ', $log->purpose ?? 'general')), 'gray'];
                            @endphp
                            <span class="text-xs bg-{{ $purposeColor }}-100 text-{{ $purposeColor }}-700 px-2 py-0.5 rounded font-semibold">
                                {{ $purposeLabel }}
                            </span>
                        </td>
                        <td>
                            @php
                                $statusConfig = [
                                    'sent'   => ['Sent',   'text-green-600',  'fa-check-circle'],
                                    'failed' => ['Failed', 'text-red-600',    'fa-times-circle'],
                                    'queued' => ['Queued', 'text-yellow-600', 'fa-clock'],
                                ];
                                [$statusLabel, $statusColor, $statusIcon] = $statusConfig[$log->status] ?? [ucfirst($log->status), 'text-gray-500', 'fa-circle'];
                            @endphp
                            <span class="text-xs font-semibold {{ $statusColor }} flex items-center gap-1">
                                <i class="fas {{ $statusIcon }}"></i> {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            @if($log->order_id)
                            <a href="{{ route('admin.orders.show', $log->order_id) }}" class="text-xs text-teal-700 hover:underline font-mono">
                                #{{ $log->order_id }}
                            </a>
                            @else
                            <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d M Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-gray-400">
                            <i class="fas fa-sms text-3xl mb-2 block"></i>
                            No SMS logs {{ request()->hasAny(['status','purpose']) ? 'matching filters' : 'yet' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t flex items-center justify-between">
            <p class="text-xs text-gray-400">
                Showing {{ $logs->firstItem() ?? 0 }}–{{ $logs->lastItem() ?? 0 }} of {{ $logs->total() }} logs
            </p>
            {{ $logs->withQueryString()->links() }}
        </div>
    </div>
</div>
@endsection