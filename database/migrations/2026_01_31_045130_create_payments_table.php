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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // Tenant & Relationships
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained()->onDelete('cascade');

            // Payment Info
            $table->string('payment_number')->unique(); // PAY-2025-001
            $table->string('invoice_number')->unique(); // INV-2025-001
            $table->enum('payment_method', ['manual_transfer', 'midtrans']);
            $table->decimal('amount', 15, 2);

            // Status
            $table->enum('status', ['pending', 'verified', 'success', 'failed', 'cancelled'])->default('pending');

            // Manual Transfer
            $table->string('proof_of_payment_path')->nullable();

            // Midtrans
            $table->string('midtrans_order_id')->nullable()->unique();
            $table->string('midtrans_transaction_id')->nullable();
            $table->text('midtrans_response_json')->nullable();

            // Verification (for manual transfer)
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            // Payment Date
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['subscription_id', 'status']);
            $table->index(['status', 'payment_method']);
            $table->index('midtrans_order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
