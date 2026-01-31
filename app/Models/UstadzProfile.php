<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UstadzProfile extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'user_id',
        'pesantren_id',
        'nip',
        'specialization',
        'join_date',
        'total_appreciation_received',
    ];

    protected $casts = [
        'join_date' => 'date',
        'total_appreciation_received' => 'decimal:2',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_ustadz', 'ustadz_profile_id', 'class_id')
            ->withPivot(['assigned_date', 'status'])
            ->withTimestamps();
    }

    public function activeClasses(): BelongsToMany
    {
        return $this->classes()->wherePivot('status', 'active');
    }

    public function verifiedHafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'verified_by_ustadz_id');
    }

    public function appreciationFunds(): HasMany
    {
        return $this->hasMany(AppreciationFund::class);
    }

    public function approvedCertificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'approved_by_ustadz_id');
    }

    // Accessors

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getTotalPendingFundsAttribute(): float
    {
        return $this->appreciationFunds()
            ->where('status', 'verified')
            ->sum('amount');
    }

    public function getTotalDisbursedFundsAttribute(): float
    {
        return $this->appreciationFunds()
            ->where('status', 'disbursed')
            ->sum('amount');
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->whereHas('user', fn($q) => $q->where('status', 'active'));
    }
}
