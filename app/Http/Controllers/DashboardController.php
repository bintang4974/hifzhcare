<?php

namespace App\Http\Controllers;

use App\Models\{Pesantren, User, Hafalan, Certificate};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard based on user role.
     * Redirect to dedicated dashboard controllers.
     */
    public function index()
    {
        $user = auth()->user();

        return match ($user->user_type) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin' => $this->adminDashboard(),
            'ustadz' => $this->ustadzDashboard(),
            'santri' => $this->santriDashboard(),
            'wali' => $this->waliDashboard(),
            'stakeholder' => redirect()->route('stakeholder.dashboard'),
            'general' => $this->generalUserDashboard(),
            default => view('dashboard.default'),
        };
    }

    /**
     * Admin Pesantren Dashboard.
     */
    protected function adminDashboard()
    {
        $pesantren = auth()->user()->pesantren;

        if (!$pesantren) {
            abort(403, 'Anda tidak terdaftar di pesantren manapun.');
        }

        $stats = [
            'total_santri' => $pesantren->current_santri_count ?? 0,
            'max_santri' => $pesantren->max_santri ?? 0,
            'quota_percentage' => $pesantren->max_santri > 0 
                ? round(($pesantren->current_santri_count / $pesantren->max_santri) * 100)
                : 0,
            'total_ustadz' => $pesantren->users()->where('user_type', 'ustadz')->count(),
            'total_classes' => $pesantren->classes()->count(),
            'active_classes' => $pesantren->classes()->where('status', 'active')->count(),
            'total_hafalan' => $pesantren->hafalans()->count(),
            'pending_hafalan' => $pesantren->hafalans()->where('status', 'pending')->count(),
            'verified_hafalan' => $pesantren->hafalans()->where('status', 'verified')->count(),
            'pending_certificates' => $pesantren->certificates()->where('status', 'pending')->count(),
            'pending_funds' => $pesantren->appreciationFunds()
                ->where('status', 'pending')
                ->sum('amount'),
        ];

        // Recent hafalans
        $recentHafalans = $pesantren->hafalans()
            ->latest()
            ->take(10)
            ->with(['user', 'verifiedBy.user'])
            ->get();

        // Pending certificates
        $pendingCertificates = $pesantren->certificates()
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->with(['user'])
            ->get();

        // Monthly progress
        $monthlyProgress = $this->getMonthlyProgress($pesantren->id);

        return view('dashboard.admin', compact(
            'stats', 
            'recentHafalans', 
            'pendingCertificates', 
            'monthlyProgress', 
            'pesantren'
        ));
    }

    /**
     * Ustadz Dashboard.
     */
    protected function ustadzDashboard()
    {
        $ustadz = auth()->user()->ustadzProfile;

        if (!$ustadz) {
            abort(403, 'Profile ustadz tidak ditemukan.');
        }

        $stats = [
            'total_classes' => $ustadz->activeClasses->count(),
            'total_students' => $ustadz->activeClasses->sum('current_student_count'),
            'pending_hafalan' => Hafalan::whereHas('class.activeUstadz', function($q) use ($ustadz) {
                    $q->where('ustadz_profile_id', $ustadz->id);
                })
                ->where('status', 'pending')
                ->count(),
            'verified_today' => $ustadz->verifiedHafalans()
                ->whereDate('verified_at', today())
                ->count(),
            'total_appreciation' => $ustadz->appreciationFunds()
                ->where('status', 'verified')
                ->sum('amount'),
        ];

        // My classes
        $myClasses = $ustadz->activeClasses()
            ->with(['activeSantri.user'])
            ->get();

        // Pending hafalans from my classes
        $pendingHafalans = Hafalan::whereHas('class.activeUstadz', function($q) use ($ustadz) {
                $q->where('ustadz_profile_id', $ustadz->id);
            })
            ->where('status', 'pending')
            ->latest()
            ->take(10)
            ->with(['user', 'class'])
            ->get();

        // Recent verified
        $recentVerified = $ustadz->verifiedHafalans()
            ->latest('verified_at')
            ->take(10)
            ->with(['user', 'class'])
            ->get();

        return view('dashboard.ustadz', compact(
            'stats', 
            'myClasses', 
            'pendingHafalans', 
            'recentVerified'
        ));
    }

    /**
     * Santri Dashboard.
     */
    protected function santriDashboard()
    {
        $santri = auth()->user()->santriProfile;

        if (!$santri) {
            abort(403, 'Profile santri tidak ditemukan.');
        }

        $stats = [
            'total_juz_completed' => $santri->total_juz_completed ?? 0,
            'total_ayat_completed' => $santri->total_ayat_completed ?? 0,
            'progress_percentage' => $santri->progress_percentage ?? 0,
            'total_hafalan' => $santri->hafalans()->count(),
            'pending_hafalan' => $santri->hafalans()->where('status', 'pending')->count(),
            'verified_hafalan' => $santri->hafalans()->where('status', 'verified')->count(),
        ];

        // My classes
        $myClasses = $santri->activeClasses()
            ->with(['activeUstadz.user'])
            ->get();

        // Recent hafalans
        $recentHafalans = $santri->hafalans()
            ->latest()
            ->take(10)
            ->with(['verifiedBy.user'])
            ->get();

        // Progress by juz
        $progressByJuz = $this->getJuzProgress($santri->id);

        return view('dashboard.santri', compact(
            'stats', 
            'myClasses', 
            'recentHafalans', 
            'progressByJuz'
        ));
    }

    /**
     * Wali Dashboard.
     */
    protected function waliDashboard()
    {
        $wali = auth()->user()->waliProfile;

        if (!$wali) {
            abort(403, 'Profile wali tidak ditemukan.');
        }

        $stats = [
            'total_children' => $wali->santris()->count(),
            'total_donations' => $wali->appreciationFundDonations()
                ->where('status', 'verified')
                ->sum('amount'),
        ];

        // My children
        $children = $wali->santris()
            ->with(['user', 'activeClasses'])
            ->get();

        // Children statistics
        $childrenStats = $children->map(function ($child) {
            return [
                'santri' => $child,
                'total_hafalan' => $child->hafalans()->count(),
                'verified_hafalan' => $child->hafalans()->where('status', 'verified')->count(),
                'progress_percentage' => $child->progress_percentage ?? 0,
            ];
        });

        // Recent donations
        $recentDonations = $wali->appreciationFundDonations()
            ->latest()
            ->take(5)
            ->with(['verifiedBy.user', 'user'])
            ->get();

        return view('dashboard.wali', compact(
            'stats', 
            'childrenStats', 
            'recentDonations'
        ));
    }

    /**
     * General User Dashboard.
     */
    protected function generalUserDashboard()
    {
        $profile = auth()->user()->generalUserProfile;

        if (!$profile) {
            abort(403, 'Profile general user tidak ditemukan.');
        }

        $stats = [
            'total_juz_completed' => $profile->total_juz_completed ?? 0,
            'total_ayat_completed' => $profile->total_ayat_completed ?? 0,
            'progress_percentage' => $profile->progress_percentage ?? 0,
            'current_streak' => $profile->current_streak_days ?? 0,
            'longest_streak' => $profile->longest_streak_days ?? 0,
            'is_pro' => auth()->user()->isProUser(),
        ];

        // Recent hafalans
        $recentHafalans = $profile->hafalans()
            ->latest()
            ->take(10)
            ->get();

        // Progress by juz
        $progressByJuz = $this->getJuzProgress($profile->id);

        // Active targets
        $activeTargets = $profile->targets()
            ->where('status', 'active')
            ->get();

        return view('dashboard.general', compact(
            'stats', 
            'recentHafalans', 
            'progressByJuz', 
            'activeTargets'
        ));
    }

    /**
     * Get monthly progress for pesantren.
     */
    protected function getMonthlyProgress(int $pesantrenId)
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'hafalans' => Hafalan::where('pesantren_id', $pesantrenId)
                    ->whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'verified' => Hafalan::where('pesantren_id', $pesantrenId)
                    ->whereYear('verified_at', $date->year)
                    ->whereMonth('verified_at', $date->month)
                    ->where('status', 'verified')
                    ->count(),
            ];
        }
        return $months;
    }

    /**
     * Get juz progress for santri.
     */
    protected function getJuzProgress(int $santriProfileId)
    {
        $progress = [];
        for ($juz = 1; $juz <= 30; $juz++) {
            $verified = Hafalan::where('santri_profile_id', $santriProfileId)
                ->where('juz_number', $juz)
                ->where('status', 'verified')
                ->count();

            $progress[] = [
                'juz' => $juz,
                'count' => $verified,
                'percentage' => min(100, $verified * 10), // Simplified
            ];
        }
        return $progress;
    }
}
