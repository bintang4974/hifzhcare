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
        Schema::create('pesantrens', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('email')->unique();
            $table->string('phone', 20);
            $table->text('address');
            $table->string('logo_url')->nullable();
            
            // Status & Subscription
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active');
            $table->enum('subscription_tier', ['free', 'low', 'medium', 'large', 'enterprise'])->default('free');
            $table->unsignedInteger('max_santri')->default(50);
            $table->unsignedInteger('current_santri_count')->default(0);
            
            // Features Toggle
            $table->boolean('is_appreciation_fund_enabled')->default(true);
            
            // Storage Usage
            $table->unsignedBigInteger('audio_storage_used')->default(0); // in bytes
            
            // Subscription Dates
            $table->timestamp('subscription_expired_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('status');
            $table->index('subscription_tier');
            $table->index(['subscription_tier', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesantrens');
    }
};
