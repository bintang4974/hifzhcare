<?php

namespace App\Observers;

use App\Models\DonationSetting;
use App\Models\Pesantren;

class PesantrenObserver
{
    /**
     * Handle the Pesantren "created" event.
     */
    public function created(Pesantren $pesantren): void
    {
        // Automatically create donation settings for new pesantren
        if (!$pesantren->donationSettings) {
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
    }

    /**
     * Handle the Pesantren "updated" event.
     */
    public function updated(Pesantren $pesantren): void
    {
        //
    }

    /**
     * Handle the Pesantren "deleted" event.
     */
    public function deleted(Pesantren $pesantren): void
    {
        //
    }

    /**
     * Handle the Pesantren "restored" event.
     */
    public function restored(Pesantren $pesantren): void
    {
        //
    }

    /**
     * Handle the Pesantren "force deleted" event.
     */
    public function forceDeleted(Pesantren $pesantren): void
    {
        //
    }
}
