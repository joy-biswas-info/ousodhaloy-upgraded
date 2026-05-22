<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{Hash, DB};
use App\Models\{User, Category, Brand, Product, Setting, DeliveryZone, Banner, PromoCode};
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // ── Admin user ────────────────────────────────────────────────────
        User::updateOrCreate(
            ['email' => 'admin@ousodhaloy.com'],
            [
                'name'     => 'Admin User',
                'phone'    => '01700000000',
                'password' => Hash::make('admin123'),
                'role'     => 'admin',
                'is_active'=> true,
                'referral_code' => 'ADMIN001',
            ]
        );

        // ── Site settings ─────────────────────────────────────────────────
        $settings = [
            ['key' => 'site_name',         'value' => 'Ousodhaloy',                  'group' => 'general'],
            ['key' => 'site_tagline',       'value' => 'বাংলাদেশের বিশ্বস্ত অনলাইন ফার্মেসি','group' => 'general'],
            ['key' => 'site_phone',         'value' => '09610016778',                 'group' => 'general'],
            ['key' => 'site_email',         'value' => 'info@ousodhaloy.com',          'group' => 'general'],
            ['key' => 'site_address',       'value' => 'House 12, Road 5, Banani, Dhaka-1213', 'group' => 'general'],
            ['key' => 'maintenance_mode',   'value' => 'false',                       'group' => 'general'],
            ['key' => 'guest_checkout',     'value' => 'true',                        'group' => 'orders'],
            ['key' => 'cod_enabled',        'value' => 'true',                        'group' => 'orders'],
            ['key' => 'ssl_enabled',        'value' => 'true',                        'group' => 'orders'],
            ['key' => 'delivery_charge',    'value' => '60',                          'group' => 'orders'],
            ['key' => 'free_delivery_min',  'value' => '500',                         'group' => 'orders'],
            ['key' => 'sms_order_confirm',  'value' => 'true',                        'group' => 'sms'],
            ['key' => 'sms_status_update',  'value' => 'true',                        'group' => 'sms'],
            ['key' => 'loyalty_enabled',    'value' => 'true',                        'group' => 'loyalty'],
        ];
        foreach ($settings as $s) {
            Setting::updateOrCreate(['key' => $s['key']], ['value' => $s['value'], 'group' => $s['group']]);
        }

        // ── Categories ────────────────────────────────────────────────────
        $cats = config('bd.default_categories', []);
        foreach ($cats as $i => $c) {
            Category::updateOrCreate(['slug' => $c['slug']], [
                'name'       => $c['name'],
                'icon'       => $c['icon'],
                'is_active'  => true,
                'sort_order' => $i,
            ]);
        }

        // ── Brands ────────────────────────────────────────────────────────
        $brands = [
            ['name' => 'Square Pharmaceuticals',   'country' => 'Bangladesh'],
            ['name' => 'Beximco Pharma',            'country' => 'Bangladesh'],
            ['name' => 'Renata Limited',            'country' => 'Bangladesh'],
            ['name' => 'ACI Limited',               'country' => 'Bangladesh'],
            ['name' => 'Incepta Pharma',            'country' => 'Bangladesh'],
            ['name' => 'Drug International',        'country' => 'Bangladesh'],
            ['name' => 'Aristopharma',              'country' => 'Bangladesh'],
            ['name' => 'Eskayef',                   'country' => 'Bangladesh'],
            ['name' => 'Opsonin Pharma',            'country' => 'Bangladesh'],
            ['name' => 'Globe Pharma',              'country' => 'Bangladesh'],
            ['name' => 'Healthcare Pharma',         'country' => 'Bangladesh'],
            ['name' => 'General Pharma',            'country' => 'Bangladesh'],
        ];
        foreach ($brands as $b) {
            Brand::updateOrCreate(
                ['slug' => Str::slug($b['name'])],
                ['name' => $b['name'], 'country' => $b['country'], 'is_active' => true]
            );
        }

        // ── Sample products ───────────────────────────────────────────────
        $medCatId  = Category::where('slug', 'medicine')->value('id');
        $vitCatId  = Category::where('slug', 'vitamins')->value('id');
        $skinCatId = Category::where('slug', 'skin-care')->value('id');
        $squareId  = Brand::where('slug', 'square-pharmaceuticals')->value('id');
        $beximcoId = Brand::where('slug', 'beximco-pharma')->value('id');
        $aciId     = Brand::where('slug', 'aci-limited')->value('id');
        $inceptaId = Brand::where('slug', 'incepta-pharma')->value('id');

        $products = [
            ['name' => 'Napa 500mg',         'generic_name' => 'Paracetamol',    'price' => 0.90,  'mrp' => 1.00,  'stock' => 5000, 'brand_id' => $beximcoId, 'category_id' => $medCatId,  'strength' => '500mg', 'form' => 'Tablet', 'pack_size' => '10 tablets/strip', 'unit' => 'strip', 'is_featured' => true],
            ['name' => 'Napa Extra',         'generic_name' => 'Paracetamol+Caffeine', 'price' => 1.20, 'mrp' => 1.50, 'stock' => 3000, 'brand_id' => $beximcoId, 'category_id' => $medCatId, 'strength' => '500mg+65mg', 'form' => 'Tablet', 'pack_size' => '10 tablets/strip', 'unit' => 'strip'],
            ['name' => 'Seclo 20mg',         'generic_name' => 'Omeprazole',     'price' => 4.00,  'mrp' => 5.00,  'stock' => 2000, 'brand_id' => $squareId,  'category_id' => $medCatId,  'strength' => '20mg', 'form' => 'Capsule', 'pack_size' => '14 caps/box', 'unit' => 'box', 'is_featured' => true],
            ['name' => 'Amodis 400mg',       'generic_name' => 'Metronidazole',  'price' => 1.50,  'mrp' => 2.00,  'stock' => 3000, 'brand_id' => $squareId,  'category_id' => $medCatId,  'strength' => '400mg', 'form' => 'Tablet', 'pack_size' => '10 tablets/strip', 'unit' => 'strip'],
            ['name' => 'Ciproflox 500mg',    'generic_name' => 'Ciprofloxacin',  'price' => 5.50,  'mrp' => 7.00,  'stock' => 1500, 'brand_id' => $beximcoId, 'category_id' => $medCatId,  'strength' => '500mg', 'form' => 'Tablet', 'pack_size' => '10 tablets/strip', 'unit' => 'strip', 'requires_prescription' => true],
            ['name' => 'Losectil 20mg',      'generic_name' => 'Omeprazole',     'price' => 3.50,  'mrp' => 4.50,  'stock' => 2500, 'brand_id' => $aciId,     'category_id' => $medCatId,  'strength' => '20mg', 'form' => 'Capsule', 'unit' => 'strip'],
            ['name' => 'Amoxil 500mg',       'generic_name' => 'Amoxicillin',    'price' => 8.00,  'mrp' => 10.00, 'stock' => 800,  'brand_id' => $beximcoId, 'category_id' => $medCatId,  'strength' => '500mg', 'form' => 'Capsule', 'requires_prescription' => true, 'unit' => 'strip'],
            ['name' => 'Vitamin C 500mg',    'generic_name' => 'Ascorbic Acid',  'price' => 2.50,  'mrp' => 3.00,  'stock' => 4000, 'brand_id' => $squareId,  'category_id' => $vitCatId,  'strength' => '500mg', 'form' => 'Tablet', 'pack_size' => '30 tablets/bottle', 'unit' => 'bottle', 'is_featured' => true],
            ['name' => 'Zinc-B 20mg',        'generic_name' => 'Zinc Sulphate',  'price' => 1.20,  'mrp' => 1.50,  'stock' => 3500, 'brand_id' => $aciId,     'category_id' => $vitCatId,  'strength' => '20mg', 'form' => 'Tablet', 'unit' => 'strip'],
            ['name' => 'Omacor 1000mg',      'generic_name' => 'Omega-3',        'price' => 25.00, 'mrp' => 30.00, 'stock' => 500,  'brand_id' => $squareId,  'category_id' => $vitCatId,  'strength' => '1000mg', 'form' => 'Capsule', 'unit' => 'box', 'is_featured' => true, 'is_flash_sale' => true, 'flash_sale_price' => 20.00, 'flash_sale_ends_at' => now()->addDays(3)],
            ['name' => 'Neutrogena Moisturizer','generic_name' => 'Glycerin',    'price' => 450.00,'mrp' => 550.00,'stock' => 200,  'brand_id' => $inceptaId, 'category_id' => $skinCatId, 'form' => 'Cream', 'pack_size' => '250ml', 'unit' => 'pcs'],
            ['name' => 'Sunscreen SPF50+',   'generic_name' => 'Titanium Dioxide','price' => 380.00,'mrp' => 450.00,'stock' => 150, 'brand_id' => $aciId,     'category_id' => $skinCatId, 'form' => 'Lotion', 'unit' => 'pcs', 'is_featured' => true],
        ];

        foreach ($products as $p) {
            $slug = Str::slug($p['name']);
            $tabs = [
                ['id' => 'desc',  'label' => 'Description',   'content' => "<p><strong>{$p['name']}</strong> is a trusted {$p['form']} containing {$p['generic_name']}. Manufactured by quality BD pharma companies to international GMP standards.</p>"],
                ['id' => 'usage', 'label' => 'Usage & Dosage','content' => '<p>Take as directed by your physician. Do not self-medicate.</p><p>Store below 30°C, away from direct sunlight and moisture. Keep out of reach of children.</p>'],
                ['id' => 'info',  'label' => 'Product Info',  'content' => "<table><thead><tr><th>Property</th><th>Details</th></tr></thead><tbody><tr><td>Generic</td><td>{$p['generic_name']}</td></tr><tr><td>Form</td><td>{$p['form']}</td></tr>" . (isset($p['strength']) ? "<tr><td>Strength</td><td>{$p['strength']}</td></tr>" : '') . "</tbody></table>"],
            ];

            Product::updateOrCreate(['slug' => $slug], array_merge($p, [
                'slug'             => $slug,
                'is_active'        => true,
                'low_stock_alert'  => 20,
                'min_order_qty'    => 1,
                'max_order_qty'    => 100,
                'tabs'             => $tabs,
                'discount_percent' => isset($p['mrp']) ? round((($p['mrp'] - $p['price']) / $p['mrp']) * 100, 2) : 0,
            ]));
        }

        // ── Delivery zones ────────────────────────────────────────────────
        DeliveryZone::upsert([
            ['name' => 'Dhaka City',      'division' => 'Dhaka',      'districts' => json_encode(['Dhaka']),                    'delivery_charge' => 60,  'free_delivery_above' => 500,  'estimated_days' => 1, 'express_available' => true,  'express_charge' => 99,  'is_active' => true],
            ['name' => 'Dhaka Division',  'division' => 'Dhaka',      'districts' => json_encode(['Gazipur','Narayanganj','Narsingdi']), 'delivery_charge' => 80,  'free_delivery_above' => 700,  'estimated_days' => 2, 'express_available' => false, 'express_charge' => null, 'is_active' => true],
            ['name' => 'Chittagong City', 'division' => 'Chittagong', 'districts' => json_encode(['Chittagong']),               'delivery_charge' => 100, 'free_delivery_above' => 800,  'estimated_days' => 2, 'express_available' => false, 'express_charge' => null, 'is_active' => true],
            ['name' => 'Rest of BD',      'division' => 'Rajshahi',   'districts' => json_encode([]),                          'delivery_charge' => 120, 'free_delivery_above' => 1000, 'estimated_days' => 3, 'express_available' => false, 'express_charge' => null, 'is_active' => true],
        ], ['name'], ['delivery_charge','free_delivery_above','estimated_days','express_available','express_charge']);

        // ── Promo codes ───────────────────────────────────────────────────
        PromoCode::updateOrCreate(['code' => 'WELCOME20'], [
            'title'         => 'Welcome discount',
            'type'          => 'percent',
            'value'         => 20,
            'min_order'     => 200,
            'max_discount'  => 100,
            'per_user_limit'=> 1,
            'first_order_only' => true,
            'is_active'     => true,
        ]);
        PromoCode::updateOrCreate(['code' => 'SAVE50'], [
            'title'     => 'Save ৳50',
            'type'      => 'fixed',
            'value'     => 50,
            'min_order' => 300,
            'is_active' => true,
        ]);

        // ── Hero banners ──────────────────────────────────────────────────
        if (Banner::count() === 0) {
            Banner::create([
                'title'       => 'আসল ওষুধ, দ্রুত ডেলিভারি',
                'subtitle'    => 'Free delivery on orders above ৳500',
                'image'       => 'banners/hero-1.jpg',
                'link_url'    => '/shop',
                'button_text' => 'Shop Now',
                'badge_text'  => 'Trusted Pharmacy',
                'bg_color'    => '#0e7673',
                'position'    => 'hero',
                'sort_order'  => 1,
                'is_active'   => true,
            ]);
            Banner::create([
                'title'       => 'Flash Sale — Up to 30% Off',
                'subtitle'    => 'On vitamins & supplements',
                'image'       => 'banners/hero-2.jpg',
                'link_url'    => '/shop?flash_sale=1',
                'button_text' => 'Grab Deal',
                'badge_text'  => '⚡ Flash Sale',
                'bg_color'    => '#7c3aed',
                'position'    => 'hero',
                'sort_order'  => 2,
                'is_active'   => true,
            ]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('✅ Ousodhaloy database seeded successfully!');
        $this->command->info('   Admin login: admin@ousodhaloy.com / admin123');
    }
}
