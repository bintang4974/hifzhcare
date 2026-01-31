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
        Schema::create('santri_profiles', function (Blueprint $table) {
            $table->id();
            // Relationships
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');
            $table->foreignId('wali_id')->nullable()->constrained('wali_profiles')->onDelete('set null');
            
            // Basic Info
            $table->string('nis')->unique()->nullable(); // Nomor Induk Santri
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['L', 'P']); // Laki-laki / Perempuan
            $table->text('address')->nullable();
            
            // Academic Info
            $table->date('entry_date')->nullable();
            $table->date('graduation_date')->nullable();
            
            // Hafalan Progress
            $table->unsignedTinyInteger('total_juz_completed')->default(0); // 0-30
            $table->unsignedInteger('total_ayat_completed')->default(0);
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['pesantren_id', 'wali_id']);
            $table->index('nis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri_profiles');
    }
};
