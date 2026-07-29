<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Which status the order was in right before it went on_hold, so
            // the admin can resume to the correct pipeline stage instead of
            // guessing — see Order::STATUS_FLOW and OrderService::updateStatus().
            $table->string('held_from_status')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('held_from_status');
        });
    }
};
