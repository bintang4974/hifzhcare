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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();

            // Tenant & Relationships (nullable for general users)
            $table->foreignId('pesantren_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('certificate_template_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Santri/General User

            // Certificate Info
            $table->string('certificate_number')->unique(); // CERT-2025-001
            $table->enum('type', ['santri_juz', 'general_achievement', 'general_consistency']);
            $table->unsignedTinyInteger('juz_completed')->nullable(); // For juz certificates
            $table->text('metadata_json')->nullable(); // Additional custom data

            // Status & Approval
            $table->enum('status', ['pending', 'approved', 'rejected', 'issued'])->default('pending');
            $table->foreignId('approved_by_ustadz_id')->nullable()->constrained('ustadz_profiles')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();

            // Generated File
            $table->string('generated_file_path')->nullable();
            $table->timestamp('issued_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['user_id', 'status']);
            $table->index(['status', 'type']);
            $table->index('certificate_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
