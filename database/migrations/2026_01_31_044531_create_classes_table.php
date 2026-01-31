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
        Schema::create('classes', function (Blueprint $table) {
            $table->id();

            // Tenant
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('name'); // Contoh: "Kelas Tahfidz A"
            $table->string('code')->unique(); // Contoh: "TAHFIDZ-A-2024"
            $table->text('description')->nullable();

            // Status & Capacity
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedInteger('max_capacity')->default(30);
            $table->unsignedInteger('current_student_count')->default(0);

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['pesantren_id', 'status']);
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
