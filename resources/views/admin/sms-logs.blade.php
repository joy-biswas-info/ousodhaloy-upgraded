@extends('layouts.admin')
@section('page-title', 'SMS Logs')

@section('content')
<div class="space-y-4">

    <div class="grid grid-cols-3 gap-3">
        @foreach([
            ['label' => 'Total Sent',  'value' => \App\Models\SmsLog::where('status','sent')->count(),   'color' => 'green'],
            ['label' => 'Failed',      'value' => \App\Models\SmsLog::where('status','failed')->count(),  'color' => 'red'],
            ['label' => 'Queued',      'value' => \App\Models\SmsLog::where('status','queued')->count(),  'color' => 'yellow'],
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
                            <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded font-semibold capitalize">
                                {{ str_replace('_',' ',$log->purpose ?? 'general') }}
                            </span>
                        </td>
                        <td>
                            <span class="text-xs font-semibold {{ $log->status === 'sent' ? 'text-green-600' : ($log->status === 'failed' ? 'text-red-600' : 'text-yellow-600') }}">
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td>
                            @if($log->order_id)
                            <a href="{{ route('admin.orders.show', $log->order_id) }}" class="text-xs text-teal-700 hover:underline font-mono">
                                #{{ $log->order_id }}
                            </a>
                            @else <span class="text-xs text-gray-400">—</span>@endif
                        </td>
                        <td class="text-xs text-gray-400 whitespace-nowrap">{{ $log->created_at->format('d M Y h:i A') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center py-12 text-gray-400">No SMS logs yet</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
