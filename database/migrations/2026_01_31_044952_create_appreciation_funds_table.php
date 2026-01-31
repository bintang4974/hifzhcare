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
        Schema::create('appreciation_funds', function (Blueprint $table) {
            $table->id();

            // Tenant & Relationships
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');
            $table->foreignId('wali_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('ustadz_profile_id')->constrained()->onDelete('cascade');
            $table->foreignId('santri_profile_id')->constrained()->onDelete('cascade'); // For reference

            // Amount
            $table->decimal('amount', 15, 2);

            // Status
            $table->enum('status', ['pending', 'verified', 'disbursed', 'rejected'])->default('pending');

            // Proof of Payment
            $table->string('proof_of_payment_path')->nullable();

            // Notes
            $table->text('notes')->nullable();

            // Verification
            $table->foreignId('verified_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            // Disbursement
            $table->timestamp('disbursed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['wali_profile_id', 'status']);
            $table->index(['ustadz_profile_id', 'status']);
            $table->index(['status', 'verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appreciation_funds');
    }
};
