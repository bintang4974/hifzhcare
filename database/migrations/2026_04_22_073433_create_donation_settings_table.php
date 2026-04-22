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
        Schema::create('donation_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pesantren_id')->constrained('pesantrens')->onDelete('cascade');

            // Fee Configuration
            $table->decimal('platform_fee_percentage', 5, 2)->default(3.00); // 3%
            $table->decimal('pesantren_fee_percentage', 5, 2)->default(10.00); // 10%

            // Payment Information
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->text('qris_image')->nullable(); // Path to QRIS image

            // Withdrawal Settings
            $table->decimal('minimum_withdrawal', 15, 2)->default(50000); // Min Rp 50,000
            $table->boolean('auto_approve_withdrawal')->default(false);

            // Features
            $table->boolean('donation_enabled')->default(true);
            $table->text('donation_message')->nullable(); // Message to wali
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donation_settings');
    }
};
