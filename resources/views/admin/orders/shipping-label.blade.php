<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label — {{ $order->order_number }}</title>
    <style>
        /* ── Reset ── */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* ── Page size: 100mm × 150mm thermal label ── */
        @page {
            size: 100mm 150mm;
            margin: 0;
        }

        html,
        body {
            width: 100mm;
            height: 150mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 9pt;
            color: #000;
            background: #fff;
        }

        /* ── Screen: show a centred card with print button ── */
        @media screen {
            body {
                width: 100%;
                height: auto;
                background: #f3f4f6;
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 20px;
                gap: 12px;
            }

            .print-toolbar {
                display: flex;
                align-items: center;
                gap: 10px;
                background: #1f2937;
                color: #fff;
                padding: 10px 20px;
                border-radius: 10px;
                font-size: 13px;
                font-family: Arial, sans-serif;
                width: 100mm;
            }

            .print-toolbar button {
                background: #0e7673;
                color: #fff;
                border: none;
                padding: 7px 18px;
                border-radius: 7px;
                font-size: 13px;
                font-weight: 700;
                cursor: pointer;
                display: flex;
                align-items: center;
                gap: 6px;
            }

            .print-toolbar button:hover {
                background: #0a5250;
            }

            .print-toolbar .spacer {
                flex: 1;
            }

            .print-toolbar a {
                color: #9ca3af;
                text-decoration: none;
                font-size: 12px;
            }

            .label-card {
                width: 100mm;
                background: #fff;
                border: 1px solid #d1d5db;
                border-radius: 6px;
                overflow: hidden;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
            }
        }

        /* ── Print: no toolbar, no shadow ── */
        @media print {
            .print-toolbar {
                display: none !important;
            }

            .label-card {
                width: 100mm;
                height: 150mm;
                border: none;
                border-radius: 0;
                box-shadow: none;
                overflow: hidden;
                page-break-after: avoid;
            }
        }

        /* ── Label layout ── */
        .label-inner {
            width: 100mm;
            min-height: 148mm;
            display: flex;
            flex-direction: column;
            border: 1.5px solid #000;
        }

        /* Header */
        .lh {
            background: #000;
            color: #fff;
            padding: 4px 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
        }

        .lh-brand {
            font-size: 12pt;
            font-weight: 900;
            letter-spacing: 0.3px;
        }

        .lh-right {
            text-align: right;
        }

        .lh-badge {
            display: inline-block;
            background: #fff;
            color: #000;
            font-size: 7pt;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            padding: 1px 5px;
            border-radius: 2px;
        }

        .lh-date {
            font-size: 6pt;
            color: #aaa;
            margin-top: 1px;
        }

        /* Sections */
        .ls {
            border-bottom: 1.5px solid #000;
            padding: 4px 6px;
            flex-shrink: 0;
        }

        .ls:last-child {
            border-bottom: none;
            flex: 1;
        }

        .ls-title {
            font-size: 6pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #555;
            margin-bottom: 2px;
        }

        .ls-name {
            font-size: 11pt;
            font-weight: 900;
            line-height: 1.2;
        }

        .ls-line {
            font-size: 8pt;
            line-height: 1.5;
        }

        .ls-bold {
            font-weight: 700;
        }

        /* COD highlight */
        .cod-box {
            border-bottom: 1.5px solid #000;
            padding: 5px 6px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }

        .cod-label {
            font-size: 6.5pt;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #555;
        }

        .cod-amount {
            font-size: 16pt;
            font-weight: 900;
        }

        .cod-prepaid {
            font-size: 8pt;
            font-weight: 700;
            color: #059669;
        }

        /* Two-col row */
        .lr {
            display: flex;
            border-bottom: 1.5px solid #000;
            flex-shrink: 0;
        }

        .lc {
            flex: 1;
            padding: 4px 6px;
        }

        .lc+.lc {
            border-left: 1px solid #ddd;
        }

        /* Items */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        .items-table th {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #666;
            padding: 0 0 2px;
            text-align: left;
        }

        .items-table td {
            padding: 1px 0;
            border-bottom: 1px dotted #e5e7eb;
        }

        .items-table td:last-child {
            text-align: right;
            white-space: nowrap;
        }

        .items-total {
            text-align: right;
            font-size: 8pt;
            font-weight: 800;
            margin-top: 3px;
            padding-top: 2px;
            border-top: 1px solid #000;
        }

        /* Tracking bar */
        .track-bar {
            background: #f5f5f5;
            border-top: 1.5px solid #000;
            padding: 4px 6px;
            text-align: center;
            flex-shrink: 0;
        }

        .track-label {
            font-size: 6pt;
            text-transform: uppercase;
            letter-spacing: 0.7px;
            color: #666;
        }

        .track-code {
            font-size: 13pt;
            font-weight: 900;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            margin-top: 1px;
        }

        /* Footer */
        .lf {
            background: #f9f9f9;
            border-top: 1.5px solid #000;
            padding: 3px 6px;
            text-align: center;
            font-size: 6pt;
            color: #777;
            flex-shrink: 0;
        }
    </style>
</head>

<body>

    {{-- Screen toolbar --}}
    <div class="print-toolbar">
        <span>Label: <strong>{{ $order->order_number }}</strong></span>
        <span class="spacer"></span>
        <a href="{{ route('admin.orders.show', $order) }}">← Back</a>
        <button onclick="window.print()">
            🖨 Print Label
        </button>
    </div>

    {{-- Label card --}}
    <div class="label-card">
        <div class="label-inner">

            {{-- Header --}}
            <div class="lh">
                <div>
                    <div class="lh-brand">{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}</div>
                    <div style="font-size:7pt;color:#ccc;">#{{ $order->order_number }}</div>
                </div>
                <div class="lh-right">
                    <div>
                        <span class="lh-badge">
                            @if($courier === 'steadfast') Steadfast
                            @elseif($courier === 'pathao') Pathao
                                @else Courier
                            @endif
                        </span>
                    </div>
                    <div class="lh-date">{{ now()->format('d M Y') }}</div>
                </div>
            </div>

            {{-- Sender --}}
            <div class="ls">
                <div class="ls-title">From</div>
                <div class="ls-bold" style="font-size:9pt;">{{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }}
                </div>
                <div class="ls-line">{{ \App\Models\Setting::get('site_phone', '') }} &nbsp;|&nbsp;
                    {{ \App\Models\Setting::get('site_address', 'Dhaka, Bangladesh') }}
                </div>
            </div>

            {{-- Recipient --}}
            <div class="ls">
                <div class="ls-title">To</div>
                <div class="ls-name">{{ $order->shipping_name }}</div>
                <div class="ls-line ls-bold">{{ $order->shipping_phone }}</div>
                @if($order->shipping_email)
                    <div class="ls-line">{{ $order->shipping_email }}</div>
                @endif
                <div class="ls-line" style="margin-top:2px;">
                    {{ $order->shipping_address }},<br>
                    {{ $order->shipping_upazila }}, {{ $order->shipping_district }},
                    {{ $order->shipping_division }}
                    @if($order->shipping_postcode) — {{ $order->shipping_postcode }}@endif
                </div>
            </div>

            {{-- COD --}}
            <div class="cod-box">
                @if($order->payment_method === 'cod' && $order->payment_status !== 'paid')
                    <div>
                        <div class="cod-label">Cash on Delivery</div>
                        <div class="cod-amount">৳{{ number_format($order->total, 0) }}</div>
                    </div>
                    <div style="font-size:8pt;text-align:right;color:#555;">
                        Collect this<br>amount on delivery
                    </div>
                @else
                    <div class="cod-prepaid">✅ PREPAID — No Cash Collection</div>
                    <div style="font-size:8pt;color:#555;">৳{{ number_format($order->total, 0) }}</div>
                @endif
            </div>

            {{-- Courier + Order info --}}
            <div class="lr">
                <div class="lc">
                    <div class="ls-title">Tracking</div>
                    @php
                        $trackCode = $courier === 'steadfast'
                            ? ($order->steadfast_consignment_id)
                            : ($order->pathao_consignment_id);
                    @endphp
                    <div style="font-size:8pt;font-weight:900;font-family:monospace;word-break:break-all;">
                        {{ $trackCode ?? 'N/A' }}
                    </div>
                </div>
                <div class="lc">
                    <div class="ls-title">Parcel Info</div>
                    <div class="ls-line">Items: <span class="ls-bold">{{ $order->items->sum('quantity') }}</span> units
                    </div>
                    <div class="ls-line">Weight: ~<span
                            class="ls-bold">{{ max(0.5, round($order->items->sum('quantity') * 0.5 * 2) / 2) }}</span>
                        kg</div>
                    <div class="ls-line">Payment: <span
                            class="ls-bold">{{ strtoupper(str_replace('_', ' ', $order->payment_method)) }}</span></div>
                </div>
            </div>

            {{-- Items --}}
            <div class="ls" style="flex:1;">
                <div class="ls-title">Contents</div>
                <table class="items-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th style="text-align:center">Qty</th>
                            <th style="text-align:right">৳</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ \Illuminate\Support\Str::limit($item->product_name, 28) }}</td>
                                <td style="text-align:center">{{ $item->quantity }}</td>
                                <td>{{ number_format($item->subtotal, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="items-total">Total: ৳{{ number_format($order->total, 0) }}</div>

                @if($order->customer_note)
                    <div
                        style="margin-top:4px;padding:3px;background:#fffbeb;border:1px solid #fcd34d;border-radius:3px;font-size:7pt;">
                        ⚠ {{ $order->customer_note }}
                    </div>
                @endif
            </div>

            {{-- Tracking bar --}}
            @if($trackCode)
                <div class="track-bar">
                    <div class="track-label">Tracking Code</div>
                    <div class="track-code">{{ $trackCode }}</div>
                </div>
            @endif

            {{-- Footer --}}
            <div class="lf">
                {{ \App\Models\Setting::get('site_name', 'Ousodhaloy') }} ·
                {{ \App\Models\Setting::get('site_phone', '') }} ·
                Printed {{ now()->format('d M Y H:i') }}
            </div>

        </div>
    </div>

    <script>
        // Auto-open print dialog when page loads
        window.addEventListener('load', () => {
            // Small delay so the page renders first
            setTimeout(() => window.print(), 400);
        });
    </script>
</body>

</html>