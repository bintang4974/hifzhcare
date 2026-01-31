<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class WaliProfile extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'user_id',
        'pesantren_id',
        'nik',
        'relation',
        'occupation',
        'address',
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

    public function santriProfiles(): HasMany
    {
        return $this->hasMany(SantriProfile::class, 'wali_id');
    }

    public function appreciationFunds(): HasMany
    {
        return $this->hasMany(AppreciationFund::class);
    }

    // Accessors

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getRelationLabelAttribute(): string
    {
        return match ($this->relation) {
            'ayah' => 'Ayah',
            'ibu' => 'Ibu',
            'wali' => 'Wali',
            default => $this->relation,
        };
    }

    public function getTotalFundsDonatedAttribute(): float
    {
        return $this->appreciationFunds()
            ->whereIn('status', ['verified', 'disbursed'])
            ->sum('amount');
    }

    // Scopes

    public function scopeByRelation($query, string $relation)
    {
        return $query->where('relation', $relation);
    }

    public function scopeActive($query)
    {
        return $query->whereHas('user', fn($q) => $q->where('status', 'active'));
    }
}
