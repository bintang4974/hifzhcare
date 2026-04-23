<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get all existing pesantrens that don't have donation settings
        $pesantrens = DB::table('pesantrens')
            ->whereNotIn('id', DB::table('donation_settings')->select('pesantren_id'))
            ->get();

        foreach ($pesantrens as $pesantren) {
            DB::table('donation_settings')->insert([
                'pesantren_id' => $pesantren->id,
                'platform_fee_percentage' => 3.00,
                'pesantren_fee_percentage' => 10.00,
                'bank_name' => null,
                'account_number' => null,
                'account_name' => null,
                'qris_image' => null,
                'minimum_withdrawal' => 50000,
                'auto_approve_withdrawal' => 0,
                'donation_enabled' => 1,
                'donation_message' => 'Terima kasih telah mendukung pendidikan santri kami.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is for populating data, not creating tables
        // Reversal would delete donation settings data
        // For safety, we'll just leave this empty
    }
};
