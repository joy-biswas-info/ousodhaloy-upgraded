<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\{Artisan, Schedule};

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Sync Pathao order statuses every 30 minutes
Schedule::command('pathao:sync')->everyThirtyMinutes();
Schedule::command('steadfast:sync')->everyThirtyMinutes();
// Clean expired OTPs daily
Schedule::call(function () {
    \App\Models\Otp::where('expires_at', '<', now()->subDay())->delete();
})->daily()->name('clean-expired-otps');

// Send low stock alerts daily at 9am
Schedule::call(function () {
    $adminPhone = \App\Models\Setting::get('site_phone');
    if (!$adminPhone) return;
    $sms = app(\App\Services\SmsService::class);
    \App\Models\Product::active()
        ->where('stock', '<=', \DB::raw('low_stock_alert'))
        ->where('stock', '>', 0)
        ->each(fn($p) => $sms->lowStockAlert($adminPhone, $p->name, $p->stock));
})->dailyAt('09:00')->name('low-stock-alerts');
