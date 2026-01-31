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
        Schema::create('class_ustadz', function (Blueprint $table) {
            $table->id();

            // Tenant
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');

            // Relationships
            $table->foreignId('class_id')->constrained('classes')->onDelete('cascade');
            $table->foreignId('ustadz_profile_id')->constrained('ustadz_profiles')->onDelete('cascade');

            // Assignment Info
            $table->date('assigned_date')->default(now());
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            // Constraints & Indexes
            $table->unique(['class_id', 'ustadz_profile_id'], 'unique_class_ustadz');
            $table->index(['pesantren_id', 'class_id']);
            $table->index(['pesantren_id', 'ustadz_profile_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_ustadzs');
    }
};
