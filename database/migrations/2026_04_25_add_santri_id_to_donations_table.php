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
        Schema::table('donations', function (Blueprint $table) {
            // Add santri_id if it doesn't exist
            if (!Schema::hasColumn('donations', 'santri_id')) {
                $table->foreignId('santri_id')->nullable()->constrained('santri_profiles')->onDelete('cascade')->after('ustadz_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\SantriProfile::class, 'santri_id');
            $table->dropColumn('santri_id');
        });
    }
};
