<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;

class AdminDonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Admin Pesantren');
    }

    /**
     * Show withdrawal requests
     */
    public function index()
    {
        $pesantrenId = auth()->user()->pesantren_id;

        // Pending donations (need admin verification)
        $pendingDonations = Donation::where('pesantren_id', $pesantrenId)
            ->where('status', 'pending')
            ->with(['ustadz.user', 'wali.user', 'pesantren.donationSettings'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Pending withdrawal requests (verified donations, ustadz requesting payout)
        $pendingRequests = Donation::where('pesantren_id', $pesantrenId)
            ->where('status', 'requested')
            ->with(['ustadz.user', 'wali.user'])
            ->orderBy('requested_at', 'asc')
            ->get();

        // Recently disbursed
        $recentDisbursed = Donation::where('pesantren_id', $pesantrenId)
            ->where('status', 'disbursed')
            ->with(['ustadz.user', 'disbursedBy'])
            ->orderByDesc('disbursed_at')
            ->limit(10)
            ->get();

        // Statistics
        $stats = [
            'pending_count' => $pendingDonations->count(),
            'pending_amount' => $pendingDonations->sum('amount'),
            'withdrawal_count' => $pendingRequests->count(),
            'withdrawal_amount' => $pendingRequests->sum('ustadz_net_amount'),
            'total_disbursed' => Donation::where('pesantren_id', $pesantrenId)
                ->where('status', 'disbursed')
                ->sum('ustadz_net_amount'),
            'pesantren_balance' => Donation::where('pesantren_id', $pesantrenId)
                ->whereIn('status', ['verified', 'requested', 'disbursed'])
                ->sum('pesantren_fee'),
        ];

        return view('donations.admin.index', compact('pendingDonations', 'pendingRequests', 'recentDisbursed', 'stats'));
    }
    /**
     * Approve withdrawal request
     */
    public function approve(Request $request, $id)
    {
        $donation = Donation::where('pesantren_id', auth()->user()->pesantren_id)
            ->findOrFail($id);

        if (!$donation->isRequested()) {
            return redirect()->back()
                ->with('error', 'Donasi ini tidak dalam status permintaan pencairan.');
        }

        $request->validate([
            'disbursement_notes' => 'nullable|string|max:500',
        ]);

        // Approve disbursement
        $donation->disburse(auth()->id(), $request->disbursement_notes);

        return redirect()->back()
            ->with('success', 'Pencairan dana berhasil disetujui! Dana dapat diserahkan ke ustadz.');
    }

    /**
     * Show donation detail
     */
    public function show($id)
    {
        $donation = Donation::where('pesantren_id', auth()->user()->pesantren_id)
            ->with(['ustadz.user', 'wali.user', 'disbursedBy', 'pesantren.donationSettings'])
            ->findOrFail($id);

        return view('donations.admin.show', compact('donation'));
    }
}
