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
        // MySQL: modify enum to add 'pending' status
        DB::statement("ALTER TABLE pesantrens MODIFY COLUMN status ENUM('pending', 'active', 'suspended', 'inactive') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum
        DB::statement("ALTER TABLE pesantrens MODIFY COLUMN status ENUM('active', 'suspended', 'inactive') DEFAULT 'active'");
    }
};
