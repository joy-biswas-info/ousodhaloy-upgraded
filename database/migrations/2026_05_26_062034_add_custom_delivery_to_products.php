<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('custom_delivery_charge', 8, 2)->nullable()->after('express_delivery')
                ->comment('Override global delivery charge for this product. null = use global setting.');
            $table->boolean('delivery_charge_per_unit')->default(false)->after('custom_delivery_charge')
                ->comment('If true, multiply custom_delivery_charge by quantity ordered.');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['custom_delivery_charge', 'delivery_charge_per_unit']);
        });
    }
};