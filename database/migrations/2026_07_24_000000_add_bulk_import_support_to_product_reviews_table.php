<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\{DB, Schema};

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            // Imported reviews aren't tied to a registered account on this site.
            $table->string('reviewer_name')->nullable()->after('user_id');
            // 'site' (submitted here) vs 'imported' (bulk-loaded from elsewhere) — for admin auditing.
            $table->string('source', 20)->default('site')->after('reviewer_name');
            // Preserves the review's real original date so imported batches don't
            // all appear to have been posted the same day they were uploaded.
            $table->timestamp('reviewed_at')->nullable()->after('source');
        });

        // user_id must become nullable for imported reviews. Doctrine/DBAL isn't
        // installed, so ->change() isn't available — drop/re-add the FK by hand.
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        DB::statement('ALTER TABLE product_reviews MODIFY user_id BIGINT UNSIGNED NULL');
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });
        DB::statement('DELETE FROM product_reviews WHERE user_id IS NULL');
        DB::statement('ALTER TABLE product_reviews MODIFY user_id BIGINT UNSIGNED NOT NULL');
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn(['reviewer_name', 'source', 'reviewed_at']);
        });
    }
};
