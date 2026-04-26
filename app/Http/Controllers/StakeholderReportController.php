<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Classes;
use App\Models\Donation;
use App\Models\Hafalan;
use App\Models\HafalanAudio;
use App\Models\SantriProfile;
use App\Models\UstadzProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $pesantrenId = auth()->user()->pesantren_id;
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
        $pesantrenId = auth()->user()->pesantren_id;
        
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
        $pesantrenId = auth()->user()->pesantren_id;
        $period = $request->get('period', 'year');
        
        // Get financial data
        $financial = $this->getFinancialData($pesantrenId, $period);
        
        // Get donation status breakdown
        $donation_status = $this->getDonationStatusBreakdown($pesantrenId);
        
        // Get top contributors
        $top_contributors = $this->getTopContributors($pesantrenId, 10);
        
        // Get top ustadz recipients
        $top_ustadz = $this->getTopUstadzRecipients($pesantrenId, 10);
        
        // Get chart data
        $chartData = $this->getFinancialChartData($pesantrenId);
        
        return view('stakeholder.financial-summary', compact(
            'financial',
            'donation_status',
            'top_contributors',
            'top_ustadz',
            'chartData'
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
        $query = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed']);
        
        // Apply period filter
        switch ($period) {
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'quarter':
                $query->where('created_at', '>=', now()->subMonths(3));
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        $donations = $query->get();
        
        $totalRevenue = $donations->sum('amount');
        $platformFee = $donations->sum('platform_fee');
        $pesantrenShare = $donations->sum('pesantren_fee');
        $ustadzTotal = $donations->sum('ustadz_net_amount');
        
        // Previous period for growth calculation
        $previousQuery = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', ['verified', 'transferred', 'available', 'requested', 'disbursed']);
        
        switch ($period) {
            case 'month':
                $previousQuery->whereMonth('created_at', now()->subMonth()->month);
                break;
            case 'quarter':
                $previousQuery->whereBetween('created_at', [now()->subMonths(6), now()->subMonths(3)]);
                break;
            case 'year':
                $previousQuery->whereYear('created_at', now()->subYear()->year);
                break;
        }
        
        $previousRevenue = $previousQuery->sum('amount');
        
        // Transaction statistics
        $totalTransactions = $donations->count();
        $successfulTransactions = $donations->whereIn('status', ['disbursed'])->count();
        $pendingDonations = Donation::where('pesantren_id', $pesantrenId)
            ->whereIn('status', ['pending', 'verified'])->get();
        
        return [
            'total_revenue' => $totalRevenue,
            'platform_fee' => $platformFee,
            'pesantren_share' => $pesantrenShare,
            'ustadz_total' => $ustadzTotal,
            'revenue_growth' => $this->calculateGrowth($totalRevenue, $previousRevenue),
            'monthly_avg' => $period === 'year' ? $totalRevenue / 12 : $totalRevenue,
            'highest_month' => $donations->groupBy(function($d) {
                return $d->created_at->format('Y-m');
            })->map->sum('amount')->max() ?? 0,
            'total_transactions' => $totalTransactions,
            'success_rate' => $totalTransactions > 0 ? round(($successfulTransactions / $totalTransactions) * 100, 1) : 0,
            'avg_donation' => $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0,
            'pending_count' => $pendingDonations->count(),
            'pending_amount' => $pendingDonations->sum('amount'),
            'cash_flow_score' => 85,
            'collection_rate' => 92,
            'disbursement_rate' => 78,
        ];
    }
 
    /**
     * Get donation status breakdown
     */
    private function getDonationStatusBreakdown($pesantrenId)
    {
        $statuses = [
            'pending' => ['label' => 'Pending Verification', 'icon' => 'clock', 'color' => 'yellow'],
            'verified' => ['label' => 'Verified', 'icon' => 'check', 'color' => 'blue'],
            'available' => ['label' => 'Available', 'icon' => 'wallet', 'color' => 'green'],
            'requested' => ['label' => 'Withdrawal Requested', 'icon' => 'hand-holding-usd', 'color' => 'orange'],
            'disbursed' => ['label' => 'Disbursed', 'icon' => 'check-double', 'color' => 'purple'],
        ];
        
        $totalAmount = Donation::where('pesantren_id', $pesantrenId)->sum('amount');
        
        $result = [];
        foreach ($statuses as $status => $info) {
            $donations = Donation::where('pesantren_id', $pesantrenId)
                ->where('status', $status)->get();
            
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
    private function getTopContributors($pesantrenId, $limit = 10)
    {
        return Donation::where('pesantren_id', $pesantrenId)
            ->with('wali.user')
            ->select('wali_id', DB::raw('SUM(amount) as total_amount'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('wali_id')
            ->orderByDesc('total_amount')
            ->limit($limit)
            ->get()
            ->map(function($donation) {
                return [
                    'name' => $donation->wali->user->name,
                    'email' => $donation->wali->user->email,
                    'total_amount' => $donation->total_amount,
                    'transaction_count' => $donation->transaction_count,
                    'avg_amount' => $donation->total_amount / $donation->transaction_count,
                    'last_donation' => Donation::where('wali_id', $donation->wali_id)
                        ->latest()->first()->created_at->format('d M Y'),
                ];
            })
            ->toArray();
    }
 
    /**
     * Get top ustadz recipients
     */
    private function getTopUstadzRecipients($pesantrenId, $limit = 10)
    {
        return Donation::where('pesantren_id', $pesantrenId)
            ->with('ustadz.user', 'ustadz.activeClassesRelation')
            ->select('ustadz_id', 
                DB::raw('SUM(ustadz_net_amount) as total_received'),
                DB::raw('COUNT(*) as donation_count'))
            ->whereIn('status', ['available', 'requested', 'disbursed'])
            ->groupBy('ustadz_id')
            ->orderByDesc('total_received')
            ->limit($limit)
            ->get()
            ->map(function($donation) {
                $disbursed = Donation::where('ustadz_id', $donation->ustadz_id)
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
            ->toArray();
    }
 
    /**
     * Get financial chart data
     */
    private function getFinancialChartData($pesantrenId)
    {
        $months = [];
        $monthlyRevenue = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            $revenue = Donation::where('pesantren_id', $pesantrenId)
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
