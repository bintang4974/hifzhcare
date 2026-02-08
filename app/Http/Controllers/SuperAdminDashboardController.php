<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Hafalan;
use App\Models\Pesantren;
use App\Models\SantriProfile;
use App\Models\User;
use App\Models\UstadzProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SuperAdminDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super Admin');
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

        return view('dashboard.super-admin', compact(
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
                'certificates',
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

        if ($totalSantri == 0) {
            return 0;
        }

        $completedSantri = SantriProfile::where('pesantren_id', $pesantrenId)
            ->where('total_juz_completed', '>=', 30)
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
                    'color' => 'blue',
                ];
            });

        // Recent certificates
        $recentCertificates = Certificate::with(['user', 'pesantren'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($c) {
                return [
                    'type' => 'certificate_issued',
                    'message' => "Sertifikat diterbitkan untuk {$c->user->name} ({$c->pesantren->name})",
                    'time' => $c->created_at,
                    'icon' => 'certificate',
                    'color' => 'green',
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
        $dbSize = DB::select('
            SELECT 
                ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS size_mb
            FROM information_schema.tables 
            WHERE table_schema = DATABASE()
        ')[0]->size_mb ?? 0;

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
        $pesantrens = Pesantren::withCount([
            'santriProfiles',
            'ustadzProfiles',
            'users' => function ($q) {
                $q->where('user_type', 'admin');
            },
        ])
            ->latest()
            ->paginate(20);

        return view('pesantrens.index', compact('pesantrens'));
    }

    /**
     * Create new pesantren form
     */
    public function createPesantren()
    {
        return view('pesantrens.create');
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
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'max_santri' => 'nullable|integer|min:0',
            'established_year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'status' => 'required|in:pending,active,inactive',
        ]);

        $pesantren = Pesantren::create($validated);

        return redirect()
            ->route('superadmin.pesantrens')
            ->with('success', 'Pesantren berhasil ditambahkan!');
    }

    /**
     * Show pesantren detail
     */
    public function showPesantren($id)
    {
        $pesantren = Pesantren::withCount([
            'santriProfiles',
            'ustadzProfiles',
            'users' => function ($q) {
                $q->where('user_type', 'admin');
            },
        ])
            ->findOrFail($id);

        // Statistics
        $stats = [
            'total_santri' => $pesantren->santri_profiles_count,
            'total_ustadz' => $pesantren->ustadz_profiles_count,
            'total_classes' => $pesantren->classes()->count(),
            'total_hafalan' => Hafalan::where('pesantren_id', $id)
                ->where('status', 'verified')
                ->count(),
            'total_certificates' => Certificate::where('pesantren_id', $id)->count(),
        ];

        // Recent data
        $recentSantri = SantriProfile::where('pesantren_id', $id)
            ->with('user')
            ->latest()
            ->take(5)
            ->get();

        $recentUstadz = UstadzProfile::where('pesantren_id', $id)
            ->with('user')
            ->withCount('classes')
            ->latest()
            ->take(5)
            ->get();

        $recentCertificates = Certificate::where('pesantren_id', $id)
            ->with(['user', 'pesantren'])
            ->latest()
            ->take(6)
            ->get();

        return view('pesantrens.show', compact(
            'pesantren',
            'stats',
            'recentSantri',
            'recentUstadz',
            'recentCertificates'
        ));
    }

    /**
     * Edit pesantren form
     */
    public function editPesantren($id)
    {
        $pesantren = Pesantren::withCount([
            'santriProfiles',
            'ustadzProfiles',
            'users' => function ($q) {
                $q->where('user_type', 'admin');
            },
        ])
            ->findOrFail($id);

        return view('pesantrens.edit', compact('pesantren'));
    }

    /**
     * Update pesantren
     */
    public function updatePesantren(Request $request, $id)
    {
        $pesantren = Pesantren::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:pesantrens,code,'.$id,
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'whatsapp' => 'nullable|string|max:20',
            'description' => 'nullable|string',
            'max_santri' => 'nullable|integer|min:0',
            'established_year' => 'nullable|integer|min:1900|max:'.date('Y'),
            'status' => 'required|in:pending,active,inactive',
        ]);

        $pesantren->update($validated);

        return redirect()
            ->route('pesantrens.show', $id)
            ->with('success', 'Pesantren berhasil diupdate!');
    }

    /**
     * Delete pesantren (soft delete)
     */
    public function destroyPesantren($id)
    {
        try {
            $pesantren = Pesantren::findOrFail($id);

            // Check if pesantren has data
            $santriCount = $pesantren->santriProfiles()->count();
            $ustadzCount = $pesantren->ustadzProfiles()->count();

            if ($santriCount > 0 || $ustadzCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Tidak dapat menghapus pesantren yang masih memiliki {$santriCount} santri dan {$ustadzCount} ustadz. Hapus atau pindahkan data terlebih dahulu.",
                ], 400);
            }

            // Soft delete
            $pesantren->delete();

            return response()->json([
                'success' => true,
                'message' => 'Pesantren berhasil dihapus!',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: '.$e->getMessage(),
            ], 500);
        }
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
            'status' => $newStatus,
        ]);
    }

    /**
     * Show pesantren settings
     */
    public function showSettings($id)
    {
        $pesantren = Pesantren::findOrFail($id);

        // Ensure settings is decoded
        if (is_string($pesantren->settings)) {
            $pesantren->settings = json_decode($pesantren->settings, true) ?? [];
        }

        return view('pesantrens.settings', compact('pesantren'));
    }

    /**
     * Update pesantren settings
     */
    public function updateSettings(Request $request, $id)
    {
        $pesantren = Pesantren::findOrFail($id);

        $settings = [
            // Basic settings
            'allow_registration' => $request->has('allow_registration'),
            'auto_approve_santri' => $request->has('auto_approve_santri'),
            'public_profile_enabled' => $request->has('public_profile_enabled'),

            // Hafalan settings
            'min_ayat_per_setoran' => (int) $request->input('min_ayat_per_setoran', 1),
            'max_ayat_per_setoran' => (int) $request->input('max_ayat_per_setoran', 50),
            'require_audio_recording' => $request->has('require_audio_recording'),
            'auto_verify_hafalan' => $request->has('auto_verify_hafalan'),

            // Certificate settings
            'min_progress_for_certificate' => (int) $request->input('min_progress_for_certificate', 100),
            'certificate_prefix' => $request->input('certificate_prefix', $pesantren->code),
            'auto_issue_certificate' => $request->has('auto_issue_certificate'),

            // Notification settings
            'enable_email_notifications' => $request->has('enable_email_notifications'),
            'enable_whatsapp_notifications' => $request->has('enable_whatsapp_notifications'),
            'notify_wali_on_verification' => $request->has('notify_wali_on_verification'),
        ];

        $pesantren->update(['settings' => $settings]);

        return redirect()
            ->route('superadmin.pesantrens.settings', $id)
            ->with('success', 'Pengaturan berhasil disimpan!');
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
                    ],
                ]);

            default:
                return response()->json(['error' => 'Invalid type'], 400);
        }
    }
}
