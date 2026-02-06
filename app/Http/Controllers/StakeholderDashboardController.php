<?php

namespace App\Http\Controllers;

use App\Models\{AppreciationFund, SantriProfile, UstadzProfile, Certificate, Classes, Hafalan};
use Illuminate\Http\Request;

class StakeholderDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant');
        $this->middleware('role:stakeholder');
    }

    /**
     * Display stakeholder dashboard
     */
    public function index()
    {
        $pesantrenId = session('current_pesantren_id');

        // Executive KPIs
        $kpis = [
            'total_santri' => SantriProfile::where('pesantren_id', $pesantrenId)->count(),
            'active_santri' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereHas('user', function ($q) {
                    $q->where('status', 'active');
                })->count(),
            'total_ustadz' => UstadzProfile::where('pesantren_id', $pesantrenId)->count(),
            'total_classes' => Classes::where('pesantren_id', $pesantrenId)->count(),
            'total_hafalan_verified' => Hafalan::whereHas('user', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->where('status', 'verified')->count(),
            'certificates_issued' => Certificate::where('pesantren_id', $pesantrenId)->count(),
            'total_donations' => AppreciationFund::whereHas('wali', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->where('status', 'verified')->sum('amount'),
            'completion_rate' => $this->getCompletionRate($pesantrenId),
        ];

        // Calculate trends (compared to last month)
        $trends = $this->calculateTrends($pesantrenId);

        // Santri Progress Chart Data
        $progressData = $this->getSantriProgressData($pesantrenId);

        // Top Performers
        $topPerformers = $this->getTopPerformers($pesantrenId);

        // Class Performance
        $classPerformance = $this->getClassPerformance($pesantrenId);

        // Monthly Hafalan Trend
        $hafalanTrend = $this->getHafalanTrend($pesantrenId);

        // Financial Summary
        $financialSummary = $this->getFinancialSummary($pesantrenId);

        // Recent Certificates
        $recentCertificates = Certificate::where('pesantren_id', $pesantrenId)
            ->with(['user', 'pesantren'])
            ->latest()
            ->take(5)
            ->get();

        // Students Needing Attention (low progress, inactive)
        $studentsNeedingAttention = $this->getStudentsNeedingAttention($pesantrenId);

        return view('dashboards.stakeholder', compact(
            'kpis',
            'trends',
            'progressData',
            'topPerformers',
            'classPerformance',
            'hafalanTrend',
            'financialSummary',
            'recentCertificates',
            'studentsNeedingAttention'
        ));
    }

    /**
     * Calculate completion rate
     */
    protected function getCompletionRate($pesantrenId)
    {
        $totalSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();

        if ($totalSantri == 0) return 0;

        $completedSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('progress_percentage', '>=', 100)
            ->count();

        return round(($completedSantri / $totalSantri) * 100, 2);
    }

    /**
     * Calculate trends compared to last month
     */
    protected function calculateTrends($pesantrenId)
    {
        $currentMonth = now();
        $lastMonth = now()->subMonth();

        // Santri trend
        $currentSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereYear('created_at', $currentMonth->year)
            ->whereMonth('created_at', $currentMonth->month)
            ->count();

        $lastMonthSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereYear('created_at', $lastMonth->year)
            ->whereMonth('created_at', $lastMonth->month)
            ->count();

        // Hafalan trend
        $currentHafalan = Hafalan::whereHas('user', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })
            ->where('status', 'verified')
            ->whereYear('verified_at', $currentMonth->year)
            ->whereMonth('verified_at', $currentMonth->month)
            ->count();

        $lastMonthHafalan = Hafalan::whereHas('user', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })
            ->where('status', 'verified')
            ->whereYear('verified_at', $lastMonth->year)
            ->whereMonth('verified_at', $lastMonth->month)
            ->count();

        return [
            'santri_change' => $this->calculatePercentageChange($lastMonthSantri, $currentSantri),
            'hafalan_change' => $this->calculatePercentageChange($lastMonthHafalan, $currentHafalan),
        ];
    }

    /**
     * Calculate percentage change
     */
    protected function calculatePercentageChange($old, $new)
    {
        if ($old == 0) return $new > 0 ? 100 : 0;
        return round((($new - $old) / $old) * 100, 1);
    }

    /**
     * Get santri progress distribution
     */
    protected function getSantriProgressData($pesantrenId)
    {
        $ranges = [
            '0-25%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('progress_percentage', [0, 25])->count(),
            '26-50%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('progress_percentage', [26, 50])->count(),
            '51-75%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('progress_percentage', [51, 75])->count(),
            '76-100%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->where('progress_percentage', '>=', 76)->count(),
        ];

        return [
            'labels' => array_keys($ranges),
            'data' => array_values($ranges),
        ];
    }

    /**
     * Get top performing students
     */
    protected function getTopPerformers($pesantrenId)
    {
        return SantriProfile::where('pesantren_id', $pesantrenId)
            ->with(['user'])
            ->withCount(['hafalans as verified_hafalans' => function ($q) {
                $q->where('status', 'verified');
            }])
            ->orderBy('progress_percentage', 'desc')
            ->orderBy('verified_hafalans', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get class performance
     */
    protected function getClassPerformance($pesantrenId)
    {
        return Classes::where('pesantren_id', $pesantrenId)
            ->with(['activeSantri'])
            ->withCount('activeSantri')
            ->get()
            ->map(function ($class) {
                $avgProgress = $class->activeSantri->avg('progress_percentage') ?? 0;
                $totalVerified = Hafalan::whereHas('santri', function ($q) use ($class) {
                    $q->whereIn('id', $class->activeSantri->pluck('id'));
                })->where('status', 'verified')->count();

                return [
                    'name' => $class->name,
                    'total_santri' => $class->active_santri_count,
                    'avg_progress' => round($avgProgress, 2),
                    'total_verified' => $totalVerified,
                ];
            });
    }

    /**
     * Get hafalan trend (last 6 months)
     */
    protected function getHafalanTrend($pesantrenId)
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Hafalan::whereHas('santri', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })
                ->where('status', 'verified')
                ->whereYear('verified_at', $date->year)
                ->whereMonth('verified_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data,
        ];
    }

    /**
     * Get financial summary
     */
    protected function getFinancialSummary($pesantrenId)
    {
        $thisMonth = AppreciationFund::whereHas('wali', function ($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })
            ->where('status', 'verified')
            ->whereYear('verified_at', now()->year)
            ->whereMonth('verified_at', now()->month)
            ->sum('amount');

        $lastMonth = AppreciationFund::whereHas('wali', function ($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })
            ->where('status', 'verified')
            ->whereYear('verified_at', now()->subMonth()->year)
            ->whereMonth('verified_at', now()->subMonth()->month)
            ->sum('amount');

        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change' => $this->calculatePercentageChange($lastMonth, $thisMonth),
            'total_year' => AppreciationFund::whereHas('wali', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })
                ->where('status', 'verified')
                ->whereYear('verified_at', now()->year)
                ->sum('amount'),
        ];
    }

    /**
     * Get students needing attention
     */
    protected function getStudentsNeedingAttention($pesantrenId)
    {
        return SantriProfile::where('pesantren_id', $pesantrenId)
            ->with(['user'])
            ->where(function ($q) {
                // Low progress OR no recent activity
                $q->where('progress_percentage', '<', 20)
                    ->orWhereDoesntHave('hafalans', function ($q2) {
                        $q2->where('created_at', '>=', now()->subDays(30));
                    });
            })
            ->orderBy('progress_percentage', 'asc')
            ->take(10)
            ->get();
    }

    /**
     * Export report
     */
    public function exportReport(Request $request)
    {
        $type = $request->get('type', 'monthly');
        $pesantrenId = session('current_pesantren_id');

        // Generate report data based on type
        $data = $this->generateReportData($pesantrenId, $type);

        // Return as PDF or Excel based on request
        // Implementation depends on your preferred library

        return response()->json([
            'success' => true,
            'message' => 'Report exported successfully',
            'data' => $data
        ]);
    }

    /**
     * Generate report data
     */
    protected function generateReportData($pesantrenId, $type)
    {
        // Implementation for different report types
        return [
            'type' => $type,
            'generated_at' => now(),
            'pesantren_id' => $pesantrenId,
        ];
    }
}
