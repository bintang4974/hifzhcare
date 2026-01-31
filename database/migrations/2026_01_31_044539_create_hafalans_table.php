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
        Schema::create('hafalans', function (Blueprint $table) {
            $table->id();

            // Tenant & Relationships (nullable for general users)
            $table->foreignId('pesantren_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('class_id')->nullable()->constrained('classes')->onDelete('set null');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Santri/General User
            $table->foreignId('created_by_user_id')->constrained('users')->onDelete('cascade'); // Ustadz/Self

            // Quran Reference
            $table->unsignedTinyInteger('surah_number'); // 1-114
            $table->unsignedSmallInteger('ayat_start');
            $table->unsignedSmallInteger('ayat_end');
            $table->unsignedTinyInteger('juz_number'); // 1-30

            // Type & Status
            $table->enum('type', ['setoran', 'murajah'])->default('setoran');
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending');

            // Additional Info
            $table->text('notes')->nullable();

            // Verification
            $table->foreignId('verified_by_ustadz_id')->nullable()->constrained('ustadz_profiles')->onDelete('set null');
            $table->timestamp('verified_at')->nullable();

            // Dates
            $table->date('hafalan_date');
            $table->timestamps();
            $table->softDeletes();

            // Indexes untuk performance
            $table->index(['pesantren_id', 'created_at']);
            $table->index(['user_id', 'hafalan_date']);
            $table->index(['class_id', 'status']);
            $table->index(['juz_number', 'status']);
            $table->index(['verified_at', 'status']);
            $table->index(['surah_number', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hafalans');
    }
};
