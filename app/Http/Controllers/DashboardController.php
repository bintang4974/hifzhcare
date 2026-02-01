<?php

namespace App\Http\Controllers;

use App\Models\{Pesantren, User, Hafalan};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display dashboard based on user role.
     */
    public function index()
    {
        $user = auth()->user();

        return match ($user->user_type) {
            'super_admin' => $this->superAdminDashboard(),
            'admin' => $this->adminDashboard(),
            'ustadz' => $this->ustadzDashboard(),
            'santri' => $this->santriDashboard(),
            'wali' => $this->waliDashboard(),
            'stakeholder' => $this->stakeholderDashboard(),
            'general' => $this->generalUserDashboard(),
            default => view('dashboard.default'),
        };
    }

    /**
     * Super Admin Dashboard.
     */
    protected function superAdminDashboard()
    {
        $stats = [
            'total_pesantren' => Pesantren::count(),
            'active_pesantren' => Pesantren::where('status', 'active')->count(),
            'total_users' => User::count(),
            'total_santri' => User::where('user_type', 'santri')->count(),
            'total_hafalan' => Hafalan::count(),
            'verified_hafalan' => Hafalan::where('status', 'verified')->count(),
        ];

        // Recent activities
        $recentPesantren = Pesantren::latest()->take(5)->get();
        $recentUsers = User::latest()->take(10)->get();

        // Monthly statistics
        $monthlyStats = $this->getMonthlyStatistics();

        return view('dashboard.super-admin', compact('stats', 'recentPesantren', 'recentUsers', 'monthlyStats'));
    }

    /**
     * Admin Pesantren Dashboard.
     */
    protected function adminDashboard()
    {
        $pesantren = auth()->user()->pesantren;

        $stats = [
            'total_santri' => $pesantren->current_santri_count,
            'max_santri' => $pesantren->max_santri,
            'quota_percentage' => round(($pesantren->current_santri_count / $pesantren->max_santri) * 100),
            'total_ustadz' => $pesantren->users()->where('user_type', 'ustadz')->count(),
            'total_classes' => $pesantren->classes()->count(),
            'active_classes' => $pesantren->classes()->where('status', 'active')->count(),
            'total_hafalan' => $pesantren->hafalans()->count(),
            'pending_hafalan' => $pesantren->hafalans()->where('status', 'pending')->count(),
            'verified_hafalan' => $pesantren->hafalans()->where('status', 'verified')->count(),
            'pending_certificates' => $pesantren->certificates()->where('status', 'pending')->count(),
            'pending_funds' => $pesantren->appreciationFunds()->where('status', 'pending')->sum('amount'),
        ];

        // Recent activities
        $recentHafalans = $pesantren->hafalans()->latest()->take(10)->with(['user', 'class'])->get();
        $pendingCertificates = $pesantren->certificates()->where('status', 'pending')->latest()->take(5)->get();

        // Monthly progress
        $monthlyProgress = $this->getMonthlyProgress($pesantren->id);

        return view('dashboard.admin', compact('stats', 'recentHafalans', 'pendingCertificates', 'monthlyProgress', 'pesantren'));
    }

    /**
     * Ustadz Dashboard.
     */
    protected function ustadzDashboard()
    {
        $ustadz = auth()->user()->ustadzProfile;

        $stats = [
            'total_classes' => $ustadz->activeClasses->count(),
            'total_students' => $ustadz->activeClasses->sum('current_student_count'),
            'pending_hafalan' => $ustadz->verifiedHafalans()->where('status', 'pending')->count(),
            'verified_today' => $ustadz->verifiedHafalans()
                ->whereDate('verified_at', today())
                ->count(),
            'total_appreciation' => $ustadz->total_appreciation_received,
        ];

        // My classes
        $myClasses = $ustadz->activeClasses()->with(['activeSantri'])->get();

        // Pending hafalans
        $pendingHafalans = Hafalan::whereHas('class.activeUstadz', function ($q) use ($ustadz) {
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

        return view('dashboard.ustadz', compact('stats', 'myClasses', 'pendingHafalans', 'recentVerified'));
    }

    /**
     * Santri Dashboard.
     */
    protected function santriDashboard()
    {
        $santri = auth()->user()->santriProfile;

        $stats = [
            'total_juz_completed' => $santri->total_juz_completed,
            'total_ayat_completed' => $santri->total_ayat_completed,
            'progress_percentage' => $santri->progress_percentage,
            'total_hafalan' => $santri->hafalans()->count(),
            'pending_hafalan' => $santri->hafalans()->where('status', 'pending')->count(),
            'verified_hafalan' => $santri->hafalans()->where('status', 'verified')->count(),
        ];

        // My classes
        $myClasses = $santri->activeClasses()->with(['activeUstadz.user'])->get();

        // Recent hafalans
        $recentHafalans = $santri->hafalans()->latest()->take(10)->with(['verifiedBy', 'class'])->get();

        // Progress by juz
        $progressByJuz = $this->getJuzProgress($santri->user_id);

        return view('dashboard.santri', compact('stats', 'myClasses', 'recentHafalans', 'progressByJuz'));
    }

    /**
     * Wali Dashboard.
     */
    protected function waliDashboard()
    {
        $wali = auth()->user()->waliProfile;

        $stats = [
            'total_children' => $wali->santriProfiles()->count(),
            'total_donations' => $wali->total_funds_donated,
        ];

        // My children
        $children = $wali->santriProfiles()->with(['user', 'activeClasses'])->get();

        // Children statistics
        $childrenStats = $children->map(function ($child) {
            return [
                'santri' => $child,
                'total_hafalan' => $child->hafalans()->count(),
                'verified_hafalan' => $child->hafalans()->where('status', 'verified')->count(),
                'progress_percentage' => $child->progress_percentage,
            ];
        });

        // Recent donations
        $recentDonations = $wali->appreciationFunds()->latest()->take(5)->with(['ustadz.user', 'santri.user'])->get();

        return view('dashboard.wali', compact('stats', 'childrenStats', 'recentDonations'));
    }

    /**
     * Stakeholder Dashboard.
     */
    protected function stakeholderDashboard()
    {
        return $this->adminDashboard(); // Similar view with admin
    }

    /**
     * General User Dashboard.
     */
    protected function generalUserDashboard()
    {
        $profile = auth()->user()->generalUserProfile;

        $stats = [
            'total_juz_completed' => $profile->total_juz_completed,
            'total_ayat_completed' => $profile->total_ayat_completed,
            'progress_percentage' => $profile->progress_percentage,
            'current_streak' => $profile->current_streak_days,
            'longest_streak' => $profile->longest_streak_days,
            'is_pro' => auth()->user()->isProUser(),
        ];

        // Recent hafalans
        $recentHafalans = $profile->hafalans()->latest()->take(10)->get();

        // Progress by juz
        $progressByJuz = $this->getJuzProgress(auth()->id());

        // Active targets
        $activeTargets = $profile->targets()->where('status', 'active')->get();

        return view('dashboard.general', compact('stats', 'recentHafalans', 'progressByJuz', 'activeTargets'));
    }

    /**
     * Get monthly statistics.
     */
    protected function getMonthlyStatistics()
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = [
                'month' => $date->format('M Y'),
                'hafalans' => Hafalan::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count(),
                'verified' => Hafalan::whereYear('verified_at', $date->year)
                    ->whereMonth('verified_at', $date->month)
                    ->where('status', 'verified')
                    ->count(),
            ];
        }
        return $months;
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
     * Get juz progress for user.
     */
    protected function getJuzProgress(int $userId)
    {
        $progress = [];
        for ($juz = 1; $juz <= 30; $juz++) {
            $verified = Hafalan::where('user_id', $userId)
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
