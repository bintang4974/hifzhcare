<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Donation;
use App\Models\WaliProfile;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Populate santri_id for existing donations
        $donations = Donation::whereNull('santri_id')->get();

        foreach ($donations as $donation) {
            // Get wali
            $wali = WaliProfile::find($donation->wali_id);
            if (!$wali) continue;

            // Get first santri that has classes with the ustadz
            $santri = $wali->santriProfiles()
                ->whereHas('activeClasses', function ($query) use ($donation) {
                    $query->whereHas('ustadzProfiles', function ($q) use ($donation) {
                        $q->where('ustadz_profiles.id', $donation->ustadz_id);
                    });
                })
                ->first();

            // If found, update donation
            if ($santri) {
                $donation->update(['santri_id' => $santri->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't do anything on rollback
    }
};
