<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_product', function (Blueprint $table) {
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->primary(['category_id', 'product_id']);
        });

        // Migrate existing category_id data into the pivot
        \DB::table('products')
            ->whereNotNull('category_id')
            ->get(['id', 'category_id'])
            ->each(function ($p) {
                \DB::table('category_product')->insertOrIgnore([
                    'category_id' => $p->category_id,
                    'product_id' => $p->id,
                    'is_primary' => true,
                ]);
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('category_product');
    }
};