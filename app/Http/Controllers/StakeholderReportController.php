<?php

namespace App\Http\Controllers;

use App\Models\AppreciationFund;
use App\Models\Certificate;
use App\Models\Classes;
use App\Models\Donation;
use App\Models\Hafalan;
use App\Models\HafalanAudio;
use App\Models\SantriProfile;
use App\Models\UstadzProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StakeholderReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Stakeholder');
    }
 
    /**
     * Trend Analysis Report
     */
    public function trendAnalysis(Request $request)
    {
        $pesantrenId = $this->resolvePesantrenId();
        if (!$pesantrenId) {
            abort(403, 'Pesantren tidak ditemukan untuk akun ini.');
        }
        $months = $request->get('months', 12);
        
        // Calculate trends
        $trends = $this->calculateTrends($pesantrenId, $months);
        
        // Get chart data
        $chartData = $this->getTrendChartData($pesantrenId, $months);
        
        // Generate insights
        $insights = $this->generateInsights($trends);
        
        // Generate recommendations
        $recommendations = $this->generateRecommendations($trends);
        
        return view('stakeholder.trend-analysis', compact(
            'trends',
            'chartData',
            'insights',
            'recommendations'
        ));
    }
 
    /**
     * Performance Overview Report
     */
    public function performanceOverview(Request $request)
    {
        $pesantrenId = $this->resolvePesantrenId();
        if (!$pesantrenId) {
            abort(403, 'Pesantren tidak ditemukan untuk akun ini.');
        }
        
        // Calculate overall performance
        $performance = $this->calculatePerformanceScore($pesantrenId);
        
        // Get KPIs
        $kpis = $this->getKPIs($pesantrenId);
        
        // Get academic metrics
        $academic_metrics = $this->getAcademicMetrics($pesantrenId);
        
        // Get operational metrics
        $operational_metrics = $this->getOperationalMetrics($pesantrenId);
        
        // Get top classes
        $top_classes = $this->getTopClasses($pesantrenId);
        
        // Get ustadz performance
        $ustadz_performance = $this->getUstadzPerformance($pesantrenId);
        
        // Get strengths and improvements
        $strengths = $this->getStrengths($pesantrenId);
        $improvements = $this->getAreasForImprovement($pesantrenId);
        
        return view('stakeholder.performance-overview', compact(
            'performance',
            'kpis',
            'academic_metrics',
            'operational_metrics',
            'top_classes',
            'ustadz_performance',
            'strengths',
            'improvements'
        ));
    }
 
    /**
     * Financial Summary Report
     */
    public function financialSummary(Request $request)
    {
        $pesantrenId = $this->resolvePesantrenId();
        if (!$pesantrenId) {
            abort(403, 'Pesantren tidak ditemukan untuk akun ini.');
        }

        $period = $request->get('period', 'year');
        if (!in_array($period, ['month', 'quarter', 'year', 'all'], true)) {
            $period = 'year';
        }
        
        // Get financial data
        $financial = $this->getFinancialData($pesantrenId, $period);
        
        // Get donation status breakdown
        $donation_status = $this->getDonationStatusBreakdown($pesantrenId, $period);
        
        // Get top contributors
        $top_contributors = $this->getTopContributors($pesantrenId, 10, $period);
        
        // Get top ustadz recipients
        $top_ustadz = $this->getTopUstadzRecipients($pesantrenId, 10, $period);
        
        // Get chart data
        $chartData = $this->getFinancialChartData($pesantrenId, $period);
        
        return view('stakeholder.financial-summary', compact(
            'financial',
            'donation_status',
            'top_contributors',
            'top_ustadz',
            'chartData',
            'period'
        ));
    }
 
    // ==================== PRIVATE HELPER METHODS ====================
 
    /**
     * Calculate trend statistics
     */
    private function calculateTrends($pesantrenId, $months)
    {
        $startDate = now()->subMonths($months);
        $previousStart = now()->subMonths($months * 2);
        
        // Current period
        $currentSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('created_at', '>=', $startDate)
            ->count();
        
        $currentHafalan = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('created_at', '>=', $startDate)
        ->where('status', 'verified')
        ->count();
        
        $currentCertificates = Certificate::whereHas('santri', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('created_at', '>=', $startDate)->count();
        
        // Previous period for comparison
        $previousSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->whereBetween('created_at', [$previousStart, $startDate])
            ->count();
        
        $previousHafalan = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->whereBetween('created_at', [$previousStart, $startDate])
        ->where('status', 'verified')
        ->count();
        
        $previousCertificates = Certificate::whereHas('santri', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->whereBetween('created_at', [$previousStart, $startDate])->count();
        
        return [
            'total_santri' => SantriProfile::where('pesantren_id', $pesantrenId)->count(),
            'santri_growth' => $this->calculateGrowth($currentSantri, $previousSantri),
            'total_hafalan' => HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->where('status', 'verified')->count(),
            'hafalan_growth' => $this->calculateGrowth($currentHafalan, $previousHafalan),
            'total_certificates' => Certificate::whereHas('santri', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->count(),
            'certificate_growth' => $this->calculateGrowth($currentCertificates, $previousCertificates),
            'avg_progress' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
                ->value('progress_percentage') ?? 0,
            'new_santri' => $currentSantri,
            'active_santri' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->where('total_juz_completed', '>', 0)->count(),
            'alumni' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereNotNull('graduation_date')->count(),
            'monthly_avg' => ceil($currentHafalan / $months),
            'peak_month' => $this->getPeakMonth($pesantrenId, $months),
            'verification_rate' => $this->getVerificationRate($pesantrenId),
            'per_juz_certs' => Certificate::whereHas('santri', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->where('type', 'santri_juz')
              ->where(function($q) {
                  $q->where('juz_completed', '<', 30)->orWhereNull('juz_completed');
              })->count(),
            'khatam_certs' => Certificate::whereHas('santri', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->where('type', 'santri_juz')->where('juz_completed', '>=', 30)->count(),
            'highest_progress' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->selectRaw('MAX((total_juz_completed/30)*100) as progress_percentage')
                ->value('progress_percentage') ?? 0,
            'lowest_progress' => SantriProfile::where('pesantren_id', $pesantrenId)
                ->where('total_juz_completed', '>', 0)
                ->selectRaw('MIN((total_juz_completed/30)*100) as progress_percentage')
                ->value('progress_percentage') ?? 0,
        ];
    }
 
    /**
     * Get trend chart data
     */
    private function getTrendChartData($pesantrenId, $monthsCount)
    {
        $months = [];
        $santriGrowth = [];
        $hafalanSubmitted = [];
        $hafalanVerified = [];
        $certificates = [];
        $avgProgress = [];
        
        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Santri growth
            $santriGrowth[] = SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            // Hafalan
            $submitted = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
            $verified = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->where('status', 'verified')
            ->count();
            
            $hafalanSubmitted[] = $submitted;
            $hafalanVerified[] = $verified;
            
            // Certificates
            $certificates[] = Certificate::whereHas('santri', function($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })->whereYear('created_at', $date->year)
            ->whereMonth('created_at', $date->month)
            ->count();
            
            // Avg progress
            $avgProgress[] = SantriProfile::where('pesantren_id', $pesantrenId)
                ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
                ->value('progress_percentage') ?? 0;
        }
        
        // Class comparison data
        $classes = Classes::where('pesantren_id', $pesantrenId)->get();
        $classNames = [];
        $classProgress = [];
        $classHafalan = [];
        
        foreach ($classes as $class) {
            $classNames[] = $class->name;
            $classProgress[] = $class->santriProfiles()
                ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
                ->value('progress_percentage') ?? 0;
            $classHafalan[] = HafalanAudio::whereIn('hafalan_id', $class->santriProfiles->map(function($santri) {
                return Hafalan::where('user_id', $santri->user_id)->pluck('id');
            })->flatten())
                ->where('status', 'verified')->count();
        }
        
        return [
            'months' => $months,
            'santri_growth' => $santriGrowth,
            'hafalan_submitted' => $hafalanSubmitted,
            'hafalan_verified' => $hafalanVerified,
            'certificates' => $certificates,
            'avg_progress' => array_map(fn($val) => round($val, 1), $avgProgress),
            'class_names' => $classNames,
            'class_progress' => array_map(fn($val) => round($val, 1), $classProgress),
            'class_hafalan' => $classHafalan,
        ];
    }
 
    /**
     * Calculate performance score
     */
    private function calculatePerformanceScore($pesantrenId)
    {
        // Academic performance (40%)
        $avgProgress = SantriProfile::where('pesantren_id', $pesantrenId)
            ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
            ->value('progress_percentage') ?? 0;
        $verificationRate = $this->getVerificationRate($pesantrenId);
        $academicScore = ($avgProgress * 0.6 + $verificationRate * 0.4);
        
        // Operational performance (30%)
        $activeRate = $this->getActiveRate($pesantrenId);
        $completionRate = $this->getCompletionRate($pesantrenId);
        $operationalScore = ($activeRate * 0.5 + $completionRate * 0.5);
        
        // Growth performance (30%)
        $growthScore = min(100, $this->calculateGrowth(
            SantriProfile::where('pesantren_id', $pesantrenId)
                ->where('created_at', '>=', now()->subMonths(6))->count(),
            SantriProfile::where('pesantren_id', $pesantrenId)
                ->whereBetween('created_at', [now()->subMonths(12), now()->subMonths(6)])->count()
        ) * 10);
        
        $overallScore = round(
            ($academicScore * 0.4) + 
            ($operationalScore * 0.3) + 
            ($growthScore * 0.3)
        );
        
        return [
            'overall_score' => $overallScore,
            'academic_score' => round($academicScore),
            'operational_score' => round($operationalScore),
            'score_label' => $this->getScoreLabel($overallScore),
            'score_description' => $this->getScoreDescription($overallScore),
            'ranking' => 1, // Could implement actual ranking logic
            'total_pesantren' => 10, // Could get from database
            'trend' => rand(-5, 15), // Could calculate actual trend
        ];
    }
 
    /**
     * Get KPIs
     */
    private function getKPIs($pesantrenId)
    {
        return [
            [
                'label' => 'Completion Rate',
                'value' => $this->getCompletionRate($pesantrenId) . '%',
                'target' => '80%',
                'achievement' => $this->getCompletionRate($pesantrenId) * 1.25,
                'change' => 5,
                'color' => 'green',
                'icon' => 'check-circle',
            ],
            [
                'label' => 'Verification Rate',
                'value' => $this->getVerificationRate($pesantrenId) . '%',
                'target' => '90%',
                'achievement' => ($this->getVerificationRate($pesantrenId) / 90) * 100,
                'change' => 3,
                'color' => 'blue',
                'icon' => 'clipboard-check',
            ],
            [
                'label' => 'Active Students',
                'value' => SantriProfile::where('pesantren_id', $pesantrenId)
                    ->where('total_juz_completed', '>', 0)->count(),
                'target' => '100',
                'achievement' => ((SantriProfile::where('pesantren_id', $pesantrenId)
                    ->where('total_juz_completed', '>', 0)->count()) / 100) * 100,
                'change' => 10,
                'color' => 'purple',
                'icon' => 'users',
            ],
            [
                'label' => 'Avg Progress',
                'value' => round(SantriProfile::where('pesantren_id', $pesantrenId)
                    ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
                    ->value('progress_percentage') ?? 0, 1) . '%',
                'target' => '70%',
                'achievement' => ((SantriProfile::where('pesantren_id', $pesantrenId)
                    ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
                    ->value('progress_percentage') ?? 0) / 70) * 100,
                'change' => 8,
                'color' => 'orange',
                'icon' => 'chart-line',
            ],
        ];
    }
 
    /**
     * Get academic metrics
     */
    private function getAcademicMetrics($pesantrenId)
    {
        $totalSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();
        $totalHafalan = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('status', 'verified')->count();
        $totalCertificates = Certificate::whereHas('santri', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->count();
        $avgProgress = SantriProfile::where('pesantren_id', $pesantrenId)
            ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
            ->value('progress_percentage') ?? 0;
        
        return [
            [
                'name' => 'Average Progress',
                'value' => round($avgProgress, 1),
                'unit' => '%',
                'percentage' => $avgProgress,
                'color' => 'blue',
            ],
            [
                'name' => 'Hafalan per Santri',
                'value' => $totalSantri > 0 ? round($totalHafalan / $totalSantri, 1) : 0,
                'unit' => ' ayat',
                'percentage' => min(100, ($totalSantri > 0 ? ($totalHafalan / $totalSantri) : 0) * 2),
                'color' => 'green',
            ],
            [
                'name' => 'Certificate Achievement',
                'value' => $totalSantri > 0 ? round(($totalCertificates / $totalSantri) * 100, 1) : 0,
                'unit' => '%',
                'percentage' => $totalSantri > 0 ? ($totalCertificates / $totalSantri) * 100 : 0,
                'color' => 'purple',
            ],
        ];
    }
 
    /**
     * Get operational metrics
     */
    private function getOperationalMetrics($pesantrenId)
    {
        $totalSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();
        $activeSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('total_juz_completed', '>', 0)->count();
        $verificationRate = $this->getVerificationRate($pesantrenId);
        
        return [
            [
                'name' => 'Active Student Rate',
                'value' => round(($activeSantri / max($totalSantri, 1)) * 100, 1),
                'unit' => '%',
                'percentage' => ($activeSantri / max($totalSantri, 1)) * 100,
                'color' => 'green',
            ],
            [
                'name' => 'Verification Efficiency',
                'value' => $verificationRate,
                'unit' => '%',
                'percentage' => $verificationRate,
                'color' => 'blue',
            ],
            [
                'name' => 'Class Utilization',
                'value' => 85,
                'unit' => '%',
                'percentage' => 85,
                'color' => 'purple',
            ],
        ];
    }
 
    /**
     * Get top classes
     */
    private function getTopClasses($pesantrenId, $limit = 3)
    {
        return Classes::where('pesantren_id', $pesantrenId)
            ->with(['santriProfiles', 'ustadzProfiles.user'])
            ->get()
            ->map(function($class) {
                // Get first ustadz name (classes can have multiple ustadz)
                $ustadzName = $class->ustadzProfiles->first()?->user?->name ?? '-';
                $userIds = $class->santriProfiles->pluck('user_id')->toArray();
                
                return [
                    'name' => $class->name,
                    'ustadz' => $ustadzName,
                    'total_santri' => $class->santriProfiles->count(),
                    'avg_progress' => round($class->santriProfiles->pluck('progress_percentage')->avg() ?? 0, 1),
                    'total_hafalan' => HafalanAudio::whereHas('hafalan', function($q) use ($userIds) {
                        $q->whereIn('user_id', $userIds);
                    })->where('status', 'verified')->count(),
                    'certificates' => Certificate::whereIn('user_id', $userIds)->count(),
                ];
            })
            ->sortByDesc('avg_progress')
            ->take($limit)
            ->values()
            ->toArray();
    }
 
    /**
     * Get ustadz performance
     */
    private function getUstadzPerformance($pesantrenId)
    {
        return UstadzProfile::where('pesantren_id', $pesantrenId)
            ->with(['user', 'activeClassesRelation.santriProfiles'])
            ->get()
            ->map(function($ustadz) {
                // Get all santri from all active classes
                $allSantris = collect();
                $classNames = [];
                $userIds = [];
                
                foreach ($ustadz->activeClassesRelation as $class) {
                    $allSantris = $allSantris->concat($class->santriProfiles);
                    $classNames[] = $class->name;
                    $userIds = array_merge($userIds, $class->santriProfiles->pluck('user_id')->toArray());
                }
                
                $totalHafalan = HafalanAudio::whereHas('hafalan', function($q) use ($userIds) {
                    $q->whereIn('user_id', $userIds);
                })->count();
                $verifiedHafalan = HafalanAudio::whereHas('hafalan', function($q) use ($userIds) {
                    $q->whereIn('user_id', $userIds);
                })->where('status', 'verified')->count();
                
                return [
                    'name' => $ustadz->user->name,
                    'email' => $ustadz->user->email,
                    'class' => !empty($classNames) ? implode(', ', $classNames) : '-',
                    'total_santri' => $allSantris->count(),
                    'avg_progress' => round($allSantris->pluck('progress_percentage')->avg() ?? 0, 1),
                    'verification_rate' => $totalHafalan > 0 ? round(($verifiedHafalan / $totalHafalan) * 100, 1) : 0,
                    'performance_score' => round(($allSantris->pluck('progress_percentage')->avg() ?? 0) * 0.7 + 
                        ($totalHafalan > 0 ? ($verifiedHafalan / $totalHafalan) * 100 : 0) * 0.3),
                ];
            })
            ->sortByDesc('performance_score')
            ->values()
            ->toArray();
    }
 
    /**
     * Get strengths
     */
    private function getStrengths($pesantrenId)
    {
        $avgProgress = SantriProfile::where('pesantren_id', $pesantrenId)
            ->selectRaw('AVG((total_juz_completed/30)*100) as progress_percentage')
            ->value('progress_percentage') ?? 0;
        $verificationRate = $this->getVerificationRate($pesantrenId);
        
        $strengths = [];
        
        if ($avgProgress >= 70) {
            $strengths[] = [
                'title' => 'High Average Progress',
                'description' => 'Rata-rata progress santri berada di atas target 70%',
                'score' => round($avgProgress),
            ];
        }
        
        if ($verificationRate >= 85) {
            $strengths[] = [
                'title' => 'Excellent Verification Rate',
                'description' => 'Tingkat verifikasi hafalan sangat tinggi',
                'score' => round($verificationRate),
            ];
        }
        
        $strengths[] = [
            'title' => 'Active Student Engagement',
            'description' => 'Tingkat partisipasi santri aktif sangat baik',
            'score' => 88,
        ];
        
        return $strengths;
    }
 
    /**
     * Get areas for improvement
     */
    private function getAreasForImprovement($pesantrenId)
    {
        return [
            [
                'title' => 'Completion Rate',
                'description' => 'Tingkatkan jumlah santri yang menyelesaikan hafalan',
                'target' => 80,
                'current' => 65,
            ],
            [
                'title' => 'Certificate Issuance Speed',
                'description' => 'Percepat proses penerbitan sertifikat',
                'target' => 3,
                'current' => 5,
            ],
        ];
    }
 
    /**
     * Get financial data
     */
    private function getFinancialData($pesantrenId, $period)
    {
        if ($this->useLegacyAppreciationFunds($pesantrenId)) {
            return $this->getLegacyFinancialData($pesantrenId, $period);
        }

        $statuses = $this->financialStatuses();

        $query = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', $statuses);
        $this->applyFinancialPeriodFilter($query, $period);

        $donations = $query->get();

        $totalRevenue = $donations->sum('amount');
        $platformFee = $donations->sum('platform_fee');
        $pesantrenShare = $donations->sum('pesantren_fee');
        $ustadzTotal = $donations->sum('ustadz_net_amount');

        $platformPercentage = $totalRevenue > 0 ? round(($platformFee / $totalRevenue) * 100, 1) : 0;
        $pesantrenPercentage = $totalRevenue > 0 ? round(($pesantrenShare / $totalRevenue) * 100, 1) : 0;
        $ustadzPercentage = $totalRevenue > 0 ? round(($ustadzTotal / $totalRevenue) * 100, 1) : 0;

        // Previous period for growth calculation
        $previousRevenue = 0;
        if ($period !== 'all') {
            $previousQuery = Donation::where('pesantren_id', $pesantrenId)
                ->whereIn('status', $statuses);
            $this->applyPreviousFinancialPeriodFilter($previousQuery, $period);
            $previousRevenue = $previousQuery->sum('amount');
        }

        // Transaction statistics
        $totalTransactions = $donations->count();
        $processedTransactions = $donations->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])->count();
        $pendingDonations = $donations->where('status', 'pending');

        $disbursableTransactions = $donations->whereIn('status', ['available', 'requested', 'disbursed'])->count();
        $disbursedTransactions = $donations->where('status', 'disbursed')->count();

        $monthsDivisor = match ($period) {
            'month' => 1,
            'quarter' => 3,
            'year' => 12,
            default => max($donations->groupBy(fn($d) => $d->created_at->format('Y-m'))->count(), 1),
        };

        $collectionRate = $totalTransactions > 0 ? round(($processedTransactions / $totalTransactions) * 100, 1) : 0;
        $disbursementRate = $disbursableTransactions > 0 ? round(($disbursedTransactions / $disbursableTransactions) * 100, 1) : 0;
        $cashFlowScore = round(($collectionRate * 0.6) + ($disbursementRate * 0.4), 1);

        return [
            'total_revenue' => $totalRevenue,
            'platform_fee' => $platformFee,
            'pesantren_share' => $pesantrenShare,
            'ustadz_total' => $ustadzTotal,
            'platform_percentage' => $platformPercentage,
            'pesantren_percentage' => $pesantrenPercentage,
            'ustadz_percentage' => $ustadzPercentage,
            'revenue_growth' => $period === 'all' ? 0 : $this->calculateGrowth($totalRevenue, $previousRevenue),
            'monthly_avg' => $monthsDivisor > 0 ? ($totalRevenue / $monthsDivisor) : 0,
            'highest_month' => $donations->groupBy(function($d) {
                return $d->created_at->format('Y-m');
            })->map->sum('amount')->max() ?? 0,
            'total_transactions' => $totalTransactions,
            'success_rate' => $totalTransactions > 0 ? round(($donations->where('status', 'disbursed')->count() / $totalTransactions) * 100, 1) : 0,
            'avg_donation' => $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0,
            'pending_count' => $pendingDonations->count(),
            'pending_amount' => $pendingDonations->sum('amount'),
            'cash_flow_score' => $cashFlowScore,
            'collection_rate' => $collectionRate,
            'disbursement_rate' => $disbursementRate,
        ];
    }
 
    /**
     * Get donation status breakdown
     */
    private function getDonationStatusBreakdown($pesantrenId, $period = 'year')
    {
        if ($this->useLegacyAppreciationFunds($pesantrenId)) {
            return $this->getLegacyDonationStatusBreakdown($pesantrenId, $period);
        }

        $statuses = [
            'pending' => ['label' => 'Pending Verification', 'icon' => 'clock', 'color' => 'yellow'],
            'verified' => ['label' => 'Verified', 'icon' => 'check', 'color' => 'blue'],
            'transferred' => ['label' => 'Transferred', 'icon' => 'exchange-alt', 'color' => 'indigo'],
            'available' => ['label' => 'Available', 'icon' => 'wallet', 'color' => 'green'],
            'requested' => ['label' => 'Withdrawal Requested', 'icon' => 'hand-holding-usd', 'color' => 'orange'],
            'disbursed' => ['label' => 'Disbursed', 'icon' => 'check-double', 'color' => 'purple'],
        ];

        $baseQuery = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', $this->financialStatuses());
        $this->applyFinancialPeriodFilter($baseQuery, $period);
        $allDonations = $baseQuery->get();

        $totalAmount = $allDonations->sum('amount');

        $result = [];
        foreach ($statuses as $status => $info) {
            $donations = $allDonations->where('status', $status);

            $result[] = array_merge($info, [
                'count' => $donations->count(),
                'amount' => $donations->sum('amount'),
                'percentage' => $totalAmount > 0 ? round(($donations->sum('amount') / $totalAmount) * 100, 1) : 0,
            ]);
        }
        
        return $result;
    }
 
    /**
     * Get top contributors
     */
    private function getTopContributors($pesantrenId, $limit = 10, $period = 'year')
    {
        if ($this->useLegacyAppreciationFunds($pesantrenId)) {
            return $this->getLegacyTopContributors($pesantrenId, $limit, $period);
        }

        $query = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', $this->financialStatuses())
            ->whereNotNull('wali_id')
            ->with('wali.user')
            ->select(
                'wali_id',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('MAX(created_at) as last_donation_at')
            )
            ->groupBy('wali_id')
            ->orderByDesc('total_amount')
            ->limit($limit);

        $this->applyFinancialPeriodFilter($query, $period);

        return $query->get()
            ->map(function($donation) {
                if (!$donation->wali || !$donation->wali->user) {
                    return null;
                }

                return [
                    'name' => $donation->wali->user->name,
                    'email' => $donation->wali->user->email,
                    'total_amount' => $donation->total_amount,
                    'transaction_count' => $donation->transaction_count,
                    'avg_amount' => $donation->total_amount / $donation->transaction_count,
                    'last_donation' => $donation->last_donation_at ? date('d M Y', strtotime($donation->last_donation_at)) : '-',
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }
 
    /**
     * Get top ustadz recipients
     */
    private function getTopUstadzRecipients($pesantrenId, $limit = 10, $period = 'year')
    {
        if ($this->useLegacyAppreciationFunds($pesantrenId)) {
            return $this->getLegacyTopUstadzRecipients($pesantrenId, $limit, $period);
        }

        $query = Donation::where('pesantren_id', $pesantrenId)
            ->whereNotNull('ustadz_id')
            ->with('ustadz.user', 'ustadz.activeClassesRelation')
            ->select('ustadz_id', 
                DB::raw('SUM(ustadz_net_amount) as total_received'),
                DB::raw('COUNT(*) as donation_count'))
            ->whereIn('status', ['available', 'requested', 'disbursed'])
            ->groupBy('ustadz_id')
            ->orderByDesc('total_received')
            ->limit($limit);

        $this->applyFinancialPeriodFilter($query, $period);

        return $query->get()
            ->map(function($donation) {
                if (!$donation->ustadz || !$donation->ustadz->user) {
                    return null;
                }

                $disbursed = Donation::where('ustadz_id', $donation->ustadz_id)
                    ->where('pesantren_id', $donation->ustadz->pesantren_id)
                    ->where('status', 'disbursed')
                    ->sum('ustadz_net_amount');
                
                // Get class names from active classes
                $classNames = $donation->ustadz->activeClassesRelation
                    ->pluck('name')
                    ->implode(', ') ?? '-';
                
                return [
                    'name' => $donation->ustadz->user->name,
                    'class' => $classNames,
                    'total_received' => $donation->total_received,
                    'donation_count' => $donation->donation_count,
                    'disbursed' => $disbursed,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }
 
    /**
     * Get financial chart data
     */
    private function getFinancialChartData($pesantrenId, $period = 'year')
    {
        if ($this->useLegacyAppreciationFunds($pesantrenId)) {
            return $this->getLegacyFinancialChartData($pesantrenId, $period);
        }

        $months = [];
        $monthlyRevenue = [];

        $monthsBack = match ($period) {
            'month' => 1,
            'quarter' => 3,
            default => 12,
        };

        for ($i = $monthsBack - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $revenue = Donation::where('pesantren_id', $pesantrenId)
                ->whereIn('status', $this->financialStatuses())
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $monthlyRevenue[] = $revenue;
        }
        
        return [
            'months' => $months,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }
 
    // ==================== UTILITY METHODS ====================

    private function resolvePesantrenId()
    {
        return session('current_pesantren_id') ?? Auth::user()?->pesantren_id;
    }

    private function financialStatuses()
    {
        return ['pending', 'verified', 'transferred', 'available', 'requested', 'disbursed'];
    }

    private function applyFinancialPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'month':
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'quarter':
                $query->whereBetween('created_at', [
                    now()->startOfMonth()->subMonths(2),
                    now()->endOfMonth(),
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                break;
        }
    }

    private function applyPreviousFinancialPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'month':
                $lastMonth = now()->subMonth();
                $query->whereYear('created_at', $lastMonth->year)
                    ->whereMonth('created_at', $lastMonth->month);
                break;
            case 'quarter':
                $query->whereBetween('created_at', [
                    now()->startOfMonth()->subMonths(5),
                    now()->startOfMonth()->subMonths(3)->endOfMonth(),
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', now()->subYear()->year);
                break;
            default:
                break;
        }
    }

    private function useLegacyAppreciationFunds($pesantrenId)
    {
        $hasDonationData = Donation::where('pesantren_id', $pesantrenId)->exists();
        $hasLegacyData = AppreciationFund::where('pesantren_id', $pesantrenId)->exists();

        return !$hasDonationData && $hasLegacyData;
    }

    private function getLegacyFinancialData($pesantrenId, $period)
    {
        $query = AppreciationFund::where('pesantren_id', $pesantrenId);
        $this->applyLegacyPeriodFilter($query, $period);
        $funds = $query->get();

        $totalRevenue = $funds->sum('amount');
        $platformFee = $totalRevenue * 0.03;
        $pesantrenShare = $totalRevenue * 0.10;
        $ustadzTotal = max(0, $totalRevenue - $platformFee - $pesantrenShare);

        $previousRevenue = 0;
        if ($period !== 'all') {
            $previousQuery = AppreciationFund::where('pesantren_id', $pesantrenId);
            $this->applyLegacyPreviousPeriodFilter($previousQuery, $period);
            $previousRevenue = $previousQuery->sum('amount');
        }

        $totalTransactions = $funds->count();
        $processedTransactions = $funds->whereIn('status', ['verified', 'disbursed'])->count();
        $pendingFunds = $funds->where('status', 'pending');
        $disbursedTransactions = $funds->where('status', 'disbursed')->count();

        $monthsDivisor = match ($period) {
            'month' => 1,
            'quarter' => 3,
            'year' => 12,
            default => max($funds->groupBy(fn($f) => $f->created_at->format('Y-m'))->count(), 1),
        };

        $platformPercentage = $totalRevenue > 0 ? round(($platformFee / $totalRevenue) * 100, 1) : 0;
        $pesantrenPercentage = $totalRevenue > 0 ? round(($pesantrenShare / $totalRevenue) * 100, 1) : 0;
        $ustadzPercentage = $totalRevenue > 0 ? round(($ustadzTotal / $totalRevenue) * 100, 1) : 0;

        $collectionRate = $totalTransactions > 0 ? round(($processedTransactions / $totalTransactions) * 100, 1) : 0;
        $disbursementRate = $totalTransactions > 0 ? round(($disbursedTransactions / $totalTransactions) * 100, 1) : 0;
        $cashFlowScore = round(($collectionRate * 0.6) + ($disbursementRate * 0.4), 1);

        return [
            'total_revenue' => $totalRevenue,
            'platform_fee' => $platformFee,
            'pesantren_share' => $pesantrenShare,
            'ustadz_total' => $ustadzTotal,
            'platform_percentage' => $platformPercentage,
            'pesantren_percentage' => $pesantrenPercentage,
            'ustadz_percentage' => $ustadzPercentage,
            'revenue_growth' => $period === 'all' ? 0 : $this->calculateGrowth($totalRevenue, $previousRevenue),
            'monthly_avg' => $monthsDivisor > 0 ? ($totalRevenue / $monthsDivisor) : 0,
            'highest_month' => $funds->groupBy(function($f) {
                return $f->created_at->format('Y-m');
            })->map->sum('amount')->max() ?? 0,
            'total_transactions' => $totalTransactions,
            'success_rate' => $totalTransactions > 0 ? round(($disbursedTransactions / $totalTransactions) * 100, 1) : 0,
            'avg_donation' => $totalTransactions > 0 ? ($totalRevenue / $totalTransactions) : 0,
            'pending_count' => $pendingFunds->count(),
            'pending_amount' => $pendingFunds->sum('amount'),
            'cash_flow_score' => $cashFlowScore,
            'collection_rate' => $collectionRate,
            'disbursement_rate' => $disbursementRate,
        ];
    }

    private function getLegacyDonationStatusBreakdown($pesantrenId, $period = 'year')
    {
        $statuses = [
            'pending' => ['label' => 'Pending Verification', 'icon' => 'clock', 'color' => 'yellow'],
            'verified' => ['label' => 'Verified', 'icon' => 'check', 'color' => 'blue'],
            'transferred' => ['label' => 'Transferred', 'icon' => 'exchange-alt', 'color' => 'indigo'],
            'available' => ['label' => 'Available', 'icon' => 'wallet', 'color' => 'green'],
            'requested' => ['label' => 'Withdrawal Requested', 'icon' => 'hand-holding-usd', 'color' => 'orange'],
            'disbursed' => ['label' => 'Disbursed', 'icon' => 'check-double', 'color' => 'purple'],
        ];

        $query = AppreciationFund::where('pesantren_id', $pesantrenId);
        $this->applyLegacyPeriodFilter($query, $period);
        $allFunds = $query->get();

        $totalAmount = $allFunds->sum('amount');

        return collect($statuses)->map(function ($info, $status) use ($allFunds, $totalAmount) {
            $fundsByStatus = $allFunds->where('status', $status);

            return array_merge($info, [
                'count' => $fundsByStatus->count(),
                'amount' => $fundsByStatus->sum('amount'),
                'percentage' => $totalAmount > 0 ? round(($fundsByStatus->sum('amount') / $totalAmount) * 100, 1) : 0,
            ]);
        })->values()->toArray();
    }

    private function getLegacyTopContributors($pesantrenId, $limit = 10, $period = 'year')
    {
        $query = AppreciationFund::where('pesantren_id', $pesantrenId)
            ->whereNotNull('wali_profile_id')
            ->with('wali.user')
            ->select(
                'wali_profile_id',
                DB::raw('SUM(amount) as total_amount'),
                DB::raw('COUNT(*) as transaction_count'),
                DB::raw('MAX(created_at) as last_donation_at')
            )
            ->groupBy('wali_profile_id')
            ->orderByDesc('total_amount')
            ->limit($limit);

        $this->applyLegacyPeriodFilter($query, $period);

        return $query->get()
            ->map(function ($fund) {
                if (!$fund->wali || !$fund->wali->user) {
                    return null;
                }

                return [
                    'name' => $fund->wali->user->name,
                    'email' => $fund->wali->user->email,
                    'total_amount' => $fund->total_amount,
                    'transaction_count' => $fund->transaction_count,
                    'avg_amount' => $fund->transaction_count > 0 ? ($fund->total_amount / $fund->transaction_count) : 0,
                    'last_donation' => $fund->last_donation_at ? date('d M Y', strtotime($fund->last_donation_at)) : '-',
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function getLegacyTopUstadzRecipients($pesantrenId, $limit = 10, $period = 'year')
    {
        $query = AppreciationFund::where('pesantren_id', $pesantrenId)
            ->whereNotNull('ustadz_profile_id')
            ->with('ustadz.user', 'ustadz.activeClassesRelation')
            ->select(
                'ustadz_profile_id',
                DB::raw('SUM(amount) as total_received'),
                DB::raw('COUNT(*) as donation_count')
            )
            ->groupBy('ustadz_profile_id')
            ->orderByDesc('total_received')
            ->limit($limit);

        $this->applyLegacyPeriodFilter($query, $period);

        return $query->get()
            ->map(function ($fund) use ($pesantrenId, $period) {
                if (!$fund->ustadz || !$fund->ustadz->user) {
                    return null;
                }

                $disbursedQuery = AppreciationFund::where('pesantren_id', $pesantrenId)
                    ->where('ustadz_profile_id', $fund->ustadz_profile_id)
                    ->where('status', 'disbursed');

                $this->applyLegacyPeriodFilter($disbursedQuery, $period);
                $disbursed = $disbursedQuery->sum('amount');

                $classNames = $fund->ustadz->activeClassesRelation->pluck('name')->implode(', ') ?: '-';

                return [
                    'name' => $fund->ustadz->user->name,
                    'class' => $classNames,
                    'total_received' => $fund->total_received,
                    'donation_count' => $fund->donation_count,
                    'disbursed' => $disbursed,
                ];
            })
            ->filter()
            ->values()
            ->toArray();
    }

    private function getLegacyFinancialChartData($pesantrenId, $period = 'year')
    {
        $months = [];
        $monthlyRevenue = [];

        $monthsBack = match ($period) {
            'month' => 1,
            'quarter' => 3,
            default => 12,
        };

        for ($i = $monthsBack - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            $revenue = AppreciationFund::where('pesantren_id', $pesantrenId)
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $monthlyRevenue[] = $revenue;
        }

        return [
            'months' => $months,
            'monthly_revenue' => $monthlyRevenue,
        ];
    }

    private function applyLegacyPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'month':
                $query->whereYear('created_at', now()->year)
                    ->whereMonth('created_at', now()->month);
                break;
            case 'quarter':
                $query->whereBetween('created_at', [
                    now()->startOfMonth()->subMonths(2),
                    now()->endOfMonth(),
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
            default:
                break;
        }
    }

    private function applyLegacyPreviousPeriodFilter($query, $period)
    {
        switch ($period) {
            case 'month':
                $lastMonth = now()->subMonth();
                $query->whereYear('created_at', $lastMonth->year)
                    ->whereMonth('created_at', $lastMonth->month);
                break;
            case 'quarter':
                $query->whereBetween('created_at', [
                    now()->startOfMonth()->subMonths(5),
                    now()->startOfMonth()->subMonths(3)->endOfMonth(),
                ]);
                break;
            case 'year':
                $query->whereYear('created_at', now()->subYear()->year);
                break;
            default:
                break;
        }
    }
 
    private function calculateGrowth($current, $previous)
    {
        if ($previous == 0) return $current > 0 ? 100 : 0;
        return round((($current - $previous) / $previous) * 100, 1);
    }
 
    private function getVerificationRate($pesantrenId)
    {
        $total = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->count();
        
        $verified = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('status', 'verified')->count();
        
        return $total > 0 ? round(($verified / $total) * 100, 1) : 0;
    }
 
    private function getActiveRate($pesantrenId)
    {
        $total = SantriProfile::where('pesantren_id', $pesantrenId)->count();
        $active = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('total_juz_completed', '>', 0)->count();
        
        return $total > 0 ? round(($active / $total) * 100, 1) : 0;
    }
 
    private function getCompletionRate($pesantrenId)
    {
        $total = SantriProfile::where('pesantren_id', $pesantrenId)->count();
        $completed = Certificate::whereHas('santri', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('type', 'santri_juz')
          ->where('juz_completed', '>=', 30)
          ->distinct('user_id')
          ->count();
        
        return $total > 0 ? round(($completed / $total) * 100, 1) : 0;
    }
 
    private function getPeakMonth($pesantrenId, $months)
    {
        $peak = HafalanAudio::whereHas('hafalan', function($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        })->where('created_at', '>=', now()->subMonths($months))
        ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
        ->groupBy('month')
        ->orderByDesc('count')
        ->first();
        
        return $peak ? now()->month($peak->month)->format('F') : '-';
    }
 
    private function getScoreLabel($score)
    {
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Very Good';
        if ($score >= 70) return 'Good';
        if ($score >= 60) return 'Fair';
        return 'Needs Improvement';
    }
 
    private function getScoreDescription($score)
    {
        if ($score >= 90) return 'Performance pesantren sangat luar biasa';
        if ($score >= 80) return 'Performance pesantren sangat baik';
        if ($score >= 70) return 'Performance pesantren baik';
        if ($score >= 60) return 'Performance pesantren cukup baik';
        return 'Performance pesantren perlu ditingkatkan';
    }
 
    private function generateInsights($trends)
    {
        $insights = [];
        
        if ($trends['santri_growth'] > 10) {
            $insights[] = [
                'title' => 'Strong Student Growth',
                'description' => 'Pertumbuhan santri mencapai ' . $trends['santri_growth'] . '%, menunjukkan daya tarik pesantren yang tinggi',
                'color' => 'green',
                'icon' => 'arrow-up',
            ];
        }
        
        if ($trends['avg_progress'] >= 70) {
            $insights[] = [
                'title' => 'High Average Progress',
                'description' => 'Rata-rata progress santri mencapai ' . round($trends['avg_progress'], 1) . '%, melebihi target yang ditetapkan',
                'color' => 'blue',
                'icon' => 'chart-line',
            ];
        }
        
        if ($trends['certificate_growth'] > 15) {
            $insights[] = [
                'title' => 'Certificate Achievement',
                'description' => 'Peningkatan penerbitan sertifikat sebesar ' . $trends['certificate_growth'] . '%',
                'color' => 'purple',
                'icon' => 'certificate',
            ];
        }
        
        return $insights;
    }
 
    private function generateRecommendations($trends)
    {
        $recommendations = [];
        
        if ($trends['avg_progress'] < 60) {
            $recommendations[] = [
                'title' => 'Improve Student Support',
                'action' => 'Tingkatkan bimbingan dan mentoring untuk santri yang progress-nya masih rendah',
            ];
        }
        
        if ($trends['hafalan_growth'] < 5) {
            $recommendations[] = [
                'title' => 'Boost Hafalan Activity',
                'action' => 'Adakan program motivasi dan target hafalan untuk meningkatkan partisipasi santri',
            ];
        }
        
        $recommendations[] = [
            'title' => 'Regular Performance Review',
            'action' => 'Lakukan review performa bulanan untuk mengidentifikasi area yang perlu perbaikan',
        ];
        
        return $recommendations;
    }
 
    /**
     * Export methods
     */
    // public function exportPerformance()
    // {
    //     // Implementation for PDF/Excel export
    //     return response()->download(/* path to generated file */);
    // }
 
    // public function exportFinancial()
    // {
    //     // Implementation for PDF/Excel export
    //     return response()->download(/* path to generated file */);
    // }
}
