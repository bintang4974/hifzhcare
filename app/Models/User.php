<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, Auditable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'pesantren_id',
        'name',
        'email',
        'phone',
        'password',
        'user_type',
        'status',
        'is_pro',
        'pro_expired_at',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'pro_expired_at' => 'datetime',
            'password' => 'hashed',
            'is_pro' => 'boolean',
        ];
    }
    
    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the pesantren this user belongs to.
     */
    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    /**
     * Get santri profile.
     */
    public function santriProfile(): HasOne
    {
        return $this->hasOne(SantriProfile::class);
    }

    /**
     * Get ustadz profile.
     */
    public function ustadzProfile(): HasOne
    {
        return $this->hasOne(UstadzProfile::class);
    }

    /**
     * Get wali profile.
     */
    public function waliProfile(): HasOne
    {
        return $this->hasOne(WaliProfile::class);
    }

    /**
     * Get stakeholder profile.
     */
    public function stakeholderProfile(): HasOne
    {
        return $this->hasOne(StakeholderProfile::class);
    }

    /**
     * Get general user profile.
     */
    public function generalUserProfile(): HasOne
    {
        return $this->hasOne(GeneralUserProfile::class);
    }

    /**
     * Get all hafalans created by this user (as santri or general user).
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id');
    }

    /**
     * Get all hafalans created by this user (as ustadz).
     */
    public function createdHafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'created_by_user_id');
    }

    /**
     * Get all certificates for this user.
     */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }
    
    // ============================================
    // HELPER METHODS
    // ============================================

    /**
     * Get the appropriate profile based on user type.
     */
    public function getProfileAttribute()
    {
        return match ($this->user_type) {
            'santri' => $this->santriProfile,
            'ustadz' => $this->ustadzProfile,
            'wali' => $this->waliProfile,
            'stakeholder' => $this->stakeholderProfile,
            'general' => $this->generalUserProfile,
            default => null,
        };
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->user_type === 'super_admin' || $this->hasRole('Super Admin');
    }

    /**
     * Check if user is admin pesantren.
     */
    public function isAdminPesantren(): bool
    {
        return $this->user_type === 'admin' && $this->hasRole('Admin Pesantren');
    }

    /**
     * Check if user is ustadz.
     */
    public function isUstadz(): bool
    {
        return $this->user_type === 'ustadz' && $this->hasRole('Ustadz');
    }

    /**
     * Check if user is santri.
     */
    public function isSantri(): bool
    {
        return $this->user_type === 'santri' && $this->hasRole('Santri');
    }

    /**
     * Check if user is wali.
     */
    public function isWali(): bool
    {
        return $this->user_type === 'wali' && $this->hasRole('Wali Santri');
    }

    /**
     * Check if user is stakeholder.
     */
    public function isStakeholder(): bool
    {
        return $this->user_type === 'stakeholder' && $this->hasRole('Stakeholder');
    }

    /**
     * Check if user is general user.
     */
    public function isGeneralUser(): bool
    {
        return $this->user_type === 'general' && $this->hasRole('General User');
    }

    /**
     * Check if general user is PRO.
     */
    public function isProUser(): bool
    {
        return $this->is_pro
            && $this->pro_expired_at
            && $this->pro_expired_at->isFuture();
    }

    /**
     * Check if user account is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if user has pending activation.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
    
    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope users by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('user_type', $type);
    }

    /**
     * Scope active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope pending users.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope users in a pesantren.
     */
    public function scopeInPesantren($query, int $pesantrenId)
    {
        return $query->where('pesantren_id', $pesantrenId);
    }

    /**
     * Scope general users only.
     */
    public function scopeGeneralUsers($query)
    {
        return $query->whereNull('pesantren_id')
            ->where('user_type', 'general');
    }

    /**
     * Scope PRO users.
     */
    public function scopeProUsers($query)
    {
        return $query->where('is_pro', true)
            ->where('pro_expired_at', '>', now());
    }
}
