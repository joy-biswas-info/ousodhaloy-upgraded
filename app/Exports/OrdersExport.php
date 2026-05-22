<?php
// app/Exports/OrdersExport.php
namespace App\Exports;

use App\Models\Order;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{FromCollection, WithHeadings, WithMapping, ShouldAutoSize};

class OrdersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(private Collection $orders) {}

    public function collection(): Collection { return $this->orders->load('items'); }

    public function headings(): array {
        return ['Order #','Customer','Phone','Division','District','Total','Payment Method','Payment Status','Status','Items','Date'];
    }

    public function map($order): array {
        return [
            $order->order_number,
            $order->customer_name,
            $order->customer_phone,
            $order->shipping_division,
            $order->shipping_district,
            '৳' . number_format($order->total, 2),
            ucwords(str_replace('_', ' ', $order->payment_method)),
            ucfirst($order->payment_status),
            $order->status_label,
            $order->items->count(),
            $order->created_at->format('Y-m-d H:i'),
        ];
    }
}
