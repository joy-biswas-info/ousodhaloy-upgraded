<?php
// ============================================================
// MIGRATION: 2024_01_01_000001_create_users_table.php
// ============================================================
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('phone', 15)->unique()->nullable();
            $table->timestamp('phone_verified_at')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();
            $table->enum('role', ['admin', 'manager', 'customer'])->default('customer');
            $table->boolean('is_active')->default(true);
            $table->string('referral_code', 20)->unique()->nullable();
            $table->unsignedBigInteger('referred_by')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        // OTP table for phone login
        Schema::create('otps', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 15);
            $table->string('code', 6);
            $table->string('purpose')->default('login'); // login, register, reset
            $table->boolean('is_used')->default(false);
            $table->timestamp('expires_at');
            $table->timestamps();
            $table->index(['phone', 'code']);
        });

        // User addresses
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Home');
            $table->string('name');
            $table->string('phone', 15);
            $table->string('division');
            $table->string('district');
            $table->string('upazila');
            $table->text('address');
            $table->boolean('is_default')->default(false);
            $table->timestamps();
        });
    }
};
