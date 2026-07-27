<?php

namespace App\Http\Controllers;

use App\Models\{LandingPage, Product};
use Illuminate\Support\Facades\Cache;

class SitemapController extends Controller
{
    public function index()
    {
        $xml = Cache::remember('sitemap.xml', now()->addHours(6), function () {
            $urls = [
                ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
                ['loc' => route('shop.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
                ['loc' => route('legal.privacy'), 'priority' => '0.3', 'changefreq' => 'monthly'],
                ['loc' => route('legal.terms'), 'priority' => '0.3', 'changefreq' => 'monthly'],
                ['loc' => route('legal.returns'), 'priority' => '0.3', 'changefreq' => 'monthly'],
            ];

            Product::active()->select('slug', 'updated_at')->chunk(500, function ($products) use (&$urls) {
                foreach ($products as $product) {
                    $urls[] = [
                        'loc' => route('shop.product', $product->slug),
                        'lastmod' => $product->updated_at->toAtomString(),
                        'priority' => '0.8',
                        'changefreq' => 'weekly',
                    ];
                }
            });

            // Landing pages use bare top-level slugs (the catch-all route), not a named route helper
            LandingPage::published()->select('slug', 'updated_at')->chunk(500, function ($pages) use (&$urls) {
                foreach ($pages as $page) {
                    $urls[] = [
                        'loc' => url($page->slug),
                        'lastmod' => $page->updated_at->toAtomString(),
                        'priority' => '0.9',
                        'changefreq' => 'weekly',
                    ];
                }
            });

            return view('sitemap', compact('urls'))->render();
        });

        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /account',
            'Disallow: /cart',
            'Disallow: /checkout',
            'Disallow: /auth',
            'Disallow: /order/',
            'Disallow: /track',
            'Allow: /',
            '',
            'Sitemap: ' . route('sitemap'),
        ];

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
