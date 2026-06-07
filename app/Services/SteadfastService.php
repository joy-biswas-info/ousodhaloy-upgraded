<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\{Order, Setting};

class SteadfastService
{
    // Correct base URL from official Steadfast API docs
    private string $baseUrl = 'https://portal.packzy.com/api/v1';
    private string $apiKey;
    private string $secretKey;

    public function __construct()
    {
        $s = Setting::getGroup('steadfast');

        $this->apiKey = $s['steadfast_api_key'] ?? config('services.steadfast.api_key', '');
        $this->secretKey = $s['steadfast_secret_key'] ?? config('services.steadfast.secret_key', '');
    }

    private function headers(): array
    {
        return [
            'Api-Key' => $this->apiKey,
            'Secret-Key' => $this->secretKey,
            'Content-Type' => 'application/json',
        ];
    }

    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && !empty($this->secretKey);
    }

    // ── Place a single order ──────────────────────────────────────────────

    public function createOrder(Order $order): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Steadfast credentials not configured. Go to Settings → Steadfast.'];
        }
        // Normalise phone: must be exactly 11 digits (01XXXXXXXXX)
        $phone = preg_replace('/\D/', '', $order->shipping_phone);
        if (str_starts_with($phone, '880'))
            $phone = '0' . substr($phone, 3);
        elseif (str_starts_with($phone, '88'))
            $phone = '0' . substr($phone, 2);

        // COD: collect if not already paid — cast to int per docs ("numeric")
        $cod = $order->payment_status !== 'paid' ? (int) ceil($order->total) : 0;

        // recipient_address max 250 chars per docs
        $address = mb_substr(trim(implode(', ', array_filter([
            $order->shipping_address,
            $order->shipping_upazila,
            $order->shipping_district,
            $order->shipping_division,
        ]))), 0, 250);

        $payload = [
            'invoice' => $order->order_number,        // unique, alphanumeric + hyphens
            'recipient_name' => mb_substr($order->shipping_name, 0, 100),
            'recipient_phone' => $phone,
            'recipient_address' => $address,
            'cod_amount' => $cod,
            'note' => mb_substr($order->customer_note ?? '', 0, 200),
            'item_description' => mb_substr(
                $order->items->pluck('product_name')->implode(', '),
                0,
                250
            ),
        ];

        // Optional: recipient email
        if ($order->shipping_email) {
            $payload['recipient_email'] = $order->shipping_email;
        }

        $res = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/create_order", $payload);

        // Steadfast returns {"status": 200, "consignment": {...}} on success
        if ($res->successful() && (int) $res->json('status') === 200) {
            $consignment = $res->json('consignment') ?? [];
            $consignmentId = $consignment['consignment_id'] ?? null;

            $trackingCode = $consignment['tracking_code'] ?? null;

            $order->update([
                'courier' => 'steadfast',
                'steadfast_consignment_id' => $consignmentId,
                'steadfast_tracking_code' => $trackingCode,
                'steadfast_status' => $consignment['status'] ?? 'in_review',
            ]);

            return ['success' => true, 'data' => $consignment];
        }

        // Surface the actual error message
        $errorMsg = $res->json('message') ?? null;
        if (!$errorMsg) {
            $errors = $res->json('errors') ?? $res->json('data') ?? null;
            if (is_array($errors)) {
                $errorMsg = collect($errors)
                    ->map(fn($v, $k) => is_array($v) ? "$k: " . implode(', ', $v) : "$k: $v")
                    ->implode(' | ');
            }
        }
        $errorMsg = $errorMsg ?? ('HTTP ' . $res->status() . ': ' . mb_substr($res->body(), 0, 300));

        return ['success' => false, 'error' => $errorMsg];
    }

    // ── Bulk order create (up to 500 orders) ─────────────────────────────

    public function bulkCreateOrders(array $orders): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Steadfast not configured.'];
        }

        $data = collect($orders)->map(function (Order $order) {
            $phone = preg_replace('/\D/', '', $order->shipping_phone);
            if (str_starts_with($phone, '880'))
                $phone = '0' . substr($phone, 3);
            elseif (str_starts_with($phone, '88'))
                $phone = '0' . substr($phone, 2);

            return [
                'invoice' => $order->order_number,
                'recipient_name' => mb_substr($order->shipping_name, 0, 100),
                'recipient_phone' => $phone,
                'recipient_address' => mb_substr(trim(implode(', ', array_filter([
                    $order->shipping_address,
                    $order->shipping_upazila,
                    $order->shipping_district,
                    $order->shipping_division,
                ]))), 0, 250),
                'cod_amount' => $order->payment_status !== 'paid' ? (int) ceil($order->total) : 0,
                'note' => mb_substr($order->customer_note ?? '', 0, 200),
            ];
        })->values()->toArray();

        $res = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/create_order/bulk-order", [
                'data' => json_encode($data),
            ]);

        if ($res->successful()) {
            $results = $res->json();
            if (!is_array($results))
                $results = [];

            // Update each order that succeeded
            foreach ($results as $item) {
                if (($item['status'] ?? '') === 'success' && !empty($item['invoice'])) {
                    $order = collect($orders)->firstWhere(fn($o) => $o->order_number === $item['invoice']);
                    if ($order) {
                        $order->update([
                            'courier' => 'steadfast',
                            'steadfast_consignment_id' => $item['consignment_id'] ?? null,
                            'steadfast_tracking_code' => $item['tracking_code'] ?? null,
                            'steadfast_status' => 'in_review',
                        ]);
                    }
                }
            }

            $success = collect($results)->where('status', 'success')->count();
            $failed = collect($results)->where('status', 'error')->count();

            return [
                'success' => true,
                'summary' => "{$success} pushed, {$failed} failed",
                'data' => $results,
            ];
        }

        return ['success' => false, 'error' => 'HTTP ' . $res->status() . ': ' . mb_substr($res->body(), 0, 300)];
    }

    // ── Delivery status ───────────────────────────────────────────────────

    public function getStatus(string $consignmentId): array
    {
        // Use consignment ID endpoint as per docs: /status_by_cid/{id}
        $res = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/status_by_cid/{$consignmentId}");

        if ($res->successful() && (int) $res->json('status') === 200) {
            return [
                'success' => true,
                'delivery_status' => $res->json('delivery_status'),
            ];
        }
        return ['success' => false, 'error' => $res->json('message') ?? 'Failed'];
    }

    // ── Sync order status ─────────────────────────────────────────────────

    public function syncOrderStatus(Order $order): bool
    {
        if (!$order->steadfast_consignment_id)
            return false;

        $result = $this->getStatus((string) $order->steadfast_consignment_id);
        if (!$result['success'])
            return false;

        $sfStatus = $result['delivery_status'] ?? null;
        if (!$sfStatus || $sfStatus === $order->steadfast_status)
            return false;

        $order->update(['steadfast_status' => $sfStatus]);

        // Map Steadfast delivery statuses → our order statuses
        $statusMap = [
            'delivered' => 'delivered',
            'partial_delivered' => 'delivered',
            'cancelled' => 'cancelled',
            'hold' => 'on_hold',
            'delivered_approval_pending' => 'delivered',
            'partial_delivered_approval_pending' => 'delivered',
            'cancelled_approval_pending' => 'cancelled',
        ];

        $mapped = $statusMap[$sfStatus] ?? null;
        if ($mapped) {
            app(OrderService::class)->updateStatus($order, $mapped, 'Synced from Steadfast', false);
        }

        return true;
    }

    // ── Account balance ───────────────────────────────────────────────────

    public function getBalance(): array
    {
        $res = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/get_balance");

        if ($res->successful() && (int) $res->json('status') === 200) {
            return ['success' => true, 'current_balance' => $res->json('current_balance') ?? 0];
        }
        return ['success' => false, 'balance' => 0];
    }

    // ── Return request ────────────────────────────────────────────────────

    public function createReturnRequest(string $consignmentId, string $reason = ''): array
    {
        $res = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/create_return_request", [
                'consignment_id' => $consignmentId,
                'reason' => $reason,
            ]);

        if ($res->successful()) {
            return ['success' => true, 'data' => $res->json()];
        }
        return ['success' => false, 'error' => $res->json('message') ?? 'Failed'];
    }
}