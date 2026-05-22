<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Generic courier fields (works for both Pathao and Steadfast)
            $table->string('courier')->nullable()->after('pathao_tracking_code')
                ->comment('pathao or steadfast');

            // Steadfast specific
            $table->string('steadfast_consignment_id')->nullable()->after('courier');
            $table->string('steadfast_tracking_code')->nullable()->after('steadfast_consignment_id');
            $table->string('steadfast_status')->nullable()->after('steadfast_tracking_code');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'courier',
                'steadfast_consignment_id',
                'steadfast_tracking_code',
                'steadfast_status',
            ]);
        });
    }
};