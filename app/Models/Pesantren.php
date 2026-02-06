<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pesantren extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'logo_url',
        'status',
        'subscription_tier',
        'max_santri',
        'current_santri_count',
        'is_appreciation_fund_enabled',
        'audio_storage_used',
        'subscription_expired_at',
        'activated_at',
    ];

    protected $casts = [
        'is_appreciation_fund_enabled' => 'boolean',
        'audio_storage_used' => 'integer',
        'subscription_expired_at' => 'datetime',
        'activated_at' => 'datetime',
    ];

    /**
     * Boot model events.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate slug from name
        static::creating(function ($pesantren) {
            if (empty($pesantren->slug)) {
                $pesantren->slug = Str::slug($pesantren->name);
            }
        });
    }

    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get all users in this pesantren.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get all santri profiles (alias: santris).
     */
    public function santris(): HasMany
    {
        return $this->hasMany(SantriProfile::class);
    }

    /**
     * Get all santri profiles.
     */
    public function santriProfiles(): HasMany
    {
        return $this->hasMany(SantriProfile::class);
    }

    /**
     * Get all ustadz profiles (alias: ustadzs).
     */
    public function ustadzs(): HasMany
    {
        return $this->hasMany(UstadzProfile::class);
    }

    /**
     * Get all ustadz profiles.
     */
    public function ustadzProfiles(): HasMany
    {
        return $this->hasMany(UstadzProfile::class);
    }

    /**
     * Get all wali profiles.
     */
    public function waliProfiles(): HasMany
    {
        return $this->hasMany(WaliProfile::class);
    }

    /**
     * Get all stakeholder profiles.
     */
    public function stakeholderProfiles(): HasMany
    {
        return $this->hasMany(StakeholderProfile::class);
    }

    /**
     * Get all classes.
     */
    public function classes(): HasMany
    {
        return $this->hasMany(Classes::class);
    }

    /**
     * Get all hafalans.
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class);
    }

    /**
     * Get all certificates.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Get all certificate templates.
     */
    public function certificateTemplates(): HasMany
    {
        return $this->hasMany(CertificateTemplate::class);
    }

    /**
     * Get all appreciation funds.
     */
    public function appreciationFunds(): HasMany
    {
        return $this->hasMany(AppreciationFund::class);
    }

    /**
     * Get all subscriptions.
     */
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Get all payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get all admin users for this pesantren.
     */
    public function admins(): HasMany
    {
        return $this->hasMany(User::class)->where('user_type', 'admin');
    }

    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    /**
     * Get subscription status.
     */
    public function getIsSubscriptionActiveAttribute(): bool
    {
        return $this->status === 'active'
            && $this->subscription_tier !== 'free'
            && $this->subscription_expired_at
            && $this->subscription_expired_at->isFuture();
    }

    /**
     * Get available santri slots.
     */
    public function getAvailableSantriSlotsAttribute(): int
    {
        return max(0, $this->max_santri - $this->current_santri_count);
    }

    /**
     * Check if santri quota is full.
     */
    public function getIsQuotaFullAttribute(): bool
    {
        return $this->current_santri_count >= $this->max_santri;
    }

    /**
     * Get audio storage in human readable format.
     */
    public function getAudioStorageUsedHumanAttribute(): string
    {
        $bytes = $this->audio_storage_used;

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2).' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2).' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2).' KB';
        }

        return $bytes.' bytes';
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope active pesantrens.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope by subscription tier.
     */
    public function scopeTier($query, string $tier)
    {
        return $query->where('subscription_tier', $tier);
    }

    /**
     * Scope with active subscription.
     */
    public function scopeWithActiveSubscription($query)
    {
        return $query->where('status', 'active')
            ->where('subscription_tier', '!=', 'free')
            ->where('subscription_expired_at', '>', now());
    }
}
