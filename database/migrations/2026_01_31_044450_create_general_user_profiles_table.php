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
        Schema::create('general_user_profiles', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');

            // Hafalan Progress
            $table->unsignedTinyInteger('total_juz_completed')->default(0);
            $table->unsignedInteger('total_ayat_completed')->default(0);

            // Streak Tracking
            $table->unsignedInteger('current_streak_days')->default(0);
            $table->unsignedInteger('longest_streak_days')->default(0);
            $table->date('last_hafalan_date')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('last_hafalan_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_user_profiles');
    }
};
