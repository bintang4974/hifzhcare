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
        Schema::table('pesantrens', function (Blueprint $table) {
            // Add additional fields for pesantren details
            $table->text('description')->nullable()->after('address');
            $table->string('website')->nullable()->after('email');
            $table->string('whatsapp', 20)->nullable()->after('phone');
            $table->unsignedSmallInteger('established_year')->nullable()->after('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pesantrens', function (Blueprint $table) {
            $table->dropColumn(['description', 'website', 'whatsapp', 'established_year']);
        });
    }
};
