<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationSetting;
use App\Models\UstadzProfile;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Wali Santri');
    }

    /**
     * Show donation form
     */
    public function create()
    {
        $wali = auth()->user()->waliProfile;

        if (!$wali) {
            return redirect()->route('dashboard')
                ->with('error', 'Profil wali tidak ditemukan.');
        }

        // Get ustadz who teach wali's children
        $santriIds = $wali->santriProfiles->pluck('id');

        $ustadzList = UstadzProfile::whereHas('activeClassesRelation.santriProfiles', function ($query) use ($santriIds) {
            $query->whereIn('santri_profiles.id', $santriIds);
        })
            ->with(['user', 'activeClassesRelation.santriProfiles'])
            ->get()
            ->unique('id');

        // Get donation settings
        $settings = DonationSetting::where('pesantren_id', auth()->user()->pesantren_id)
            ->first();

        if (!$settings || !$settings->donation_enabled) {
            return redirect()->route('dashboard')
                ->with('error', 'Fitur donasi belum diaktifkan untuk pesantren ini.');
        }

        return view('donations.wali.create', compact('ustadzList', 'settings'));
    }

    /**
     * Store donation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ustadz_id' => 'required|exists:ustadz_profiles,id',
            'santri_id' => 'nullable|exists:santri_profiles,id',
            'amount' => 'required|numeric|min:10000',
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'notes' => 'nullable|string|max:500',
        ], [
            'amount.min' => 'Minimal donasi adalah Rp 10.000',
            'payment_proof.required' => 'Bukti transfer wajib diupload',
        ]);

        $wali = auth()->user()->waliProfile;

        // Get ustadz
        $ustadz = UstadzProfile::findOrFail($validated['ustadz_id']);

        // If santri_id not provided, get first santri from wali's children who is in ustadz's classes
        $santriId = $validated['santri_id'] ?? null;
        if (!$santriId) {
            $santriId = $wali->santriProfiles()
                ->whereHas('activeClasses', function ($query) use ($ustadz) {
                    $query->whereHas('ustadzProfiles', function ($q) use ($ustadz) {
                        $q->where('ustadz_profiles.id', $ustadz->id);
                    });
                })
                ->first()
                ?->id;
        }

        // Upload payment proof
        $proofPath = $request->file('payment_proof')->store('donations/proofs', 'public');

        // Create donation
        $donation = Donation::create([
            'wali_id' => $wali->id,
            'ustadz_id' => $validated['ustadz_id'],
            'santri_id' => $santriId,
            'pesantren_id' => auth()->user()->pesantren_id,
            'amount' => $validated['amount'],
            'payment_method' => 'transfer',
            'payment_proof' => $proofPath,
            'notes' => $validated['notes'],
            'status' => 'pending',
        ]);

        return redirect()->route('donations.show', $donation->id)
            ->with('success', 'Donasi berhasil dikirim! Menunggu verifikasi dari admin.');
    }

    /**
     * Show donation history
     */
    public function index()
    {
        $wali = auth()->user()->waliProfile;

        $donations = Donation::where('wali_id', $wali->id)
            ->with(['ustadz.user', 'verifiedBy'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('donations.wali.index', compact('donations'));
    }

    /**
     * Show donation detail
     */
    public function show($id)
    {
        $donation = Donation::with(['ustadz.user', 'verifiedBy', 'transferredBy'])
            ->where('wali_id', auth()->user()->waliProfile->id)
            ->findOrFail($id);

        return view('donations.wali.show', compact('donation'));
    }
}
