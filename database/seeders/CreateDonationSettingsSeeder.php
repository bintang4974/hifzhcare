<?php

namespace Database\Seeders;

use App\Models\DonationSetting;
use App\Models\Pesantren;
use Illuminate\Database\Seeder;

class CreateDonationSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all pesantrens that don't have donation settings yet
        $pesantrens = Pesantren::whereDoesntHave('donationSettings')->get();

        foreach ($pesantrens as $pesantren) {
            DonationSetting::create([
                'pesantren_id' => $pesantren->id,
                'platform_fee_percentage' => 3.00,
                'pesantren_fee_percentage' => 10.00,
                'bank_name' => null,
                'account_number' => null,
                'account_name' => null,
                'qris_image' => null,
                'minimum_withdrawal' => 50000,
                'auto_approve_withdrawal' => false,
                'donation_enabled' => true,
                'donation_message' => 'Terima kasih telah mendukung pendidikan santri kami.',
            ]);
        }

        $this->command->info('Donation settings created for ' . $pesantrens->count() . ' pesantrens.');
    }
}
