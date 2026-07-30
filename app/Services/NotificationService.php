<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\Order;
use App\Models\User;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\AndroidConfig;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\MessageData;

class NotificationService
{
    /**
     * Alerts every admin/manager device when a new order lands. Deliberately
     * data-only (no top-level "notification" key) — see the Flutter app's
     * fcm_service.dart for why: a notification-payload message is
     * auto-displayed by the OS default channel when the app is backgrounded/
     * killed but silently skipped by app code when foregrounded, which
     * breaks "must alert with sound even while the app is open." Data-only
     * forces every app state through the same local-notification code path.
     */
    public function newOrderPush(Order $order): void
    {
        $tokens = DeviceToken::whereIn(
            'user_id',
            User::whereIn('role', ['admin', 'manager'])->pluck('id')
        )->pluck('token')->all();

        if (empty($tokens)) {
            return;
        }

        try {
            // Building the message itself was previously OUTSIDE this try —
            // if the Kreait Firebase classes weren't loadable for any reason
            // (e.g. the package missing/incomplete in a deployed vendor/,
            // which is exactly what happened in production once), that
            // construction threw uncaught and took down the entire checkout
            // request with it. The comment below already documented the
            // intent ("must never break order creation") — moving the
            // construction in here is what actually delivers on it.
            //
            // Messaging itself is also still resolved lazily, inside the try
            // — the Firebase SDK validates the service-account credentials
            // the moment it's built, not just when a message is actually
            // sent, so a missing/misconfigured credentials file throws at
            // resolution time too.
            $message = CloudMessage::new()
                ->withData(MessageData::fromArray([
                    'type' => 'new_order',
                    'order_id' => (string) $order->id,
                    'order_number' => $order->order_number,
                    'title' => "New Order #{$order->order_number}",
                    'body' => "৳" . number_format((float) $order->total, 0) . ' · ' . ($order->shipping_district ?? ''),
                ]))
                ->withAndroidConfig(AndroidConfig::fromArray([])->withHighMessagePriority());

            $messaging = app(Messaging::class);
            $report = $messaging->sendMulticast($message, $tokens);

            // Prune tokens FCM says are dead (uninstalled app, revoked
            // token) so future pushes don't keep paying the round-trip cost
            // of sending to a device that will never receive it.
            $dead = array_merge($report->invalidTokens(), $report->unknownTokens());
            if (!empty($dead)) {
                DeviceToken::whereIn('token', $dead)->delete();
            }
        } catch (\Throwable $e) {
            // A push failure should never block order creation — log and move on.
            logger()->error('FCM new-order push failed: ' . $e->getMessage());
        }
    }
}
