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
        $msg = "প্রিয় {$order->shipping_name}, আপনার অর্ডার #{$order->order_number} নিশ্চিত হয়েছে। মোট: ৳{$order->total}। ধন্যবাদ - Ousodhaloy";
        return $this->send($order->shipping_phone, $msg, 'order_confirm', $order->id);
    }

    public function orderStatusUpdate(Order $order, string $status): bool
    {
        if (Setting::get('sms_status_update', 'true') !== 'true')
            return false;
        $msgs = [
            'confirmed' => "আপনার অর্ডার #{$order->order_number} কনফার্ম হয়েছে। শীঘ্রই প্রেরণ করা হবে।",
            'shipped' => "আপনার অর্ডার #{$order->order_number} পাঠানো হয়েছে। ট্র্যাকিং: ousodhaloy.com/track",
            'out_for_delivery' => "সুখবর! আপনার অর্ডার #{$order->order_number} আজ ডেলিভারি হবে।",
            'delivered' => "আপনার অর্ডার #{$order->order_number} সফলভাবে ডেলিভারি হয়েছে। ধন্যবাদ!",
            'cancelled' => "আপনার অর্ডার #{$order->order_number} বাতিল করা হয়েছে।",
        ];
        $msg = $msgs[$status] ?? "অর্ডার #{$order->order_number} আপডেট: " . (Order::STATUS_LABELS[$status] ?? $status);
        return $this->send($order->shipping_phone, $msg, 'status_update', $order->id);
    }

    public function otp(string $phone, string $code): bool
    {
        $msg = "আপনার Ousodhaloy OTP: {$code}। " . config('app.otp_expiry', 5) . " মিনিটের মধ্যে মেয়াদ শেষ হবে।";
        return $this->send($phone, $msg, 'otp');
    }

    public function lowStockAlert(string $adminPhone, string $productName, int $stock): bool
    {
        $msg = "⚠️ Low Stock: {$productName} - only {$stock} left. Login to restock.";
        return $this->send($adminPhone, $msg, 'low_stock');
    }
}