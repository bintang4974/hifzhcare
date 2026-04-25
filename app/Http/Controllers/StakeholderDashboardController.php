<?php

namespace App\Http\Controllers;

use App\Models\AppreciationFund;
use App\Models\Certificate;
use App\Models\Classes;
use App\Models\Donation;
use App\Models\Hafalan;
use App\Models\SantriProfile;
use App\Models\UstadzProfile;
use Illuminate\Http\Request;

class StakeholderDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('tenant');
        $this->middleware('role:Stakeholder');
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
            'total_hafalan_verified' => Hafalan::where('pesantren_id', $pesantrenId)
                ->where('status', 'verified')
                ->count(),
            'certificates_issued' => Certificate::where('pesantren_id', $pesantrenId)->count(),
            'total_donations' => Donation::where('pesantren_id', $pesantrenId)
                ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
                ->sum('amount'),
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
            ->whereHas('user')
            ->with(['user', 'pesantren'])
            ->latest()
            ->take(5)
            ->get();

        // Students Needing Attention (low progress, inactive)
        $studentsNeedingAttention = $this->getStudentsNeedingAttention($pesantrenId);

        return view('dashboard.stakeholder', compact(
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

        if ($totalSantri == 0) {
            return 0;
        }

        $completedSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('total_juz_completed', '>=', 30)
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
        $currentHafalan = Hafalan::where('pesantren_id', $pesantrenId)
            ->where('status', 'verified')
            ->whereYear('verified_at', $currentMonth->year)
            ->whereMonth('verified_at', $currentMonth->month)
            ->count();

        $lastMonthHafalan = Hafalan::where('pesantren_id', $pesantrenId)
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
        if ($old == 0) {
            return $new > 0 ? 100 : 0;
        }

        return round((($new - $old) / $old) * 100, 1);
    }

    /**
     * Get santri progress distribution
     */
    protected function getSantriProgressData($pesantrenId)
    {
        $ranges = [
            '0-25%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('total_juz_completed', [0, 7])->count(),
            '26-50%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('total_juz_completed', [8, 15])->count(),
            '51-75%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('total_juz_completed', [16, 22])->count(),
            '76-100%' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->where('total_juz_completed', '>=', 23)->count(),
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
            ->whereHas('user')
            ->with(['user'])
            ->withCount(['hafalans as verified_hafalans' => function ($q) {
                $q->where('status', 'verified');
            }])
            ->orderBy('total_juz_completed', 'desc')
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
                $avgJuzCompleted = $class->activeSantri->avg('total_juz_completed') ?? 0;
                $avgProgress = round(($avgJuzCompleted / 30) * 100, 2);
                $totalVerified = Hafalan::whereIn('user_id', $class->activeSantri->pluck('user_id'))
                    ->where('status', 'verified')
                    ->count();

                return [
                    'name' => $class->name,
                    'total_santri' => $class->active_santri_count,
                    'avg_progress' => $avgProgress,
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

            $count = Hafalan::where('pesantren_id', $pesantrenId)
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
        $thisMonth = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $lastMonth = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
            ->whereYear('created_at', now()->subMonth()->year)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->sum('amount');

        return [
            'this_month' => $thisMonth,
            'last_month' => $lastMonth,
            'change' => $this->calculatePercentageChange($lastMonth, $thisMonth),
            'total_year' => Donation::where('pesantren_id', $pesantrenId)
                ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];
    }

    /**
     * Get students needing attention
     */
    protected function getStudentsNeedingAttention($pesantrenId)
    {
        return SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereHas('user')
            ->with(['user'])
            ->where(function ($q) {
                // Low progress (less than 20% = less than 6 juz) OR no recent activity
                $q->where('total_juz_completed', '<', 6)
                    ->orWhereDoesntHave('hafalans', function ($q2) {
                        $q2->where('created_at', '>=', now()->subDays(30));
                    });
            })
            ->orderBy('total_juz_completed', 'asc')
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
            'data' => $data,
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

    /**
     * Trend Analysis Report
     */
    public function trendAnalysis()
    {
        $pesantrenId = auth()->user()->pesantren_id;

        // Calculate trends for the last 12 months
        $trends = $this->calculateTrendAnalysis($pesantrenId);
        
        // Generate chart data
        $chartData = $this->generateChartData($pesantrenId);
        
        // Generate insights
        $insights = $this->generateInsights($trends, $chartData, $pesantrenId);

        // Generate recommendations
        $recommendations = $this->generateRecommendations($trends, $chartData, $pesantrenId);

        return view('reports.trend-analysis', compact('trends', 'chartData', 'insights', 'recommendations'));
    }

    /**
     * Calculate comprehensive trend analysis
     */
    protected function calculateTrendAnalysis($pesantrenId)
    {
        $now = now();
        $lastYear = now()->subYear();

        // Current period stats
        $currentSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();
        $currentHafalan = Hafalan::where('pesantren_id', $pesantrenId)
            ->where('status', 'verified')
            ->count();
        $currentCertificates = Certificate::where('pesantren_id', $pesantrenId)->count();

        // Last year same period
        $lastYearSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('created_at', '<', $lastYear)
            ->count();

        $lastYearHafalan = Hafalan::where('pesantren_id', $pesantrenId)
            ->where('status', 'verified')
            ->where('verified_at', '<', $lastYear)
            ->count();

        $lastYearCertificates = Certificate::where('pesantren_id', $pesantrenId)
            ->where('issued_at', '<', $lastYear)
            ->count();

        // Growth percentages
        $santriGrowth = $this->calculatePercentageChange($lastYearSantri, $currentSantri);
        $hafalanGrowth = $this->calculatePercentageChange($lastYearHafalan, $currentHafalan);
        $certificateGrowth = $this->calculatePercentageChange($lastYearCertificates, $currentCertificates);

        // New santri this month
        $newSantriThisMonth = SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->count();

        // Active santri
        $activeSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereHas('user', function ($q) {
                $q->where('status', 'active');
            })
            ->count();

        // Alumni (santri no longer active or completed)
        $alumni = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where(function ($q) {
                $q->whereHas('user', function ($q2) {
                    $q2->where('status', 'inactive');
                })
                ->orWhere('total_juz_completed', '>=', 30);
            })
            ->count();

        // Monthly average hafalan
        $allHafalans = Hafalan::where('pesantren_id', $pesantrenId)->count();
        $monthlyAvg = $allHafalans > 0 ? round($allHafalans / 12, 0) : 0;

        // Peak month (highest submissions)
        $peakMonthData = Hafalan::where('pesantren_id', $pesantrenId)
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->orderByDesc('count')
            ->first();
        $peakMonth = $peakMonthData ? date('M', mktime(0, 0, 0, $peakMonthData->month, 1)) : 'N/A';

        // Verification rate
        $totalHafalans = Hafalan::where('pesantren_id', $pesantrenId)->count();
        $verificationRate = $totalHafalans > 0 ? round(($currentHafalan / $totalHafalans) * 100, 1) : 0;

        // Certificate types
        $perJuzCerts = Certificate::where('pesantren_id', $pesantrenId)
            ->where('type', '!=', 'khatam')
            ->count();
        $khatamCerts = Certificate::where('pesantren_id', $pesantrenId)
            ->where('type', 'khatam')
            ->count();

        // Student progress stats
        $studentProgresses = SantriProfile::where('pesantren_id', $pesantrenId)
            ->pluck('total_juz_completed');
        $highestProgress = $studentProgresses->count() > 0 
            ? round((max($studentProgresses->toArray()) / 30) * 100, 1) 
            : 0;
        $lowestProgress = $studentProgresses->count() > 0 
            ? round((min($studentProgresses->toArray()) / 30) * 100, 1) 
            : 0;

        // Average progress
        $avgProgress = SantriProfile::where('pesantren_id', $pesantrenId)
            ->avg('total_juz_completed') ?? 0;
        $avgProgress = round(($avgProgress / 30) * 100, 1);

        return [
            'total_santri' => $currentSantri,
            'santri_growth' => $santriGrowth,
            'total_hafalan' => $currentHafalan,
            'hafalan_growth' => $hafalanGrowth,
            'total_certificates' => $currentCertificates,
            'certificate_growth' => $certificateGrowth,
            'new_santri' => $newSantriThisMonth,
            'active_santri' => $activeSantri,
            'alumni' => $alumni,
            'monthly_avg' => $monthlyAvg,
            'peak_month' => $peakMonth,
            'verification_rate' => $verificationRate,
            'per_juz_certs' => $perJuzCerts,
            'khatam_certs' => $khatamCerts,
            'highest_progress' => $highestProgress,
            'lowest_progress' => $lowestProgress,
            'avg_progress' => $avgProgress,
            'pesantren_name' => auth()->user()->pesantren->name ?? 'Pesantren',
        ];
    }

    /**
     * Generate chart data for trend analysis
     */
    protected function generateChartData($pesantrenId)
    {
        $months = [];
        $santriGrowth = [];
        $hafalanSubmitted = [];
        $hafalanVerified = [];
        $certificates = [];
        $avgProgress = [];

        // Get data for last 12 months
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Santri growth per month
            $santriCount = SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $santriGrowth[] = $santriCount;

            // Hafalan submitted
            $hafalanCount = Hafalan::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $hafalanSubmitted[] = $hafalanCount;

            // Hafalan verified
            $verifiedCount = Hafalan::where('pesantren_id', $pesantrenId)
                ->where('status', 'verified')
                ->whereYear('verified_at', $date->year)
                ->whereMonth('verified_at', $date->month)
                ->count();
            $hafalanVerified[] = $verifiedCount;

            // Certificates issued
            $certCount = Certificate::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $certificates[] = $certCount;

            // Average progress
            $avgProg = SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', '<=', $date->year)
                ->where(function ($q) use ($date) {
                    if ($date->month > 1) {
                        $q->orWhereMonth('created_at', '<=', $date->month);
                    }
                })
                ->avg('total_juz_completed') ?? 0;
            $avgProgress[] = round(($avgProg / 30) * 100, 1);
        }

        // Get class performance data
        $classes = Classes::where('pesantren_id', $pesantrenId)->get();
        $classNames = $classes->pluck('name')->toArray();
        $classProgress = [];
        $classHafalan = [];

        foreach ($classes as $class) {
            $avgJuzCompleted = $class->activeSantri->avg('total_juz_completed') ?? 0;
            $progress = round(($avgJuzCompleted / 30) * 100, 1);
            $classProgress[] = $progress;

            $totalVerified = Hafalan::whereIn('user_id', $class->activeSantri->pluck('user_id'))
                ->where('status', 'verified')
                ->count();
            $classHafalan[] = $totalVerified;
        }

        return [
            'months' => $months,
            'santri_growth' => $santriGrowth,
            'hafalan_submitted' => $hafalanSubmitted,
            'hafalan_verified' => $hafalanVerified,
            'certificates' => $certificates,
            'avg_progress' => $avgProgress,
            'class_names' => $classNames,
            'class_progress' => $classProgress,
            'class_hafalan' => $classHafalan,
        ];
    }

    /**
     * Generate insights based on trends and chart data
     */
    protected function generateInsights($trends, $chartData, $pesantrenId)
    {
        $insights = [];

        // Insight 1: Santri Growth
        if ($trends['santri_growth'] > 0) {
            $insights[] = [
                'title' => 'Pertumbuhan Santri Positif',
                'description' => 'Pertumbuhan santri meningkat ' . $trends['santri_growth'] . '% dibanding tahun lalu',
                'icon' => 'arrow-up',
                'color' => 'green',
            ];
        } elseif ($trends['santri_growth'] < 0) {
            $insights[] = [
                'title' => 'Penurunan Santri',
                'description' => 'Perlu perhatian: santri menurun ' . abs($trends['santri_growth']) . '%',
                'icon' => 'arrow-down',
                'color' => 'red',
            ];
        }

        // Insight 2: Hafalan Progress
        if ($trends['verification_rate'] > 80) {
            $insights[] = [
                'title' => 'Tingkat Verifikasi Tinggi',
                'description' => 'Proses verifikasi hafalan sangat baik dengan rate ' . $trends['verification_rate'] . '%',
                'icon' => 'check-circle',
                'color' => 'green',
            ];
        } elseif ($trends['verification_rate'] < 50) {
            $insights[] = [
                'title' => 'Verifikasi Tertinggal',
                'description' => 'Tingkat verifikasi baru ' . $trends['verification_rate'] . '%, perlu ditingkatkan',
                'icon' => 'exclamation-triangle',
                'color' => 'yellow',
            ];
        }

        // Insight 3: Progress Distribution
        $progressGap = $trends['highest_progress'] - $trends['lowest_progress'];
        if ($progressGap > 50) {
            $insights[] = [
                'title' => 'Kesenjangan Progress Besar',
                'description' => 'Ada perbedaan signifikan ' . $progressGap . '% antara santri tercepat dan terlambat',
                'icon' => 'exclamation-circle',
                'color' => 'yellow',
            ];
        }

        // Insight 4: Certificate Achievement
        $totalCerts = $trends['per_juz_certs'] + $trends['khatam_certs'];
        if ($totalCerts > 0) {
            $khatamRate = round(($trends['khatam_certs'] / $totalCerts) * 100, 1);
            if ($khatamRate > 30) {
                $insights[] = [
                    'title' => 'Pencapaian Khatam Baik',
                    'description' => $khatamRate . '% santri sudah menyelesaikan hafalan 30 juz',
                    'icon' => 'star',
                    'color' => 'blue',
                ];
            }
        }

        // Insight 5: Alumni
        if ($trends['alumni'] > 0) {
            $alumniRate = round(($trends['alumni'] / $trends['total_santri']) * 100, 1);
            $insights[] = [
                'title' => 'Alumni Program',
                'description' => $trends['alumni'] . ' santri (' . $alumniRate . '%) telah lulus dari program',
                'icon' => 'graduation-cap',
                'color' => 'purple',
            ];
        }

        // Insight 6: Active Students
        $activeRate = round(($trends['active_santri'] / $trends['total_santri']) * 100, 1);
        if ($activeRate > 90) {
            $insights[] = [
                'title' => 'Partisipasi Aktif Tinggi',
                'description' => $activeRate . '% santri masih aktif dalam program',
                'icon' => 'users',
                'color' => 'green',
            ];
        } elseif ($activeRate < 70) {
            $insights[] = [
                'title' => 'Partisipasi Menurun',
                'description' => 'Hanya ' . $activeRate . '% santri yang masih aktif, perlu ditingkatkan',
                'icon' => 'alert-circle',
                'color' => 'red',
            ];
        }

        return $insights;
    }

    /**
     * Generate recommendations based on trends and data
     */
    protected function generateRecommendations($trends, $chartData, $pesantrenId)
    {
        $recommendations = [];

        // Recommendation 1: Low Santri Growth
        if ($trends['santri_growth'] < 5) {
            $recommendations[] = [
                'title' => 'Tingkatkan Rekrutmen Santri',
                'action' => 'Pertumbuhan santri lambat. Pertimbangkan program promosi dan penerimaan santri baru untuk meningkatkan jumlah peserta didik.',
            ];
        }

        // Recommendation 2: Low Verification Rate
        if ($trends['verification_rate'] < 70) {
            $recommendations[] = [
                'title' => 'Percepatan Proses Verifikasi Hafalan',
                'action' => 'Tingkat verifikasi masih rendah. Tambah ustadz verifikator atau optimalkan jadwal verifikasi untuk mengurangi backlog.',
            ];
        }

        // Recommendation 3: Low Khatam Rate
        $totalCerts = $trends['per_juz_certs'] + $trends['khatam_certs'];
        if ($totalCerts > 0) {
            $khatamRate = round(($trends['khatam_certs'] / $totalCerts) * 100, 1);
            if ($khatamRate < 20) {
                $recommendations[] = [
                    'title' => 'Dorong Penyelesaian Hafalan',
                    'action' => 'Hanya ' . $khatamRate . '% santri yang menyelesaikan 30 juz. Berikan motivasi dan dukungan tambahan untuk santri yang mendekati khatam.',
                ];
            }
        }

        // Recommendation 4: High Progress Gap
        $progressGap = $trends['highest_progress'] - $trends['lowest_progress'];
        if ($progressGap > 60) {
            $recommendations[] = [
                'title' => 'Program Remedial untuk Santri Tertinggal',
                'action' => 'Ada kesenjangan progress ' . $progressGap . '%. Buat program tambahan untuk membantu santri dengan progress rendah agar tidak tertinggal jauh.',
            ];
        }

        // Recommendation 5: Low Active Rate
        $activeRate = round(($trends['active_santri'] / $trends['total_santri']) * 100, 1);
        if ($activeRate < 80) {
            $recommendations[] = [
                'title' => 'Tingkatkan Partisipasi Santri',
                'action' => 'Partisipasi aktif hanya ' . $activeRate . '%. Identifikasi penyebab dan lakukan intervensi untuk meningkatkan kehadiran dan keterlibatan santri.',
            ];
        }

        // Recommendation 6: Low Average Progress
        if ($trends['avg_progress'] < 30) {
            $recommendations[] = [
                'title' => 'Evaluasi Kurikulum dan Metode Pembelajaran',
                'action' => 'Rata-rata progress santri masih ' . $trends['avg_progress'] . '%. Review efektivitas kurikulum dan metode pembelajaran yang diterapkan.',
            ];
        }

        // Recommendation 7: Uneven Class Performance
        $classProgresses = array_filter(json_decode(json_encode([
            'values' => $chartData['class_progress'] ?? []
        ]))->values ?? []);
        if (count($classProgresses) > 1) {
            $classMax = max($chartData['class_progress'] ?? [0]);
            $classMin = min($chartData['class_progress'] ?? [0]);
            $classGap = $classMax - $classMin;
            if ($classGap > 30) {
                $recommendations[] = [
                    'title' => 'Standarisasi Kualitas Antar Kelas',
                    'action' => 'Ada perbedaan performa ' . $classGap . '% antar kelas. Lakukan peer learning atau mentoring antar ustadz untuk menyetarakan kualitas pembelajaran.',
                ];
            }
        }

        // Recommendation 8: Increase Alumni Programs
        if ($trends['alumni'] > 0 && $trends['alumni'] < 5) {
            $recommendations[] = [
                'title' => 'Program Alumni Berkelanjutan',
                'action' => 'Buat program alumni yang terstruktur untuk tetap menjaga hubungan dengan lulusan dan melibatkan mereka dalam kegiatan pesantren.',
            ];
        }

        // Default recommendation if no specific issues
        if (empty($recommendations)) {
            $recommendations[] = [
                'title' => 'Pertahankan Momentum Positif',
                'action' => 'Tren pesantren menunjukkan performa yang baik. Terus evaluasi dan cari peluang peningkatan untuk mencapai target yang lebih ambisius.',
            ];
        }

        return $recommendations;
    }
}