<?php

namespace App\Http\Controllers;

use App\Services\MetaConversionsApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Generic server-side beacon for the browser fbTrack() helper — see
 * resources/views/partials/meta-pixel.blade.php. Every non-Purchase pixel
 * event double-fires here with the same event_id used client-side, so Meta
 * dedups the pair. Only has anonymous request-level data (ip/UA/fbp/fbc);
 * Purchase is fired separately with full customer data — see
 * MetaConversionsApiService callers in OrderService/SslCommerzService.
 */
class CapiTrackController extends Controller
{
    public function track(Request $request, MetaConversionsApiService $capi)
    {
        $eventName = $request->input('event_name');
        $eventId = $request->input('event_id');

        if ($eventName && $eventId && $capi->isConfigured()) {
            $userData = [];
            if (Auth::check()) {
                $userData = $capi->hashUserData(['external_id' => Auth::id()]);
            }
            $userData['client_ip_address'] = $request->ip();
            $userData['client_user_agent'] = $request->userAgent();
            if ($fbp = $request->cookie('_fbp')) {
                $userData['fbp'] = $fbp;
            }
            if ($fbc = $request->cookie('_fbc')) {
                $userData['fbc'] = $fbc;
            }

            $capi->send(
                $eventName,
                $eventId,
                (array) $request->input('custom_data', []),
                $userData,
                $request->input('event_source_url')
            );
        }

        return response()->noContent();
    }
}
