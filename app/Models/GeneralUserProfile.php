<?php

namespace App\Models;

use App\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralUserProfile extends Model
{
    use HasFactory, SoftDeletes, Auditable;

    protected $fillable = [
        'user_id',
        'total_juz_completed',
        'total_ayat_completed',
        'current_streak_days',
        'longest_streak_days',
        'last_hafalan_date',
    ];

    protected $casts = [
        'last_hafalan_date' => 'date',
        'total_juz_completed' => 'integer',
        'total_ayat_completed' => 'integer',
        'current_streak_days' => 'integer',
        'longest_streak_days' => 'integer',
    ];

    // Relationships

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function hafalans(): HasMany
    {
        return $this->hasMany(Hafalan::class, 'user_id', 'user_id');
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'user_id', 'user_id');
    }

    public function targets(): HasMany
    {
        return $this->hasMany(UserTarget::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(UserReminder::class);
    }

    // Accessors

    public function getNameAttribute(): string
    {
        return $this->user->name;
    }

    public function getProgressPercentageAttribute(): float
    {
        return round(($this->total_juz_completed / 30) * 100, 2);
    }

    public function getIsStreakActiveAttribute(): bool
    {
        if (!$this->last_hafalan_date) {
            return false;
        }

        // Streak is active if last hafalan was today or yesterday
        return $this->last_hafalan_date->isToday()
            || $this->last_hafalan_date->isYesterday();
    }

    // Scopes

    public function scopeWithActiveStreak($query)
    {
        return $query->whereNotNull('last_hafalan_date')
            ->where('last_hafalan_date', '>=', now()->subDay()->startOfDay());
    }

    public function scopeProUsers($query)
    {
        return $query->whereHas('user', function ($q) {
            $q->where('is_pro', true)
                ->where('pro_expired_at', '>', now());
        });
    }
}
