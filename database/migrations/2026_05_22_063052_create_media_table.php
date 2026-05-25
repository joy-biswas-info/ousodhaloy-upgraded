<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->id();
            $table->string('filename');           // SEO slug filename on disk: napa-500mg-paracetamol.webp
            $table->string('original_name');      // what user uploaded: Napa 500mg.jpg
            $table->string('path');               // storage path: media/napa-500mg-paracetamol.webp
            $table->string('mime_type');
            $table->unsignedBigInteger('size');   // bytes
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->string('alt_text')->nullable();
            $table->string('folder')->default('media');
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->index(['folder', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_library');
    }
};