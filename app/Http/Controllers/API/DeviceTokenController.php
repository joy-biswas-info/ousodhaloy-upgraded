<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class DeviceTokenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'platform' => 'nullable|string|max:20',
        ]);

        // A token is unique per device, not per user — if the same device
        // previously logged in as a different manager, re-point it to the
        // current one rather than leaving a stale owner able to receive
        // pushes meant for whoever is signed in now.
        DeviceToken::updateOrCreate(
            ['token' => $request->token],
            ['user_id' => $request->user()->id, 'platform' => $request->platform ?? 'android']
        );

        return response()->json(['message' => 'Device registered']);
    }

    public function destroy(Request $request)
    {
        $request->validate(['token' => 'required|string']);

        DeviceToken::where('token', $request->token)
            ->where('user_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Device unregistered']);
    }
}
