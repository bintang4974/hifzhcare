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

class SantriProfile extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'user_id',
        'pesantren_id',
        'wali_id',
        'nis',
        'birth_date',
        'gender',
        'address',
        'entry_date',
        'graduation_date',
        'total_juz_completed',
        'total_ayat_completed',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'entry_date' => 'date',
        'graduation_date' => 'date',
        'total_juz_completed' => 'integer',
        'total_ayat_completed' => 'integer',
    ];
    
    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the user that owns this profile.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the pesantren.
     */
    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    /**
     * Get the wali (guardian).
     */
    public function wali(): BelongsTo
    {
        return $this->belongsTo(WaliProfile::class, 'wali_id');
    }

    /**
     * Get all hafalans for this santri.
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id', 'user_id');
    }

    /**
     * Get all certificates.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }

    /**
     * Get all appreciation funds related to this santri.
     */
    public function appreciationFunds(): HasMany
    {
        return $this->hasMany(AppreciationFund::class);
    }

    /**
     * Get classes this santri is enrolled in (many-to-many).
     */
    public function classes(): BelongsToMany
    {
        return $this->belongsToMany(Classes::class, 'class_santri', 'santri_profile_id', 'class_id')
            ->withPivot(['enrolled_date', 'status'])
            ->withTimestamps();
    }

    /**
     * Get active classes only.
     */
    public function activeClasses(): BelongsToMany
    {
        return $this->classes()->wherePivot('status', 'active');
    }
    
    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    /**
     * Get age in years.
     */
    public function getAgeAttribute(): ?int
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    /**
     * Get full name from user.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Get gender label.
     */
    public function getGenderLabelAttribute(): string
    {
        return $this->gender === 'L' ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Check if santri has graduated.
     */
    public function getHasGraduatedAttribute(): bool
    {
        return !is_null($this->graduation_date);
    }

    /**
     * Get hafalan progress percentage (out of 30 juz).
     */
    public function getProgressPercentageAttribute(): float
    {
        return round(($this->total_juz_completed / 30) * 100, 2);
    }
    
    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope active santri (not graduated).
     */
    public function scopeActive($query)
    {
        return $query->whereNull('graduation_date')
            ->whereHas('user', fn($q) => $q->where('status', 'active'));
    }

    /**
     * Scope graduated santri.
     */
    public function scopeGraduated($query)
    {
        return $query->whereNotNull('graduation_date');
    }

    /**
     * Scope by gender.
     */
    public function scopeGender($query, string $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Scope by wali.
     */
    public function scopeByWali($query, int $waliId)
    {
        return $query->where('wali_id', $waliId);
    }
}
