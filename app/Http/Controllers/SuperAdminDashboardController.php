<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;

// class SuperAdminDashboardController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth');
//     }

//     public function index()
//     {
//         // Delegate to the main DashboardController which routes by user role
//         $dashboard = new DashboardController();
//         return $dashboard->index();
//     }

//     public function pesantrens()
//     {
//         abort_unless(auth()->user()->user_type === 'super_admin', 403);
//         // Simple list view placeholder — reuse existing Pesantren model if available
//         $pesantrens = \App\Models\Pesantren::latest()->get();
//         return view('superadmin.pesantrens', compact('pesantrens'));
//     }

//     public function createPesantren()
//     {
//         abort_unless(auth()->user()->user_type === 'super_admin', 403);
//         return view('superadmin.create-pesantren');
//     }

//     public function storePesantren(Request $request)
//     {
//         abort_unless(auth()->user()->user_type === 'super_admin', 403);
//         $data = $request->validate([
//             'name' => 'required|string|max:255',
//             'email' => 'nullable|email',
//         ]);
//         \App\Models\Pesantren::create($data + ['status' => 'active']);
//         return redirect()->route('superadmin.pesantrens')->with('success', 'Pesantren dibuat');
//     }

//     public function togglePesantrenStatus($id)
//     {
//         abort_unless(auth()->user()->user_type === 'super_admin', 403);
//         $p = \App\Models\Pesantren::findOrFail($id);
//         $p->status = $p->status === 'active' ? 'inactive' : 'active';
//         $p->save();
//         return redirect()->back()->with('success', 'Status pesantren diperbarui');
//     }

//     public function statistics()
//     {
//         abort_unless(auth()->user()->user_type === 'super_admin', 403);
//         return response()->json(['ok' => true]);
//     }
// }

namespace App\Http\Controllers;

use App\Models\{Pesantren, UstadzProfile, SantriProfile, HafalanRecord, Certificate, Hafalan, User};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:super_admin');
    }

    /**
     * Display super admin dashboard
     */
    public function index()
    {
        // Global Statistics
        $stats = [
            'total_pesantrens' => Pesantren::count(),
            'active_pesantrens' => Pesantren::where('status', 'active')->count(),
            'pending_pesantrens' => Pesantren::where('status', 'pending')->count(),
            'total_users' => User::count(),
            'total_admins' => User::where('user_type', 'admin')->count(),
            'total_ustadz' => UstadzProfile::count(),
            'total_santri' => SantriProfile::count(),
            'total_hafalans' => Hafalan::where('status', 'verified')->count(),
            'total_certificates' => Certificate::count(),
        ];

        // Monthly Growth Data (Last 6 months)
        $monthlyGrowth = $this->getMonthlyGrowth();

        // Pesantren Performance
        $pesantrenPerformance = $this->getPesantrenPerformance();

        // Recent Activities
        $recentActivities = $this->getRecentActivities();

        // Top Pesantrens by Santri
        $topPesantrens = Pesantren::withCount('santris')
            ->orderBy('santris_count', 'desc')
            ->take(5)
            ->get();

        // User Distribution by Role
        $userDistribution = [
            'admins' => User::where('user_type', 'admin')->count(),
            'ustadz' => User::where('user_type', 'ustadz')->count(),
            'santri' => User::where('user_type', 'santri')->count(),
            'wali' => User::where('user_type', 'wali')->count(),
        ];

        // System Health
        $systemHealth = $this->getSystemHealth();

        return view('dashboards.superadmin', compact(
            'stats',
            'monthlyGrowth',
            'pesantrenPerformance',
            'recentActivities',
            'topPesantrens',
            'userDistribution',
            'systemHealth'
        ));
    }

    /**
     * Get monthly growth data
     */
    protected function getMonthlyGrowth()
    {
        $months = [];
        $santriData = [];
        $hafalanData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $months[] = $date->format('M Y');

            // Santri added in this month
            $santriData[] = SantriProfile::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            // Hafalans verified in this month
            $hafalanData[] = Hafalan::where('status', 'verified')
                ->whereYear('verified_at', $date->year)
                ->whereMonth('verified_at', $date->month)
                ->count();
        }

        return [
            'labels' => $months,
            'santri' => $santriData,
            'hafalan' => $hafalanData,
        ];
    }

    /**
     * Get pesantren performance data
     */
    protected function getPesantrenPerformance()
    {
        return Pesantren::where('status', 'active')
            ->withCount([
                'santris',
                'ustadzs',
                'hafalans' => function ($q) {
                    $q->where('status', 'verified');
                },
                'certificates'
            ])
            ->get()
            ->map(function ($pesantren) {
                return [
                    'name' => $pesantren->name,
                    'santri_count' => $pesantren->santris_count,
                    'ustadz_count' => $pesantren->ustadzs_count,
                    'hafalan_count' => $pesantren->hafalans_count,
                    'certificate_count' => $pesantren->certificates_count,
                    'completion_rate' => $this->calculateCompletionRate($pesantren->id),
                ];
            });
    }

    /**
     * Calculate completion rate for pesantren
     */
    protected function calculateCompletionRate($pesantrenId)
    {
        $totalSantri = SantriProfile::where('pesantren_id', $pesantrenId)->count();

        if ($totalSantri == 0) return 0;

        $completedSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('progress_percentage', '>=', 100)
            ->count();

        return round(($completedSantri / $totalSantri) * 100, 2);
    }

    /**
     * Get recent system activities
     */
    protected function getRecentActivities()
    {
        $activities = [];

        // Recent Pesantren registrations
        $recentPesantrens = Pesantren::latest()
            ->take(5)
            ->get()
            ->map(function ($p) {
                return [
                    'type' => 'pesantren_registered',
                    'message' => "Pesantren '{$p->name}' terdaftar",
                    'time' => $p->created_at,
                    'icon' => 'building',
                    'color' => 'blue'
                ];
            });

        // Recent certificates
        $recentCertificates = Certificate::with(['santri.user', 'pesantren'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($c) {
                return [
                    'type' => 'certificate_issued',
                    'message' => "Sertifikat diterbitkan untuk {$c->santri->user->name} ({$c->pesantren->name})",
                    'time' => $c->created_at,
                    'icon' => 'certificate',
                    'color' => 'green'
                ];
            });

        // Merge and sort by time
        $activities = $recentPesantrens->concat($recentCertificates)
            ->sortByDesc('time')
            ->take(10)
            ->values();

        return $activities;
    }

    /**
     * Get system health metrics
     */
    protected function getSystemHealth()
    {
        // Database size
        $dbSize = DB::select("
            SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
        ")[0]->size_mb ?? 0;

        // Storage usage (if using local storage)
        $storageUsed = 0;
        if (file_exists(storage_path('app/public'))) {
            $storageUsed = round(disk_free_space(storage_path('app/public')) / 1024 / 1024 / 1024, 2);
        }

        return [
            'database_size_mb' => $dbSize,
            'storage_used_gb' => $storageUsed,
            'total_records' => User::count() + Hafalan::count() + Certificate::count(),
            'status' => 'healthy', // Could be calculated based on thresholds
        ];
    }

    /**
     * Pesantren management page
     */
    public function pesantrens()
    {
        $pesantrens = Pesantren::withCount(['santris', 'ustadzs', 'admins'])
            ->latest()
            ->paginate(20);

        return view('dashboards.superadmin-pesantrens', compact('pesantrens'));
    }

    /**
     * Create new pesantren
     */
    public function createPesantren()
    {
        return view('dashboards.superadmin-pesantren-create');
    }

    /**
     * Store new pesantren
     */
    public function storePesantren(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:pesantrens',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string',
            'max_santri' => 'nullable|integer|min:0',
            'status' => 'required|in:pending,active,inactive',
        ]);

        $pesantren = Pesantren::create($validated);

        return redirect()
            ->route('superadmin.pesantrens')
            ->with('success', 'Pesantren berhasil ditambahkan!');
    }

    /**
     * Toggle pesantren status
     */
    public function togglePesantrenStatus($id)
    {
        $pesantren = Pesantren::findOrFail($id);

        $newStatus = $pesantren->status === 'active' ? 'inactive' : 'active';
        $pesantren->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => "Status pesantren berhasil diubah menjadi {$newStatus}",
            'status' => $newStatus
        ]);
    }

    /**
     * Get statistics API for charts
     */
    public function statistics(Request $request)
    {
        $type = $request->get('type', 'overview');

        switch ($type) {
            case 'overview':
                return response()->json([
                    'total_pesantrens' => Pesantren::count(),
                    'active_pesantrens' => Pesantren::where('status', 'active')->count(),
                    'total_users' => User::count(),
                    'total_santri' => SantriProfile::count(),
                ]);

            case 'growth':
                return response()->json($this->getMonthlyGrowth());

            case 'distribution':
                return response()->json([
                    'labels' => ['Admin', 'Ustadz', 'Santri', 'Wali'],
                    'data' => [
                        User::where('user_type', 'admin')->count(),
                        User::where('user_type', 'ustadz')->count(),
                        User::where('user_type', 'santri')->count(),
                        User::where('user_type', 'wali')->count(),
                    ]
                ]);

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }
}
