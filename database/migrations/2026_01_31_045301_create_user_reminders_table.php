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
        Schema::create('user_reminders', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('general_user_profile_id')->constrained()->onDelete('cascade');

            // Reminder Info
            $table->enum('reminder_type', ['murajah', 'daily_target']);
            $table->time('reminder_time'); // HH:MM
            $table->json('days_of_week')->nullable(); // [0,1,2,3,4,5,6] (Sunday-Saturday)

            // Status
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index(['general_user_profile_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_reminders');
    }
};
