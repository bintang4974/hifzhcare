<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationSetting extends Model
{
    protected $fillable = [
        'pesantren_id',
        'platform_fee_percentage',
        'pesantren_fee_percentage',
        'bank_name',
        'account_number',
        'account_name',
        'qris_image',
        'minimum_withdrawal',
        'auto_approve_withdrawal',
        'donation_enabled',
        'donation_message',
    ];

    protected $casts = [
        'platform_fee_percentage' => 'decimal:2',
        'pesantren_fee_percentage' => 'decimal:2',
        'minimum_withdrawal' => 'decimal:2',
        'auto_approve_withdrawal' => 'boolean',
        'donation_enabled' => 'boolean',
    ];

    /**
     * Get the pesantren that owns this donation setting.
     */
    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }
}
