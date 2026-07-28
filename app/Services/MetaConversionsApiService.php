<?php
namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\{Http, Log};

/**
 * Server-side half of the Meta Pixel + Conversions API hybrid setup — see
 * resources/views/partials/meta-pixel.blade.php for the browser-side half
 * and CapiTrackController for the generic-event beacon endpoint that calls
 * this service. Purchase is fired directly from OrderService/SslCommerzService
 * instead, since it has richer customer data to hash than an anonymous beacon.
 */
class MetaConversionsApiService
{
    private const API_VERSION = 'v21.0';

    private ?string $pixelId;
    private ?string $accessToken;
    private string $mode;
    private ?string $testEventCode;

    public function __construct()
    {
        $this->pixelId = Setting::get('meta_pixel_id');
        $this->accessToken = Setting::get('meta_access_token');
        $this->mode = Setting::get('meta_pixel_mode', 'test');
        $this->testEventCode = Setting::get('meta_pixel_test_event_code');
    }

    public function isConfigured(): bool
    {
        return !empty($this->pixelId) && !empty($this->accessToken);
    }

    /**
     * Send one event to Meta. $userData should already be normalized/hashed
     * via hashUserData() for PII fields — client_ip_address, client_user_agent,
     * fbp, and fbc are passed through raw (Meta requires them unhashed).
     */
    public function send(
        string $eventName,
        string $eventId,
        array $customData = [],
        array $userData = [],
        ?string $eventSourceUrl = null,
        string $actionSource = 'website'
    ): bool {
        if (!$this->isConfigured()) {
            return false;
        }

        $event = [
            'event_name' => $eventName,
            'event_time' => now()->timestamp,
            'event_id' => $eventId,
            'action_source' => $actionSource,
            'user_data' => $userData,
            'custom_data' => $customData,
        ];
        if ($eventSourceUrl) {
            $event['event_source_url'] = $eventSourceUrl;
        }

        $payload = ['data' => [$event]];
        if ($this->mode === 'test' && $this->testEventCode) {
            $payload['test_event_code'] = $this->testEventCode;
        }

        try {
            $res = Http::timeout(5)->post(
                "https://graph.facebook.com/" . self::API_VERSION . "/{$this->pixelId}/events",
                array_merge($payload, ['access_token' => $this->accessToken])
            );

            if (!$res->successful()) {
                Log::warning('Meta CAPI event failed', [
                    'event' => $eventName,
                    'event_id' => $eventId,
                    'status' => $res->status(),
                    'error' => $res->json('error.message'),
                ]);
            }

            return $res->successful();
        } catch (\Throwable $e) {
            Log::warning('Meta CAPI request threw', [
                'event' => $eventName,
                'event_id' => $eventId,
                'message' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Normalizes and SHA-256-hashes PII fields per Meta's Advanced Matching
     * spec. Pass raw values in; only non-empty fields are included in the
     * result, so callers can freely pass nulls for data they don't have.
     *
     * Accepted keys: email, phone, first_name, last_name, city, external_id.
     */
    public function hashUserData(array $raw): array
    {
        $out = [];

        if (!empty($raw['email'])) {
            $out['em'] = $this->hash(strtolower(trim($raw['email'])));
        }

        if (!empty($raw['phone'])) {
            $phone = preg_replace('/\D/', '', $raw['phone']);
            if (str_starts_with($phone, '0')) {
                $phone = '88' . $phone;
            } elseif (!str_starts_with($phone, '88')) {
                $phone = '88' . $phone;
            }
            $out['ph'] = $this->hash($phone);
        }

        if (!empty($raw['first_name'])) {
            $out['fn'] = $this->hash($this->normalizeName($raw['first_name']));
        }

        if (!empty($raw['last_name'])) {
            $out['ln'] = $this->hash($this->normalizeName($raw['last_name']));
        }

        if (!empty($raw['city'])) {
            $out['ct'] = $this->hash(strtolower(preg_replace('/\s+/', '', $raw['city'])));
        }

        if (!empty($raw['external_id'])) {
            $out['external_id'] = $this->hash((string) $raw['external_id']);
        }

        return $out;
    }

    private function normalizeName(string $name): string
    {
        return trim(preg_replace('/[^\p{L}\p{N}]/u', '', strtolower($name)));
    }

    private function hash(string $value): string
    {
        return hash('sha256', $value);
    }
}
