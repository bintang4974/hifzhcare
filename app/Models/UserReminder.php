<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'general_user_profile_id',
        'reminder_type',
        'reminder_time',
        'days_of_week',
        'is_active',
    ];

    protected $casts = [
        'days_of_week' => 'array',
        'is_active' => 'boolean',
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
        return match ($this->reminder_type) {
            'murajah' => "Muraja'ah",
            'daily_target' => 'Target Harian',
            default => ucfirst($this->reminder_type),
        };
    }

    public function getDaysLabelAttribute(): string
    {
        if (empty($this->days_of_week)) {
            return 'Setiap Hari';
        }

        $dayNames = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $selectedDays = array_map(fn($day) => $dayNames[$day] ?? $day, $this->days_of_week);

        return implode(', ', $selectedDays);
    }

    public function getShouldTriggerTodayAttribute(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        $today = now()->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

        if (empty($this->days_of_week)) {
            return true; // Every day
        }

        return in_array($today, $this->days_of_week);
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('reminder_type', $type);
    }

    public function scopeForToday($query)
    {
        return $query->where('is_active', true)
            ->where(function ($q) {
                $today = now()->dayOfWeek;
                $q->whereNull('days_of_week')
                    ->orWhereJsonContains('days_of_week', $today);
            });
    }
}
