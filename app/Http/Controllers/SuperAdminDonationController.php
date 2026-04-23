<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class SuperAdminDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super Admin');
    }

    /**
     * Show pending donations for verification
     */
    public function index()
    {
        $pendingDonations = Donation::where('status', 'pending')
            ->with(['wali.user', 'ustadz.user', 'pesantren'])
            ->orderBy('created_at', 'asc')
            ->get();

        $verifiedDonations = Donation::where('status', 'verified')
            ->with(['wali.user', 'ustadz.user', 'pesantren'])
            ->orderByDesc('verified_at')
            ->limit(10)
            ->get();

        return view('donations.superadmin.index', compact('pendingDonations', 'verifiedDonations'));
    }

    /**
     * Verify and approve donation
     */
    public function verify(Request $request, $id)
    {
        $donation = Donation::findOrFail($id);

        if (!$donation->isPending()) {
            return redirect()->back()
                ->with('error', 'Donasi ini sudah diverifikasi atau ditolak.');
        }

        // Verify donation
        $donation->verify(auth()->id(), $request->notes);

        return redirect()->back()
            ->with('success', 'Donasi berhasil diverifikasi! Silahkan transfer ke pesantren.');
    }

    /**
     * Reject donation
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_notes' => 'required|string|max:500',
        ]);

        $donation = Donation::findOrFail($id);

        if (!$donation->isPending()) {
            return redirect()->back()
                ->with('error', 'Donasi ini sudah diverifikasi atau ditolak.');
        }

        // Reject donation
        $donation->reject(auth()->id(), $request->rejection_notes);

        return redirect()->back()
            ->with('success', 'Donasi berhasil ditolak.');
    }

    /**
     * Mark as transferred to pesantren
     */
    public function markTransferred(Request $request, $id)
    {
        $request->validate([
            'transfer_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $donation = Donation::findOrFail($id);

        if (!$donation->isVerified()) {
            return redirect()->back()
                ->with('error', 'Donasi harus diverifikasi terlebih dahulu.');
        }

        // Upload transfer proof if provided
        $proofPath = null;
        if ($request->hasFile('transfer_proof')) {
            $proofPath = $request->file('transfer_proof')->store('donations/transfers', 'public');
        }

        // Mark as transferred
        $donation->markTransferred(auth()->id(), $proofPath);

        // Auto mark as available for ustadz
        $donation->markAvailable();

        return redirect()->back()
            ->with('success', 'Transfer ke pesantren berhasil dikonfirmasi! Dana tersedia untuk ustadz.');
    }

    /**
     * Show transfers page
     */
    public function transfers()
    {
        $toTransfer = Donation::where('status', 'verified')
            ->with(['wali.user', 'ustadz.user', 'pesantren'])
            ->orderBy('verified_at', 'asc')
            ->get();

        $transferred = Donation::whereIn('status', ['transferred', 'available'])
            ->with(['wali.user', 'ustadz.user', 'pesantren', 'transferredBy'])
            ->orderByDesc('transferred_at')
            ->limit(20)
            ->get();

        return view('donations.superadmin.transfers', compact('toTransfer', 'transferred'));
    }

    /**
     * Dashboard statistics
     */
    public function statistics()
    {
        $stats = [
            'total_donations' => Donation::sum('amount'),
            'platform_fees' => Donation::whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
                ->sum('platform_fee'),
            'pending_count' => Donation::where('status', 'pending')->count(),
            'verified_count' => Donation::where('status', 'verified')->count(),
            'monthly_donations' => Donation::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return view('donations.superadmin.statistics', compact('stats'));
    }
}
