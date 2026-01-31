<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_user_profile_id',
        'target_type',
        'target_ayat_count',
        'target_juz_count',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'target_ayat_count' => 'integer',
        'target_juz_count' => 'integer',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function generalUserProfile(): BelongsTo
    {
        return $this->belongsTo(GeneralUserProfile::class);
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getTypeLabelAttribute(): string
    {
        return match ($this->target_type) {
            'daily' => 'Harian',
            'weekly' => 'Mingguan',
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => ucfirst($this->target_type),
        };
    }

    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active'
            && $this->start_date->isPast()
            && $this->end_date->isFuture();
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getRemainingDaysAttribute(): int
    {
        if (!$this->is_active) {
            return 0;
        }

        return max(0, now()->diffInDays($this->end_date, false));
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('target_type', $type);
    }
}
