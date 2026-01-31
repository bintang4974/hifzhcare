<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class AppreciationFund extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'pesantren_id',
        'wali_profile_id',
        'ustadz_profile_id',
        'santri_profile_id',
        'amount',
        'status',
        'proof_of_payment_path',
        'notes',
        'verified_by_user_id',
        'verified_at',
        'disbursed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'verified_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function wali(): BelongsTo
    {
        return $this->belongsTo(WaliProfile::class, 'wali_profile_id');
    }

    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(UstadzProfile::class, 'ustadz_profile_id');
    }

    public function santri(): BelongsTo
    {
        return $this->belongsTo(SantriProfile::class, 'santri_profile_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_of_payment_path
            ? Storage::disk('public')->url($this->proof_of_payment_path)
            : null;
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeDisbursed($query)
    {
        return $query->where('status', 'disbursed');
    }
}
