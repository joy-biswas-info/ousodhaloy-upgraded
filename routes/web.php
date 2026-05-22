<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\AuthController,
    Shop\HomeController,
    Shop\ProductController,
    Shop\CartController,
    Shop\CheckoutController,
    Shop\OrderController,
    Shop\PaymentController,
    Admin\DashboardController,
    Admin\OrderController as AdminOrderController,
    Admin\ProductController as AdminProductController,
    Admin\SettingsController,
    Admin\CategoryController,
    Admin\BrandController,
    Admin\UserController,
    Admin\PrescriptionController,
    Admin\ReviewController,
    Admin\ManualOrderController,
    Admin\BulkProductController,
    Admin\MediaController,
};


// ── Public shop routes ─────────────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product');
});

Route::get('/search', [ProductController::class, 'search'])->name('search');

// ── Cart ───────────────────────────────────────────────────────────────────

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{id}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::post('/validate-promo', [CartController::class, 'validatePromo'])->name('validate-promo');
});

// ── Checkout ───────────────────────────────────────────────────────────────

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'index'])->name('index');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::get('/failed', fn() => view('shop.checkout.failed'))->name('failed');
});

// ── Delivery charge calculator (AJAX) ─────────────────────────────────────
Route::post('/checkout/delivery-charge', [CheckoutController::class, 'deliveryCharge'])->name('checkout.delivery-charge');

// ── Orders (public) ────────────────────────────────────────────────────────

Route::get('/order/{id}', [OrderController::class, 'show'])->name('orders.show');
Route::get('/track', [OrderController::class, 'track'])->name('track');
Route::post('/track', [OrderController::class, 'track'])->name('track.search');

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/orders', [OrderController::class, 'myOrders'])->name('orders');
    Route::get('/profile', fn() => view('shop.account.profile'))->name('profile');
    Route::get('/wishlist', fn() => view('shop.account.wishlist'))->name('wishlist');
    Route::get('/addresses', fn() => view('shop.account.addresses'))->name('addresses');
});

// ── Payment callbacks ──────────────────────────────────────────────────────

Route::prefix('payment')->name('payment.')->group(function () {
    Route::post('/success', [PaymentController::class, 'success'])->name('success');
    Route::post('/fail', [PaymentController::class, 'fail'])->name('fail');
    Route::post('/cancel', [PaymentController::class, 'cancel'])->name('cancel');
    Route::post('/ipn', [PaymentController::class, 'ipn'])->name('ipn');
});

// ── Auth ───────────────────────────────────────────────────────────────────

Route::middleware('guest')->prefix('auth')->name('auth.')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
    Route::get('/otp', [AuthController::class, 'otpForm'])->name('otp');
    Route::post('/otp/send', [AuthController::class, 'sendOtp'])->name('otp.send');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});
Route::post('/auth/logout', [AuthController::class, 'logout'])->name('auth.logout');

// ── Admin ──────────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->middleware(['auth', 'manager'])->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    // ── Media Library ────────────────────────────────────────────────────────────
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::get('/media/search', [MediaController::class, 'search'])->name('media.search');
    Route::patch('/media/{medium}', [MediaController::class, 'update'])->name('media.update');
    Route::delete('/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');

    // ── Products — specific routes BEFORE resource wildcard ───
    Route::get('/products/bulk', [BulkProductController::class, 'index'])->name('products.bulk');
    Route::post('/products/bulk', [BulkProductController::class, 'storeBulk'])->name('products.bulk-store');
    Route::post('/products/import-csv', [BulkProductController::class, 'importCsv'])->name('products.import-csv');
    Route::get('/products/csv-template', [BulkProductController::class, 'template'])->name('products.csv-template');
    Route::post('/products/upload-image', [AdminProductController::class, 'uploadImage'])->name('products.upload-image');
    Route::get('/products/trash', [AdminProductController::class, 'trash'])->name('products.trash');
    Route::patch('/products/{id}/restore', [AdminProductController::class, 'restore'])->name('products.restore');
    Route::delete('/products/{id}/force', [AdminProductController::class, 'forceDelete'])->name('products.force-delete');

    Route::resource('products', AdminProductController::class)->except(['show']);

    // ── Orders — specific routes BEFORE {order} wildcard ──────
    Route::get('/orders/create', [ManualOrderController::class, 'create'])->name('orders.create');
    Route::post('/orders/create', [ManualOrderController::class, 'store'])->name('orders.manual-store');
    Route::get('/orders/product-search', [ManualOrderController::class, 'productSearch'])->name('orders.product-search');
    Route::post('/orders/bulk', [AdminOrderController::class, 'bulkAction'])->name('orders.bulk');
    Route::get('/orders/pathao-lookup', [AdminOrderController::class, 'pathaoLookup'])->name('orders.pathao-lookup');

    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/payment', [AdminOrderController::class, 'updatePayment'])->name('orders.payment');
    Route::post('/orders/{order}/pathao', [AdminOrderController::class, 'pushToPathao'])->name('orders.pathao');
    Route::post('/orders/{order}/sync-pathao', [AdminOrderController::class, 'syncPathao'])->name('orders.sync-pathao');
    Route::post('/orders/{order}/note', [AdminOrderController::class, 'adminNote'])->name('orders.note');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice');
    Route::post('/orders/{order}/steadfast', [AdminOrderController::class, 'pushToSteadfast'])->name('orders.steadfast');
    Route::post('/orders/{order}/sync-steadfast', [AdminOrderController::class, 'syncSteadfast'])->name('orders.sync-steadfast');
    Route::get('/orders/{order}/shipping-label', [AdminOrderController::class, 'shippingLabel'])->name('orders.shipping-label');

    // ── Categories & Brands ──────────────────────────────────
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('brands', BrandController::class)->except(['show']);

    // ── Users ────────────────────────────────────────────────
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::patch('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // ── Prescriptions ─────────────────────────────────────────
    Route::get('/prescriptions', [PrescriptionController::class, 'index'])->name('prescriptions');
    Route::patch('/prescriptions/{p}/review', [PrescriptionController::class, 'review'])->name('prescriptions.review');

    // ── Reviews ──────────────────────────────────────────────
    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::patch('/reviews/{r}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{r}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // ── Settings — specific before wildcards ─────────────────
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::get('/settings/promos', [SettingsController::class, 'promos'])->name('settings.promos');
    Route::post('/settings/promos', [SettingsController::class, 'storePromo'])->name('settings.promos.store');
    Route::patch('/settings/promos/{promo}/toggle', [SettingsController::class, 'togglePromo'])->name('settings.promos.toggle');
    Route::delete('/settings/promos/{promo}', [SettingsController::class, 'destroyPromo'])->name('settings.promos.destroy');
    Route::get('/settings/banners', [SettingsController::class, 'banners'])->name('settings.banners');
    Route::post('/settings/banners', [SettingsController::class, 'storeBanner'])->name('settings.banners.store');
    Route::delete('/settings/banners/{banner}', [SettingsController::class, 'destroyBanner'])->name('settings.banners.destroy');
    Route::post('/settings/delivery-zones', [SettingsController::class, 'storeDeliveryZone'])->name('settings.delivery-zones.store');
    Route::put('/settings/delivery-zones/{zone}', [SettingsController::class, 'updateDeliveryZone'])->name('settings.delivery-zones.update');
    Route::delete('/settings/delivery-zones/{zone}', [SettingsController::class, 'destroyDeliveryZone'])->name('settings.delivery-zones.destroy');
    Route::post('/settings/pixel', [SettingsController::class, 'savePixel'])->name('settings.pixel');

    // ── SMS Logs ──────────────────────────────────────────────
    Route::get('/sms-logs', fn() => view('admin.sms-logs', [
        'logs' => \App\Models\SmsLog::latest()->paginate(30),
    ]))->name('sms-logs');
});