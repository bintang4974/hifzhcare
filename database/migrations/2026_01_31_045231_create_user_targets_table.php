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
        Schema::create('user_targets', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('general_user_profile_id')->constrained()->onDelete('cascade');

            // Target Info
            $table->enum('target_type', ['daily', 'weekly', 'monthly', 'yearly']);
            $table->unsignedInteger('target_ayat_count')->default(0);
            $table->unsignedTinyInteger('target_juz_count')->default(0);

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Status
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');

            $table->timestamps();

            // Indexes
            $table->index(['general_user_profile_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_targets');
    }
};
