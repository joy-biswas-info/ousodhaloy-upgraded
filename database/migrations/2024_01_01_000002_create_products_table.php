<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Categories (hierarchical)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('banner_image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        // Brands / Manufacturers
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('logo')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Products
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('sku')->unique()->nullable();
            $table->string('barcode')->nullable();
            $table->string('generic_name')->nullable();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('thumbnail')->nullable();
            $table->json('images')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('mrp', 10, 2)->nullable()->comment('Maximum Retail Price');
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->integer('stock')->default(0);
            $table->integer('low_stock_alert')->default(10);
            $table->integer('min_order_qty')->default(1);
            $table->integer('max_order_qty')->default(100);
            $table->string('unit')->default('pcs')->comment('pcs, strip, bottle, box, ml, mg');
            $table->string('pack_size')->nullable()->comment('e.g. 10 tablets/strip');
            $table->string('strength')->nullable()->comment('e.g. 500mg');
            $table->string('form')->nullable()->comment('Tablet, Syrup, Capsule, Cream...');
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_flash_sale')->default(false);
            $table->decimal('flash_sale_price', 10, 2)->nullable();
            $table->timestamp('flash_sale_ends_at')->nullable();
            $table->boolean('express_delivery')->default(false);
            $table->json('tabs')->nullable()->comment('[{id, label, content}] — customizable content tabs');
            $table->json('tags')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->unsignedBigInteger('total_sold')->default(0);
            $table->decimal('average_rating', 3, 2)->default(0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'category_id']);
            $table->index(['is_flash_sale', 'is_active']);
            $table->index(['is_featured', 'is_active']);
        });

        // Product reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('order_item_id')->nullable();
            $table->unsignedTinyInteger('rating'); // 1-5
            $table->string('title')->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            $table->unique(['product_id', 'user_id', 'order_item_id']);
        });

        // Related products
        Schema::create('product_related', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('related_id')->constrained('products')->cascadeOnDelete();
            $table->primary(['product_id', 'related_id']);
        });
    }
};
