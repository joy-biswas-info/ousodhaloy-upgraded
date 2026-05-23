<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Setting, DeliveryZone, PromoCode, Banner};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    // Boolean keys — must be explicitly saved as 'false' when unchecked
    private const BOOLEAN_KEYS = [
        'maintenance_mode',
        'guest_checkout',
        'cod_enabled',
        'ssl_enabled',
        'bkash_enabled',
        'nagad_enabled',
        'rocket_enabled',
        'bank_enabled',
        'ssl_is_live',
        'sms_order_confirm',
        'sms_status_update',
        'pathao_is_live',
        'steadfast_enabled',
        'loyalty_enabled',
        'meta_pixel_page_view',
        'meta_pixel_view_content',
        'meta_pixel_search',
        'meta_pixel_add_to_cart',
        'meta_pixel_initiate_checkout',
        'meta_pixel_purchase',
    ];

    // All keys per group — used to know which keys to save
    private const GROUPS = [
        'general' => [
            'site_name',
            'site_tagline',
            'site_phone',
            'site_email',
            'site_address',
            'maintenance_mode',
            'brand_primary',
            'brand_dark',
            'brand_light',
            'brand_bg',
            'messenger_url'
        ],
        'orders' => ['guest_checkout', 'free_delivery_min', 'delivery_charge', 'min_order_amount'],
        'payment' => [
            'cod_enabled',
            'ssl_enabled',
            'bkash_enabled',
            'nagad_enabled',
            'rocket_enabled',
            'bank_enabled',
            'ssl_store_id',
            'ssl_store_pass',
            'ssl_is_live',
        ],
        'sms' => ['sms_order_confirm', 'sms_status_update', 'mimsms_api_key', 'mimsms_sender_id'],
        'pathao' => ['pathao_client_id', 'pathao_client_secret', 'pathao_username', 'pathao_password', 'pathao_store_id', 'pathao_is_live', 'pathao_default_city_id', 'pathao_default_zone_id', 'pathao_default_area_id'],
        'steadfast' => ['steadfast_api_key', 'steadfast_secret_key', 'steadfast_enabled'],
        'loyalty' => ['loyalty_enabled', 'loyalty_points_per_taka', 'loyalty_points_per_order'],
        'checkout' => ['checkout_fields'],
        'pixel' => [
            'meta_pixel_id',
            'meta_pixel_test_event_code',
            'meta_pixel_mode',
            'meta_access_token',
            'meta_pixel_page_view',
            'meta_pixel_view_content',
            'meta_pixel_search',
            'meta_pixel_add_to_cart',
            'meta_pixel_initiate_checkout',
            'meta_pixel_purchase',
        ],
    ];

    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $deliveryZones = DeliveryZone::all();
        return view('admin.settings.index', compact('settings', 'deliveryZones'));
    }

    public function update(Request $request)
    {
        $group = $request->input('group', 'general');
        $keys = self::GROUPS[$group] ?? [];

        foreach ($keys as $key) {
            if (in_array($key, self::BOOLEAN_KEYS)) {
                // Checkbox: save 'true' if checked, 'false' if unchecked (not submitted)
                Setting::set($key, $request->has($key) ? 'true' : 'false', $group);
            } elseif ($request->has($key)) {
                Setting::set($key, $request->input($key), $group);
            }
        }

        // Handle checkout_fields JSON specially (built by JS before submit)
        if ($group === 'checkout' && $request->has('checkout_fields')) {
            Setting::set('checkout_fields', $request->input('checkout_fields'), 'checkout');
        }

        // Logo upload
        if ($request->hasFile('site_logo')) {
            $path = $request->file('site_logo')->store('settings', 'public');
            Setting::set('site_logo', $path, 'general');
        }

        return back()->with('success', ucfirst($group) . ' settings saved!');
    }

    // ── Meta Pixel ────────────────────────────────────────────────────────
    public function savePixel(Request $request)
    {
        $keys = self::GROUPS['pixel'];
        foreach ($keys as $key) {
            if (in_array($key, self::BOOLEAN_KEYS)) {
                Setting::set($key, $request->has($key) ? 'true' : 'false', 'pixel');
            } elseif ($request->has($key)) {
                Setting::set($key, $request->input($key), 'pixel');
            }
        }
        return back()->with('success', 'Meta Pixel settings saved!');
    }

    // ── Customization ─────────────────────────────────────────────────────

    public function customization()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $banners = \App\Models\Banner::orderBy('position')->orderBy('sort_order')->paginate(20);
        return view('admin.customization.index', compact('settings', 'banners'));
    }

    public function saveCustomization(\Illuminate\Http\Request $request)
    {
        if ($request->filled('logo_media_path')) {
            Setting::set('site_logo', $request->logo_media_path, 'general');
        } elseif ($request->hasFile('site_logo')) {
            $file = $request->file('site_logo');
            $seoName = \App\Models\Media::seoFilename($file->getClientOriginalName(), 'settings');
            $path = $file->storeAs('settings', $seoName, 'public');
            \App\Models\Media::create([
                'filename' => $seoName,
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'alt_text' => 'Site Logo',
                'folder' => 'settings',
                'uploaded_by' => auth()->id(),
            ]);
            Setting::set('site_logo', $path, 'general');
        }
        foreach (['brand_primary', 'brand_dark', 'brand_light', 'brand_bg'] as $k) {
            if ($request->filled($k))
                Setting::set($k, $request->input($k), 'general');
        }
        foreach (['site_name', 'site_tagline', 'site_phone', 'site_email', 'site_address', 'messenger_url', 'hero_banner_height', 'promo_banner_height'] as $k) {
            if ($request->has($k))
                Setting::set($k, $request->input($k), 'general');
        }
        return back()->with('success', 'Customization saved!');
    }

    // ── Promo Codes ───────────────────────────────────────────────────────

    public function promos()
    {
        $promos = PromoCode::latest()->paginate(15);
        return view('admin.settings.promos', compact('promos'));
    }

    public function storePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promo_codes,code',
            'type' => 'required|in:percent,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date|after:today',
        ]);

        PromoCode::create([
            ...$request->only(['type', 'value', 'min_order', 'max_discount', 'usage_limit', 'per_user_limit', 'title']),
            'code' => strtoupper($request->code),
            'min_order' => $request->min_order ?? 0,
            'first_order_only' => $request->boolean('first_order_only'),
            'is_active' => $request->boolean('is_active', true),
            'expires_at' => $request->expires_at,
        ]);

        return back()->with('success', 'Promo code created!');
    }

    public function togglePromo(PromoCode $promo)
    {
        $promo->update(['is_active' => !$promo->is_active]);
        return back()->with('success', 'Promo code ' . ($promo->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function destroyPromo(PromoCode $promo)
    {
        $promo->delete();
        return back()->with('success', 'Promo code deleted.');
    }

    // ── Banners ───────────────────────────────────────────────────────────

    public function banners()
    {
        $banners = Banner::orderBy('position')->orderBy('sort_order')->paginate(20);
        return view('admin.settings.banners', compact('banners'));
    }

    public function storeBanner(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:5120',
            'position' => 'required|in:hero,promo,sidebar,popup',
        ]);

        $path = $request->file('image')->store('banners', 'public');
        Banner::create([
            ...$request->only(['title', 'subtitle', 'link_url', 'button_text', 'badge_text', 'bg_color', 'position', 'sort_order']),
            'image' => $path,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Banner added!');
    }

    public function destroyBanner(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image);
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }

    // ── Delivery Zones ────────────────────────────────────────────────────

    public function storeDeliveryZone(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'division' => 'required|string',
            'delivery_charge' => 'required|numeric',
        ]);

        DeliveryZone::create([
            ...$request->only(['name', 'division', 'delivery_charge', 'free_delivery_above', 'estimated_days']),
            'districts' => $request->districts ? explode(',', $request->districts) : null,
            'express_available' => $request->boolean('express_available'),
            'express_charge' => $request->express_charge ?: null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Delivery zone added!');
    }

    public function updateDeliveryZone(Request $request, DeliveryZone $zone)
    {
        $request->validate([
            'name' => 'required|string',
            'delivery_charge' => 'required|numeric',
        ]);

        $zone->update([
            ...$request->only(['name', 'division', 'delivery_charge', 'free_delivery_above', 'estimated_days']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Delivery zone updated!');
    }

    public function destroyDeliveryZone(DeliveryZone $zone)
    {
        $zone->delete();
        return back()->with('success', 'Delivery zone deleted.');
    }
}