<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Promo codes
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('title')->nullable();
            $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('value', 10, 2);
            $table->decimal('min_order', 10, 2)->default(0);
            $table->decimal('max_discount', 10, 2)->nullable();
            $table->integer('usage_limit')->nullable();
            $table->integer('per_user_limit')->default(1);
            $table->integer('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('first_order_only')->default(false);
            $table->json('applicable_categories')->nullable();
            $table->json('applicable_products')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['code', 'is_active']);
        });
        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            // Guest info
            $table->string('guest_name')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('guest_phone', 15)->nullable();
            // Status
            $table->enum('status', [
                'pending',
                'confirmed',
                'processing',
                'ready_to_ship',
                'shipped',
                'out_for_delivery',
                'delivered',
                'cancelled',
                'refunded',
                'on_hold',
                'returned'
            ])->default('pending');
            $table->enum('payment_status', ['unpaid', 'pending', 'paid', 'failed', 'refunded'])->default('unpaid');
            $table->enum('payment_method', ['cod', 'ssl_commerz', 'bkash', 'nagad', 'rocket', 'bank'])->default('cod');
            $table->string('ssl_transaction_id')->nullable();
            $table->string('ssl_val_id')->nullable();
            // Amounts
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_charge', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('promo_code')->nullable();
            // Shipping
            $table->string('shipping_name');
            $table->string('shipping_phone', 15);
            $table->string('shipping_email')->nullable();
            $table->string('shipping_division');
            $table->string('shipping_district');
            $table->string('shipping_upazila')->nullable();
            $table->text('shipping_address');
            $table->string('shipping_postcode', 10)->nullable();
            // Pathao
            $table->string('pathao_order_id')->nullable();
            $table->string('pathao_consignment_id')->nullable();
            $table->string('pathao_status')->nullable();
            $table->string('pathao_tracking_code')->nullable();
            // Misc
            $table->text('customer_note')->nullable();
            $table->text('admin_note')->nullable();
            $table->string('prescription_image')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index(['user_id', 'status']);
        });

        // Order items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->string('product_name');
            $table->string('product_sku')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable();
            $table->integer('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });

        // Order status history
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status');
            $table->text('note')->nullable();
            $table->string('changed_by')->nullable(); // admin name or 'system'
            $table->boolean('notify_customer')->default(false);
            $table->timestamps();
        });

        // Promo code usage log
        Schema::create('promo_code_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promo_code_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('discount_amount', 10, 2);
            $table->timestamps();
        });

        // Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'product_id']);
        });
    }
};
