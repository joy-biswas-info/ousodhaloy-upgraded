<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Site settings key-value store
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Banners / Sliders
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->string('image');
            $table->string('mobile_image')->nullable();
            $table->string('link_url')->nullable();
            $table->string('button_text')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('bg_color')->default('#0e7673');
            $table->enum('position', ['hero', 'promo', 'sidebar', 'popup'])->default('hero');
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });

        // SMS logs
        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15);
            $table->text('message');
            $table->enum('status', ['sent', 'failed', 'queued'])->default('queued');
            $table->string('provider')->default('mimsms');
            $table->string('reference')->nullable();
            $table->string('purpose')->nullable(); // order_confirm, status_update, otp
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->json('response')->nullable();
            $table->timestamps();
        });

        // Notifications (in-app)
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        // Prescription uploads
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('guest_phone', 15)->nullable();
            $table->string('image');
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        // Delivery areas & charges
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Dhaka City"
            $table->string('division');
            $table->json('districts')->nullable();
            $table->decimal('delivery_charge', 8, 2)->default(60);
            $table->decimal('free_delivery_above', 10, 2)->default(500);
            $table->integer('estimated_days')->default(2);
            $table->boolean('express_available')->default(false);
            $table->decimal('express_charge', 8, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Flash deals
        Schema::create('flash_deals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('flash_deal_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('flash_deal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->decimal('sale_price', 10, 2);
            $table->integer('max_quantity')->nullable();
            $table->integer('sold_quantity')->default(0);
        });

        // Loyalty points
        Schema::create('loyalty_points', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points');
            $table->enum('type', ['earn', 'redeem', 'expire', 'bonus'])->default('earn');
            $table->string('description');
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });

        // Cache tables
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });
    }
};
