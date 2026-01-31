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

class Classes extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $table = 'classes';

    protected $fillable = [
        'pesantren_id',
        'name',
        'code',
        'description',
        'status',
        'max_capacity',
        'current_student_count',
    ];

    protected $casts = [
        'max_capacity' => 'integer',
        'current_student_count' => 'integer',
    ];
    
    // ============================================
    // RELATIONSHIPS
    // ============================================

    /**
     * Get the pesantren.
     */
    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    /**
     * Get all ustadz teaching this class (many-to-many).
     */
    public function ustadzProfiles(): BelongsToMany
    {
        return $this->belongsToMany(UstadzProfile::class, 'class_ustadz', 'class_id', 'ustadz_profile_id')
            ->withPivot(['assigned_date', 'status'])
            ->withTimestamps();
    }

    /**
     * Get active ustadz only.
     */
    public function activeUstadz(): BelongsToMany
    {
        return $this->ustadzProfiles()->wherePivot('status', 'active');
    }

    /**
     * Get all santri in this class (many-to-many).
     */
    public function santriProfiles(): BelongsToMany
    {
        return $this->belongsToMany(SantriProfile::class, 'class_santri', 'class_id', 'santri_profile_id')
            ->withPivot(['enrolled_date', 'status'])
            ->withTimestamps();
    }

    /**
     * Get active santri only.
     */
    public function activeSantri(): BelongsToMany
    {
        return $this->santriProfiles()->wherePivot('status', 'active');
    }

    /**
     * Get all hafalans in this class.
     */
    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'class_id');
    }
    
    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    /**
     * Get available seats.
     */
    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->max_capacity - $this->current_student_count);
    }

    /**
     * Check if class is full.
     */
    public function getIsFullAttribute(): bool
    {
        return $this->current_student_count >= $this->max_capacity;
    }

    /**
     * Check if class is active.
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active';
    }
    
    // ============================================
    // METHODS
    // ============================================

    /**
     * Assign ustadz to this class.
     */
    public function assignUstadz(UstadzProfile $ustadz, ?string $assignedDate = null): void
    {
        $this->ustadzProfiles()->attach($ustadz->id, [
            'pesantren_id' => $this->pesantren_id,
            'assigned_date' => $assignedDate ?? now(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Remove ustadz from this class.
     */
    public function removeUstadz(UstadzProfile $ustadz): void
    {
        $this->ustadzProfiles()->detach($ustadz->id);
    }

    /**
     * Enroll santri to this class.
     */
    public function enrollSantri(SantriProfile $santri, ?string $enrolledDate = null): void
    {
        if ($this->is_full) {
            throw new \Exception('Kelas sudah penuh. Tidak dapat menambah santri.');
        }

        $this->santriProfiles()->attach($santri->id, [
            'pesantren_id' => $this->pesantren_id,
            'enrolled_date' => $enrolledDate ?? now(),
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->increment('current_student_count');
    }

    /**
     * Remove santri from this class.
     */
    public function removeSantri(SantriProfile $santri): void
    {
        $this->santriProfiles()->detach($santri->id);
        $this->decrement('current_student_count');
    }

    /**
     * Graduate santri from this class.
     */
    public function graduateSantri(SantriProfile $santri): void
    {
        $this->santriProfiles()->updateExistingPivot($santri->id, [
            'status' => 'graduated',
            'updated_at' => now(),
        ]);

        $this->decrement('current_student_count');
    }
    
    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope active classes.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope classes with available seats.
     */
    public function scopeWithAvailableSeats($query)
    {
        return $query->whereRaw('current_student_count < max_capacity');
    }

    /**
     * Scope classes taught by specific ustadz.
     */
    public function scopeTaughtBy($query, int $ustadzProfileId)
    {
        return $query->whereHas('ustadzProfiles', function ($q) use ($ustadzProfileId) {
            $q->where('ustadz_profile_id', $ustadzProfileId)
                ->where('class_ustadz.status', 'active');
        });
    }

    /**
     * Scope classes with specific santri.
     */
    public function scopeWithSantri($query, int $santriProfileId)
    {
        return $query->whereHas('santriProfiles', function ($q) use ($santriProfileId) {
            $q->where('santri_profile_id', $santriProfileId)
                ->where('class_santri.status', 'active');
        });
    }
}
