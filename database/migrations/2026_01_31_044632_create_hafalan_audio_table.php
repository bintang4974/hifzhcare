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
        Schema::create('hafalan_audio', function (Blueprint $table) {
            $table->id();

            // Tenant & Relationships
            $table->foreignId('pesantren_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('hafalan_id')->constrained()->onDelete('cascade');

            // File Info
            $table->string('original_filename');
            $table->string('stored_filename');
            $table->string('file_path');
            $table->string('mime_type', 50);
            $table->unsignedBigInteger('file_size'); // in bytes
            $table->unsignedInteger('duration_seconds')->nullable();

            // Processing Status
            $table->enum('status', ['pending', 'processing', 'ready', 'failed'])->default('pending');

            $table->timestamps();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['hafalan_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalan_audio');
    }
};
