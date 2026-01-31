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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();

            // Tenant
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');

            // Subscription Info
            $table->string('subscription_number')->unique(); // SUB-2025-001
            $table->enum('tier', ['low', 'medium', 'large', 'enterprise']);
            $table->unsignedInteger('max_santri');
            $table->decimal('price', 15, 2);

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Status
            $table->enum('status', ['active', 'expired', 'cancelled'])->default('active');

            $table->timestamps();

            // Indexes
            $table->index(['pesantren_id', 'status']);
            $table->index(['end_date', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
