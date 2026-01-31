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
        Schema::create('wali_profiles', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('nik', 20)->unique()->nullable(); // NIK KTP
            $table->enum('relation', ['ayah', 'ibu', 'wali'])->default('ayah');
            $table->string('occupation')->nullable(); // Pekerjaan
            $table->text('address')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index('nik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_profiles');
    }
};
