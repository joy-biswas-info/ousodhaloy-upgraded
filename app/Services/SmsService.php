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

        $apiKey = config('services.mimsms.api_key');
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
                'senderid' => config('services.mimsms.sender_id', 'Ousodhaloy'),
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

    public function orderConfirm(Order $order): bool
    {
        if (Setting::get('sms_order_confirm', 'true') !== 'true')
            return false;
        $msg = "Dear {$order->shipping_name}, Your Order #{$order->order_number} has been confirmed. Total TK {$order->total} Thank you -Ousodhaloy";
        return $this->send($order->shipping_phone, $msg, 'order_confirm', $order->id);
    }

    public function orderStatusUpdate(Order $order, string $status): bool
    {
        if (Setting::get('sms_status_update', 'true') !== 'true')
            return false;
        $msgs = [
            'confirmed' => "Your Order #{$order->order_number} has been confirmed. is comfirmed. Track https://ousodhaloy.com/track Thank you -Ousodhaloy",
            'shipped' => "Your Order #{$order->order_number} has been shipped",
            'out_for_delivery' => "Good news! Your Order #{$order->order_number} will delivered today",
            'delivered' => "Your Order #{$order->order_number} Has been successfully delivered. Thank you",
            'cancelled' => "Your Order #{$order->order_number} has been cancled",
        ];
        $msg = $msgs[$status] ?? "Order #{$order->order_number} Updated" . (Order::STATUS_LABELS[$status] ?? $status);
        return $this->send($order->shipping_phone, $msg, 'status_update', $order->id);
    }

    public function otp(string $phone, string $code): bool
    {
        $msg = "Your Ousodhaloy OTP: {$code}। " . config('app.otp_expiry', 5) . " Will expire";
        return $this->send($phone, $msg, 'otp');
    }

    public function lowStockAlert(string $adminPhone, string $productName, int $stock): bool
    {
        $msg = "⚠️ Low Stock: {$productName} - only {$stock} left. Login to restock.";
        return $this->send($adminPhone, $msg, 'low_stock');
    }
}