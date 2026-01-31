<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use App\Support\Helpers\QuranHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hafalan extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'pesantren_id',
        'class_id',
        'user_id',
        'created_by_user_id',
        'surah_number',
        'ayat_start',
        'ayat_end',
        'juz_number',
        'type',
        'status',
        'notes',
        'verified_by_ustadz_id',
        'verified_at',
        'hafalan_date',
    ];

    protected $casts = [
        'hafalan_date' => 'date',
        'verified_at' => 'datetime',
        'surah_number' => 'integer',
        'ayat_start' => 'integer',
        'ayat_end' => 'integer',
        'juz_number' => 'integer',
    ];

    protected $with = ['audios']; // Eager load audio by default

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(UstadzProfile::class, 'verified_by_ustadz_id');
    }

    public function audios(): HasMany
    {
        return $this->hasMany(HafalanAudio::class);
    }

    // ============================================
    // ACCESSORS & MUTATORS
    // ============================================

    public function getAyatCountAttribute(): int
    {
        return $this->ayat_end - $this->ayat_start + 1;
    }

    public function getSurahNameAttribute(): string
    {
        return QuranHelper::getSurahName($this->surah_number);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Pending</span>',
            'verified' => '<span class="badge bg-success">Verified</span>',
            'rejected' => '<span class="badge bg-danger">Rejected</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'setoran' => 'Setoran',
            'murajah' => "Muraja'ah",
            default => ucfirst($this->type),
        };
    }

    public function getIsVerifiedAttribute(): bool
    {
        return $this->status === 'verified';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getHasAudioAttribute(): bool
    {
        return $this->audios()->exists();
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeByJuz($query, int $juz)
    {
        return $query->where('juz_number', $juz);
    }

    public function scopeBySurah($query, int $surah)
    {
        return $query->where('surah_number', $surah);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByDateRange($query, $from, $to)
    {
        return $query->whereBetween('hafalan_date', [$from, $to]);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByClass($query, int $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeCreatedBy($query, int $userId)
    {
        return $query->where('created_by_user_id', $userId);
    }

    public function scopeVerifiedBy($query, int $ustadzId)
    {
        return $query->where('verified_by_ustadz_id', $ustadzId);
    }

    public function scopeWithAudio($query)
    {
        return $query->whereHas('audios');
    }
}
