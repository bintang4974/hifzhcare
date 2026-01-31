<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    /**
     * The table doesn't have updated_at column.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'pesantren_id',
        'user_id',
        'event',
        'auditable_type',
        'auditable_id',
        'old_values_json',
        'new_values_json',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values_json' => 'array',
        'new_values_json' => 'array',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the auditable model (polymorphic).
     */
    public function auditable()
    {
        return $this->morphTo();
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getEventLabelAttribute(): string
    {
        return match ($this->event) {
            'created' => 'Dibuat',
            'updated' => 'Diubah',
            'deleted' => 'Dihapus',
            'verified' => 'Diverifikasi',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            default => ucfirst($this->event),
        };
    }

    public function getModelNameAttribute(): string
    {
        $class = class_basename($this->auditable_type);

        return match ($class) {
            'Hafalan' => 'Hafalan',
            'User' => 'Pengguna',
            'Certificate' => 'Sertifikat',
            'Payment' => 'Pembayaran',
            'AppreciationFund' => 'Dana Apresiasi',
            default => $class,
        };
    }

    // ============================================
    // SCOPES
    // ============================================

    public function scopeForModel($query, string $modelType, int $modelId)
    {
        return $query->where('auditable_type', $modelType)
            ->where('auditable_id', $modelId);
    }

    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByEvent($query, string $event)
    {
        return $query->where('event', $event);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
