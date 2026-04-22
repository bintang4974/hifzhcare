<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationSetting;
use Illuminate\Http\Request;

class UstadzDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Ustadz');
    }
 
    /**
     * Show balance dashboard
     */
    public function balance()
    {
        $ustadz = auth()->user()->ustadzProfile;
 
        if (!$ustadz) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil ustadz tidak ditemukan.');
        }
 
        // Available balance (status: available, verified, or transferred)
        $availableBalance = Donation::where('ustadz_id', $ustadz->id)
            ->whereIn('status', ['available', 'verified', 'transferred'])
            ->sum('ustadz_net_amount');
 
        // Pending withdrawal (status: requested)
        $pendingWithdrawal = Donation::where('ustadz_id', $ustadz->id)
            ->where('status', 'requested')
            ->sum('ustadz_net_amount');
 
        // Total received (all verified and beyond)
        $totalReceived = Donation::where('ustadz_id', $ustadz->id)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
            ->sum('ustadz_net_amount');
 
        // Total disbursed
        $totalDisbursed = Donation::where('ustadz_id', $ustadz->id)
            ->where('status', 'disbursed')
            ->sum('ustadz_net_amount');
 
        // All donations
        $donations = Donation::where('ustadz_id', $ustadz->id)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
            ->with(['wali.user'])
            ->orderByDesc('created_at')
            ->paginate(10);
 
        // Statistics
        $stats = [
            'available_balance' => $availableBalance,
            'pending_withdrawal' => $pendingWithdrawal,
            'total_received' => $totalReceived,
            'total_disbursed' => $totalDisbursed,
            'donation_count' => $donations->total(),
        ];
 
        return view('donations.ustadz.balance', compact('donations', 'stats'));
    }
 
    /**
     * Show withdrawal form
     */
    public function withdrawForm()
    {
        $ustadz = auth()->user()->ustadzProfile;
 
        $availableBalance = Donation::where('ustadz_id', $ustadz->id)
            ->whereIn('status', ['available', 'verified', 'transferred'])
            ->sum('ustadz_net_amount');
 
        $settings = DonationSetting::where('pesantren_id', auth()->user()->pesantren_id)
            ->first();
 
        $minimumWithdrawal = $settings->minimum_withdrawal ?? 50000;
 
        if ($availableBalance < $minimumWithdrawal) {
            return redirect()->route('ustadz.donations.balance')
                ->with('error', 'Saldo Anda belum mencapai minimal penarikan (Rp ' . number_format($minimumWithdrawal, 0, ',', '.') . ')');
        }
 
        return view('donations.ustadz.withdraw', compact('availableBalance', 'minimumWithdrawal'));
    }
 
    /**
     * Request withdrawal
     */
    public function requestWithdrawal(Request $request)
    {
        $request->validate([
            'request_notes' => 'nullable|string|max:500',
        ]);
 
        $ustadz = auth()->user()->ustadzProfile;
 
        // Get all available donations
        $availableDonations = Donation::where('ustadz_id', $ustadz->id)
            ->whereIn('status', ['available', 'verified', 'transferred'])
            ->get();
 
        if ($availableDonations->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Tidak ada saldo yang dapat ditarik.');
        }
 
        // Check minimum withdrawal
        $settings = DonationSetting::where('pesantren_id', auth()->user()->pesantren_id)
            ->first();
        
        $totalAmount = $availableDonations->sum('ustadz_net_amount');
        $minimumWithdrawal = $settings->minimum_withdrawal ?? 50000;
 
        if ($totalAmount < $minimumWithdrawal) {
            return redirect()->back()
                ->with('error', 'Saldo belum mencapai minimal penarikan.');
        }
 
        // Mark all as requested
        foreach ($availableDonations as $donation) {
            $donation->requestWithdrawal($request->request_notes);
        }
 
        return redirect()->route('ustadz.donations.balance')
            ->with('success', 'Permintaan pencairan berhasil dikirim! Silahkan hubungi admin pondok untuk pencairan.');
    }
}
