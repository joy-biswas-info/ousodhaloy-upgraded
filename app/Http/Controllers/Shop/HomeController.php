<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\{Product, Category, Brand, Banner, Setting};
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::active()->where('position', 'hero')->get();
        $categories = Category::active()->withCount(['products' => fn($q) => $q->active()])->get();
        $flashSale = Product::flashSale()->inStock()->with('brand', 'category')->take(10)->get();
        $featured = Product::featured()->inStock()->with('brand', 'category')->take(10)->get();
        $newArrivals = Product::active()->inStock()->latest()->with('brand', 'category')->take(10)->get();
        $topSelling = Product::active()->inStock()->orderByDesc('total_sold')->with('brand', 'category')->take(10)->get();
        $promoBanners = Banner::active()->where('position', 'promo')->take(3)->get();

        // Flash deal timer
        $flashDeal = \App\Models\FlashDeal::where('is_active', true)
            ->where('ends_at', '>', now())->latest()->first();

        return view('shop.home.index', compact(
            'banners',
            'categories',
            'flashSale',
            'featured',
            'newArrivals',
            'topSelling',
            'promoBanners',
            'flashDeal'
        ));
    }
}
