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
        Schema::create('certificate_templates', function (Blueprint $table) {
            $table->id();

            // Tenant (nullable for general templates)
            $table->foreignId('pesantren_id')->nullable()->constrained()->onDelete('cascade');

            // Template Info
            $table->string('name');
            $table->enum('type', ['santri_juz', 'general_achievement', 'general_consistency']);
            $table->string('file_path'); // Path to PDF template
            $table->text('placeholders_json')->nullable(); // Available placeholders

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'type']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};
