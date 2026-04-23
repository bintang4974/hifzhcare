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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            // Relations
            $table->foreignId('wali_id')->constrained('wali_profiles')->onDelete('cascade');
            $table->foreignId('ustadz_id')->constrained('ustadz_profiles')->onDelete('cascade');
            $table->foreignId('pesantren_id')->constrained('pesantrens')->onDelete('cascade');

            // Donation Info
            $table->string('donation_code')->unique(); // DON-2024-0001
            $table->decimal('amount', 15, 2); // Original amount
            $table->decimal('platform_fee', 15, 2)->default(0); // 3%
            $table->decimal('pesantren_fee', 15, 2)->default(0); // 10%
            $table->decimal('ustadz_net_amount', 15, 2); // Net for ustadz (87%)
            $table->decimal('transfer_to_pesantren', 15, 2); // Amount transferred to pesantren (97%)

            // Payment Info
            $table->string('payment_method')->default('transfer'); // transfer, qris
            $table->string('payment_proof')->nullable(); // Upload bukti transfer
            $table->text('notes')->nullable();

            // Status Flow
            $table->enum('status', [
                'pending',          // Waiting superadmin verification
                'verified',         // Approved by superadmin
                'transferred',      // Transferred to pesantren
                'available',        // Available for ustadz withdrawal
                'requested',        // Ustadz requested withdrawal
                'disbursed',        // Disbursed by admin to ustadz
                'rejected'          // Rejected by superadmin
            ])->default('pending');

            // Verification (SuperAdmin)
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->timestamp('verified_at')->nullable();
            $table->text('verification_notes')->nullable();

            // Transfer to Pesantren (SuperAdmin)
            $table->foreignId('transferred_by')->nullable()->constrained('users');
            $table->timestamp('transferred_at')->nullable();
            $table->string('transfer_proof')->nullable(); // Bukti transfer ke pesantren

            // Withdrawal Request (Ustadz)
            $table->timestamp('requested_at')->nullable();
            $table->text('request_notes')->nullable();

            // Disbursement (Admin Pondok)
            $table->foreignId('disbursed_by')->nullable()->constrained('users');
            $table->timestamp('disbursed_at')->nullable();
            $table->text('disbursement_notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['status', 'pesantren_id']);
            $table->index(['ustadz_id', 'status']);
            $table->index('donation_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
