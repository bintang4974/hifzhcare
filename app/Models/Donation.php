<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;
 
    protected $fillable = [
        'wali_id',
        'ustadz_id',
        'pesantren_id',
        'donation_code',
        'amount',
        'platform_fee',
        'pesantren_fee',
        'ustadz_net_amount',
        'transfer_to_pesantren',
        'payment_method',
        'payment_proof',
        'notes',
        'status',
        'verified_by',
        'verified_at',
        'verification_notes',
        'transferred_by',
        'transferred_at',
        'transfer_proof',
        'requested_at',
        'request_notes',
        'disbursed_by',
        'disbursed_at',
        'disbursement_notes',
    ];
 
    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'pesantren_fee' => 'decimal:2',
        'ustadz_net_amount' => 'decimal:2',
        'transfer_to_pesantren' => 'decimal:2',
        'verified_at' => 'datetime',
        'transferred_at' => 'datetime',
        'requested_at' => 'datetime',
        'disbursed_at' => 'datetime',
    ];
 
    // Relationships
    public function wali()
    {
        return $this->belongsTo(WaliProfile::class, 'wali_id');
    }
 
    public function ustadz()
    {
        return $this->belongsTo(UstadzProfile::class, 'ustadz_id');
    }
 
    public function pesantren()
    {
        return $this->belongsTo(Pesantren::class);
    }
 
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
 
    public function transferredBy()
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
 
    public function disbursedBy()
    {
        return $this->belongsTo(User::class, 'disbursed_by');
    }
 
    // Boot method for auto-calculations
    protected static function boot()
    {
        parent::boot();
 
        static::creating(function ($donation) {
            // Generate donation code
            $donation->donation_code = self::generateDonationCode();
            
            // Calculate fees
            $platformFeePercent = $donation->pesantren->donationSettings->platform_fee_percentage ?? 3;
            $pesantrenFeePercent = $donation->pesantren->donationSettings->pesantren_fee_percentage ?? 10;
            
            $donation->platform_fee = ($donation->amount * $platformFeePercent) / 100;
            $donation->pesantren_fee = ($donation->amount * $pesantrenFeePercent) / 100;
            $donation->ustadz_net_amount = $donation->amount - $donation->platform_fee - $donation->pesantren_fee;
            $donation->transfer_to_pesantren = $donation->amount - $donation->platform_fee;
        });
    }
 
    // Generate unique donation code
    public static function generateDonationCode()
    {
        $year = date('Y');
        $lastDonation = self::whereYear('created_at', $year)
            ->orderBy('id', 'desc')
            ->first();
        
        $number = $lastDonation ? intval(substr($lastDonation->donation_code, -4)) + 1 : 1;
        
        return 'DON-' . $year . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
 
    // Status helpers
    public function isPending()
    {
        return $this->status === 'pending';
    }
 
    public function isVerified()
    {
        return $this->status === 'verified';
    }
 
    public function isTransferred()
    {
        return $this->status === 'transferred';
    }
 
    public function isAvailable()
    {
        return $this->status === 'available';
    }
 
    public function isRequested()
    {
        return $this->status === 'requested';
    }
 
    public function isDisbursed()
    {
        return $this->status === 'disbursed';
    }
 
    public function isRejected()
    {
        return $this->status === 'rejected';
    }
 
    // Action methods
    public function verify($userId, $notes = null)
    {
        $this->update([
            'status' => 'verified',
            'verified_by' => $userId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }
 
    public function markTransferred($userId, $proofPath = null)
    {
        $this->update([
            'status' => 'transferred',
            'transferred_by' => $userId,
            'transferred_at' => now(),
            'transfer_proof' => $proofPath,
        ]);
    }
 
    public function markAvailable()
    {
        $this->update(['status' => 'available']);
    }
 
    public function requestWithdrawal($notes = null)
    {
        $this->update([
            'status' => 'requested',
            'requested_at' => now(),
            'request_notes' => $notes,
        ]);
    }
 
    public function disburse($userId, $notes = null)
    {
        $this->update([
            'status' => 'disbursed',
            'disbursed_by' => $userId,
            'disbursed_at' => now(),
            'disbursement_notes' => $notes,
        ]);
    }
 
    public function reject($userId, $notes)
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $userId,
            'verified_at' => now(),
            'verification_notes' => $notes,
        ]);
    }
}
