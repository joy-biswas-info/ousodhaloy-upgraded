<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('status', 20)->default('draft'); // draft | published

            // Hero
            $table->string('headline');
            $table->string('subheadline')->nullable();
            $table->string('eyebrow_text')->nullable();
            $table->string('badge_text')->nullable();
            $table->string('hero_image')->nullable();

            // Pricing overrides — null means "use the product's own price"
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('compare_at_price', 10, 2)->nullable();
            $table->timestamp('countdown_end_at')->nullable();

            // { accent, cta } hex colors — bright/hover variants derived client-side via color-mix()
            $table->json('theme')->nullable();
            // { problems, formula, benefits, how_to_use, ingredients, reviews, faq, gallery, trust_badges }
            // each: { enabled: bool, items/text: ... } — see LandingPage model for shape
            $table->json('sections')->nullable();

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('shipping_note')->nullable();
            $table->text('return_policy_note')->nullable();

            $table->unsignedBigInteger('views')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['status', 'product_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('landing_page_id')->nullable()->after('user_id')
                ->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['landing_page_id']);
            $table->dropColumn('landing_page_id');
        });
        Schema::dropIfExists('landing_pages');
    }
};
