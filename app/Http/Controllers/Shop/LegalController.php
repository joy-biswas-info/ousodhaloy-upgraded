<?php
namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;

class LegalController extends Controller
{
    public function privacy()
    {
        return view('shop.legal.privacy');
    }
    public function terms()
    {
        return view('shop.legal.terms');
    }
    public function returns()
    {
        return view('shop.legal.returns');
    }
}