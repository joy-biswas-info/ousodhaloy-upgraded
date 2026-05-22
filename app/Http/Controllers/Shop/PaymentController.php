<?php
// ─────────────────────────────────────────────────────────────────────────────

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\SslCommerzService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function __construct(private SslCommerzService $ssl)
    {
    }

    public function success(Request $request)
    {
        $order = $this->ssl->handleSuccess($request->all());
        if ($order) {
            return redirect()->route('orders.show', $order->id)
                ->with('success', '✅ Payment successful! Your order is confirmed.');
        }
        return redirect()->route('checkout.index')->with('error', 'Payment verification failed.');
    }

    public function fail(Request $request)
    {
        $orderNum = $request->tran_id;
        if ($orderNum) {
            $order = \App\Models\Order::where('order_number', $orderNum)->first();
            if ($order) {
                $order->update(['payment_status' => 'failed']);
                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Payment failed. Please try again or use Cash on Delivery.');
            }
        }
        return redirect()->route('checkout.index')->with('error', 'Payment was not completed.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('checkout.index')->with('info', 'Payment cancelled. You can try again.');
    }

    public function ipn(Request $request)
    {
        $this->ssl->handleIpn($request->all());
        return response('OK', 200);
    }
}