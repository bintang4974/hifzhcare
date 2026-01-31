<?php

namespace App\Models;

use App\Auditable;
use App\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Payment extends Model
{
    use HasFactory, BelongsToTenant, Auditable;

    protected $fillable = [
        'pesantren_id',
        'subscription_id',
        'payment_number',
        'invoice_number',
        'payment_method',
        'amount',
        'status',
        'proof_of_payment_path',
        'midtrans_order_id',
        'midtrans_transaction_id',
        'midtrans_response_json',
        'verified_by_user_id',
        'verified_at',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'midtrans_response_json' => 'array',
        'verified_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function pesantren(): BelongsTo
    {
        return $this->belongsTo(Pesantren::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function getProofUrlAttribute(): ?string
    {
        return $this->proof_of_payment_path
            ? Storage::disk('public')->url($this->proof_of_payment_path)
            : null;
    }

    public function getAmountFormattedAttribute(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 'success');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }
}
