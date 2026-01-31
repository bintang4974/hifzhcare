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
        Schema::create('stakeholder_profiles', function (Blueprint $table) {
            $table->id();

            // Relationships
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            $table->foreignId('pesantren_id')->constrained()->onDelete('cascade');

            // Basic Info
            $table->string('position'); // Jabatan: Kyai, Gus, Ketua Yayasan, dll

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index(['pesantren_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stakeholder_profiles');
    }
};
