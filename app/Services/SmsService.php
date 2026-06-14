<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\{SmsLog, Order, Setting};

class SmsService
{
    public function send(string $phone, string $message, string $purpose = 'general', ?int $orderId = null): bool
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (str_starts_with($phone, '0'))
            $phone = '88' . $phone;
        elseif (!str_starts_with($phone, '88'))
            $phone = '88' . $phone;

        $log = SmsLog::create([
            'phone' => $phone,
            'message' => $message,
            'status' => 'queued',
            'purpose' => $purpose,
            'order_id' => $orderId,
        ]);

        // ✅ Read from database (admin settings) instead of .env
        $apiKey = Setting::get('mimsms_api_key');
        $senderId = Setting::get('mimsms_sender_id', 'Ousodhaloy');

        if (!$apiKey) {
            logger()->info("SMS (no key): [{$phone}] {$message}");
            $log->update(['status' => 'sent', 'reference' => 'local']);
            return true;
        }

        try {
            $res = Http::get('https://app.mimsms.com/smsapi', [
                'api_key' => $apiKey,
                'type' => 'text',
                'contacts' => $phone,
                'senderid' => $senderId,
                'msg' => $message,
            ]);
            $success = $res->successful();
            $log->update([
                'status' => $success ? 'sent' : 'failed',
                'response' => $res->json() ?? ['raw' => $res->body()],
                'reference' => $res->json('request_id') ?? null,
            ]);
            return $success;
        } catch (\Exception $e) {
            $log->update(['status' => 'failed', 'response' => ['error' => $e->getMessage()]]);
            return false;
        }
    }

    // Fires once when customer places the order — "we received it"
    public function orderPlaced(Order $order): bool
    {
        if (Setting::get('sms_order_confirm', 'true') !== 'true')
            return false;

        $msg = "Dear {$order->shipping_name}, we received your order #{$order->order_number} (TK {$order->total}). We'll confirm it shortly. -Ousodhaloy";
        return $this->send($order->shipping_phone, $msg, 'order_placed', $order->id);
    }

    // Fires when admin changes status — "confirmed/shipped/delivered etc"
    public function orderStatusUpdate(Order $order, string $status): bool
    {
        if (Setting::get('sms_status_update', 'true') !== 'true')
            return false;

        $msgs = [
            'confirmed' => "Your Order #{$order->order_number} has been confirmed and is being processed. -Ousodhaloy",
            'shipped' => "Your Order #{$order->order_number} has been shipped and is on its way. -Ousodhaloy",
            'out_for_delivery' => "Good news! Your Order #{$order->order_number} will be delivered today. -Ousodhaloy",
            'delivered' => "Your Order #{$order->order_number} has been delivered. Thank you for shopping with us! -Ousodhaloy",
            'cancelled' => "Your Order #{$order->order_number} has been cancelled. Contact us if you need help. -Ousodhaloy",
        ];

        $msg = $msgs[$status] ?? "Your Order #{$order->order_number} has been updated to: " . (Order::STATUS_LABELS[$status] ?? $status) . ". -Ousodhaloy";
        return $this->send($order->shipping_phone, $msg, 'status_update', $order->id);
    }

    public function otp(string $phone, string $code): bool
    {
        $expiry = config('app.otp_expiry', 5);
        $msg = "Your Ousodhaloy OTP is {$code}. It will expire in {$expiry} minutes. -Ousodhaloy";
        return $this->send($phone, $msg, 'otp');
    }

    public function lowStockAlert(string $adminPhone, string $productName, int $stock): bool
    {
        $msg = "Low Stock: {$productName} - only {$stock} left. Login to restock. -Ousodhaloy";
        return $this->send($adminPhone, $msg, 'low_stock');
    }
}