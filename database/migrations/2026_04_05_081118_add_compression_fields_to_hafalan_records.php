<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('hafalan_audio', function (Blueprint $table) {
            $table->bigInteger('original_audio_size')->nullable();
            $table->bigInteger('compressed_audio_size')->nullable();
            $table->decimal('compression_ratio', 5, 2)->nullable();
            $table->boolean('is_compressed')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hafalan_records', function (Blueprint $table) {
            //
        });
    }
};
