<?php
namespace App\Services;

use App\Models\{Order, Setting};
use Illuminate\Support\Facades\{Http, Log};

class SslCommerzService
{
    private string $storeId;
    private string $storePasswd;
    private bool $isLive;
    private string $baseUrl;

    public function __construct()
    {
        $s = Setting::getGroup('payment');

        $this->storeId = $s['ssl_store_id'] ?? config('services.sslcommerz.store_id', '');
        $this->storePasswd = $s['ssl_store_pass'] ?? config('services.sslcommerz.store_passwd', '');
        $this->isLive = ($s['ssl_is_live'] ?? 'false') === 'true';
        $this->baseUrl = $this->isLive
            ? 'https://securepay.sslcommerz.com'
            : 'https://sandbox.sslcommerz.com';
    }

    public function isConfigured(): bool
    {
        return !empty($this->storeId) && !empty($this->storePasswd);
    }

    public function initiatePayment(Order $order): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'SSL Commerz credentials not configured. Go to Settings → Payments.'];
        }

        $appUrl = config('app.url');

        $data = [
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePasswd,
            'total_amount' => number_format($order->total, 2, '.', ''),
            'currency' => 'BDT',
            'tran_id' => $order->order_number,
            'success_url' => "{$appUrl}/payment/success",
            'fail_url' => "{$appUrl}/payment/fail",
            'cancel_url' => "{$appUrl}/payment/cancel",
            'ipn_url' => "{$appUrl}/payment/ipn",
            'cus_name' => $order->shipping_name,
            'cus_email' => $order->shipping_email ?? $order->guest_email ?? 'customer@ousodhaloy.com',
            'cus_add1' => $order->shipping_address,
            'cus_city' => $order->shipping_district,
            'cus_state' => $order->shipping_division,
            'cus_postcode' => $order->shipping_postcode ?? '1000',
            'cus_country' => 'Bangladesh',
            'cus_phone' => $order->shipping_phone,
            'ship_name' => $order->shipping_name,
            'ship_add1' => $order->shipping_address,
            'ship_city' => $order->shipping_district,
            'ship_state' => $order->shipping_division,
            'ship_postcode' => $order->shipping_postcode ?? '1000',
            'ship_country' => 'Bangladesh',
            'product_name' => "Order #{$order->order_number}",
            'product_category' => 'Medicine & Healthcare',
            'product_profile' => 'general',
            'value_a' => $order->id,
            'value_b' => $order->order_number,
        ];

        $res = Http::asForm()->post("{$this->baseUrl}/gwprocess/v4/api.php", $data);

        // Log full response so we can debug any SSL Commerz issues
        Log::channel('single')->info('SSLCommerz initiatePayment', [
            'order' => $order->order_number,
            'store_id' => $this->storeId,
            'is_live' => $this->isLive,
            'status' => $res->status(),
            'body' => $res->json() ?? $res->body(),
        ]);

        if ($res->json('status') === 'SUCCESS') {
            return [
                'success' => true,
                'gateway_url' => $res->json('GatewayPageURL'),
                'session_key' => $res->json('sessionkey'),
            ];
        }

        $error = $res->json('failedreason')
            ?? $res->json('desc')
            ?? $res->json('status')
            ?? ('HTTP ' . $res->status());

        return ['success' => false, 'error' => "SSL Commerz: {$error}"];
    }

    public function validate(string $valId): array
    {
        $res = Http::get("{$this->baseUrl}/validator/api/validationserverAPI.php", [
            'val_id' => $valId,
            'store_id' => $this->storeId,
            'store_passwd' => $this->storePasswd,
            'format' => 'json',
        ]);

        if ($res->json('status') === 'VALID') {
            return ['success' => true, 'data' => $res->json()];
        }
        return ['success' => false, 'error' => 'Validation failed'];
    }

    public function handleSuccess(array $payload): ?Order
    {
        $tranId = $payload['tran_id'] ?? null;
        $valId = $payload['val_id'] ?? null;

        if (!$tranId || !$valId)
            return null;
        if (($payload['status'] ?? '') !== 'VALID')
            return null;

        $validation = $this->validate($valId);
        if (!$validation['success'])
            return null;

        $order = Order::where('order_number', $tranId)->first();
        if (!$order || $order->payment_status === 'paid')
            return $order;

        $order->update([
            'payment_status' => 'paid',
            'ssl_transaction_id' => $payload['bank_tran_id'] ?? $tranId,
            'ssl_val_id' => $valId,
            'status' => 'confirmed',
        ]);

        app(OrderService::class)->updateStatus($order, 'confirmed', 'Payment confirmed via SSL Commerz');

        return $order;
    }

    public function handleIpn(array $payload): bool
    {
        if (($payload['status'] ?? '') !== 'VALID')
            return false;

        $order = Order::where('order_number', $payload['tran_id'] ?? '')->first();
        if (!$order || $order->payment_status === 'paid')
            return false;

        $validation = $this->validate($payload['val_id'] ?? '');
        if ($validation['success']) {
            $order->update(['payment_status' => 'paid', 'ssl_val_id' => $payload['val_id']]);
            if ($order->status === 'pending') {
                app(OrderService::class)->updateStatus($order, 'confirmed', 'IPN payment confirmed');
            }
            return true;
        }
        return false;
    }
}