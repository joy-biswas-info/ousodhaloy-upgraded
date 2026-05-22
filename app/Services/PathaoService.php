<?php
namespace App\Services;

use Illuminate\Support\Facades\{Http, Cache};
use App\Models\{Order, Setting};

class PathaoService
{
    private string $baseUrl;
    private string $clientId;
    private string $clientSecret;
    private string $username;
    private string $password;
    private string $storeId;
    private bool $isLive;

    public function __construct()
    {
        $s = Setting::getGroup('pathao');

        $this->clientId = $s['pathao_client_id'] ?? config('services.pathao.client_id', '');
        $this->clientSecret = $s['pathao_client_secret'] ?? config('services.pathao.client_secret', '');
        $this->username = $s['pathao_username'] ?? config('services.pathao.username', '');
        $this->password = $s['pathao_password'] ?? config('services.pathao.password', '');
        $this->storeId = $s['pathao_store_id'] ?? config('services.pathao.store_id', '');
        $this->isLive = ($s['pathao_is_live'] ?? 'false') === 'true';

        $this->baseUrl = $this->isLive
            ? 'https://api-hermes.pathao.com'
            : 'https://hermes-api.p-stageenv.xyz';
    }

    public function isConfigured(): bool
    {
        return !empty($this->clientId)
            && !empty($this->username)
            && !empty($this->password)
            && !empty($this->storeId);
    }

    // ── Auth ──────────────────────────────────────────────────────────────

    private function getToken(): string
    {
        $cacheKey = 'pathao_token_' . md5($this->clientId . $this->username);

        return Cache::remember($cacheKey, 3500, function () {
            $response = Http::post("{$this->baseUrl}/aladdin/api/v1/issue-token", [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'username' => $this->username,
                'password' => $this->password,
                'grant_type' => 'password',
            ]);

            if (!$response->successful()) {
                throw new \Exception(
                    'Pathao auth failed: ' . ($response->json('message') ?? $response->body())
                );
            }

            return $response->json('access_token');
        });
    }

    private function headers(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getToken(),
            'Content-Type' => 'application/json',
        ];
    }

    // ── Lookup lists (cached 24h) ─────────────────────────────────────────

    public function getCities(): array
    {
        return Cache::remember('pathao_cities_' . $this->clientId, 86400, function () {
            $res = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/aladdin/api/v1/countries/1/city-list");
            return $res->json('data.data') ?? [];
        });
    }

    public function getZones(int $cityId): array
    {
        return Cache::remember("pathao_zones_{$cityId}_{$this->clientId}", 86400, function () use ($cityId) {
            $res = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/aladdin/api/v1/cities/{$cityId}/zone-list");
            return $res->json('data.data') ?? [];
        });
    }

    public function getAreas(int $zoneId): array
    {
        return Cache::remember("pathao_areas_{$zoneId}_{$this->clientId}", 86400, function () use ($zoneId) {
            $res = Http::withHeaders($this->headers())
                ->get("{$this->baseUrl}/aladdin/api/v1/zones/{$zoneId}/area-list");
            return $res->json('data.data') ?? [];
        });
    }

    // ── Create order ──────────────────────────────────────────────────────

    public function createOrder(Order $order, ?int $cityId = null, ?int $zoneId = null, ?int $areaId = null): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'Pathao credentials not configured. Go to Settings → Pathao.'];
        }

        // Use passed IDs → fall back to stored defaults → fall back to 1
        $s = Setting::getGroup('pathao');
        $recipientCity = $cityId ?? (int) ($s['pathao_default_city_id'] ?? 1);
        $recipientZone = $zoneId ?? (int) ($s['pathao_default_zone_id'] ?? 1);
        $recipientArea = $areaId ?? (int) ($s['pathao_default_area_id'] ?? 0) ?: null;

        $items = $order->items;
        $totalQty = $items->sum('quantity');

        $payload = [
            'store_id' => (int) $this->storeId,
            'merchant_order_id' => $order->order_number,
            'recipient_name' => $order->shipping_name,
            'recipient_phone' => $order->shipping_phone,
            'recipient_address' => implode(', ', array_filter([
                $order->shipping_address,
                $order->shipping_upazila,
                $order->shipping_district,
            ])),
            'recipient_city' => $recipientCity,
            'recipient_zone' => $recipientZone,
            'delivery_type' => 48,
            'item_type' => 2,
            'special_instruction' => (string) ($order->customer_note ?? ''),
            'item_quantity' => $totalQty,
            'item_weight' => (float) max(0.5, round($totalQty * 0.1, 1)),
            'amount_to_collect' => $order->payment_status !== 'paid' ? (int) $order->total : 0,
            'item_description' => $items->pluck('product_name')->implode(', '),
        ];

        // area is optional — only add if we have one
        if ($recipientArea) {
            $payload['recipient_area'] = $recipientArea;
        }

        $res = Http::withHeaders($this->headers())
            ->post("{$this->baseUrl}/aladdin/api/v1/orders", $payload);
        if ($res->successful()) {
            $data = $res->json('data') ?? [];
            $order->update([
                'courier' => 'pathao',
                'pathao_order_id' => $data['order_id'] ?? null,
                'pathao_consignment_id' => $data['consignment_id'] ?? null,
                'pathao_status' => 'Pending',
                'pathao_tracking_code' => $data['consignment_id'] ?? null,
            ]);
            return ['success' => true, 'data' => $data];
        }

        // Return the full validation errors so admin can see what's wrong
        return [
            'success' => false,
            'error' => $res->json('message')
                ?? json_encode($res->json('errors') ?? $res->json())
                ?? 'Pathao API error (HTTP ' . $res->status() . ')',
        ];
    }

    // ── Status ────────────────────────────────────────────────────────────

    public function getOrderStatus(string $consignmentId): array
    {
        $res = Http::withHeaders($this->headers())
            ->get("{$this->baseUrl}/aladdin/api/v1/orders/{$consignmentId}/info");

        if ($res->successful()) {
            return ['success' => true, 'data' => $res->json('data')];
        }
        return ['success' => false, 'error' => $res->json('message') ?? 'Failed'];
    }

    public function syncOrderStatus(Order $order): bool
    {
        if (!$order->pathao_consignment_id)
            return false;

        $result = $this->getOrderStatus($order->pathao_consignment_id);
        if (!$result['success'])
            return false;

        $pathaoStatus = $result['data']['order_status'] ?? null;
        if (!$pathaoStatus || $pathaoStatus === $order->pathao_status)
            return false;

        $order->update(['pathao_status' => $pathaoStatus]);

        $statusMap = [
            'Delivered' => 'delivered',
            'Cancelled' => 'cancelled',
            'Picked_Up' => 'shipped',
            'Out_For_Delivery' => 'out_for_delivery',
            'In_Transit' => 'shipped',
            'Return_Picked_Up' => 'returned',
        ];

        if (isset($statusMap[$pathaoStatus])) {
            app(OrderService::class)->updateStatus(
                $order,
                $statusMap[$pathaoStatus],
                'Synced from Pathao',
                false
            );
        }

        return true;
    }
}