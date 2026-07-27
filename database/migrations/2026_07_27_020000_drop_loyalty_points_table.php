<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Loyalty points accrued on every order but had no redemption path anywhere in
// checkout, and the admin settings meant to configure it (loyalty_enabled,
// points-per-taka) had no UI tab to actually set them. Removed rather than
// left as a number that only ever goes up with no payoff.
return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('loyalty_points');
    }

    public function down(): void
    {
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
    }
};
