<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Certificate extends Model
{
    use HasFactory, SoftDeletes, BelongsToTenant, Auditable;

    protected $fillable = [
        'pesantren_id',
        'certificate_template_id',
        'user_id',
        'certificate_number',
        'type',
        'juz_completed',
        'metadata_json',
        'status',
        'approved_by_ustadz_id',
        'approved_at',
        'generated_file_path',
        'issued_at',
    ];

    protected $casts = [
        'metadata_json' => 'array',
        'approved_at' => 'datetime',
        'issued_at' => 'datetime',
        'juz_completed' => 'integer',
    ];

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(CertificateTemplate::class, 'certificate_template_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(UstadzProfile::class, 'approved_by_ustadz_id');
    }

    public function getFileUrlAttribute(): ?string
    {
        return $this->generated_file_path
            ? Storage::disk('public')->url($this->generated_file_path)
            : null;
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending' => '<span class="badge bg-warning">Menunggu Persetujuan</span>',
            'approved' => '<span class="badge bg-info">Disetujui - Sedang Diproses</span>',
            'rejected' => '<span class="badge bg-danger">Ditolak</span>',
            'issued' => '<span class="badge bg-success">Sudah Diterbitkan</span>',
            default => '<span class="badge bg-secondary">' . ucfirst($this->status) . '</span>',
        };
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeIssued($query)
    {
        return $query->where('status', 'issued');
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}
