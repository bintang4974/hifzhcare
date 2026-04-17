<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hafalan\{CreateHafalanRequest, UpdateHafalanRequest};
use App\Models\{Certificate, Classes, Hafalan, HafalanAudio, SantriProfile, User};
use App\Services\Hafalan\HafalanService;
use App\Support\Helpers\QuranHelper;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class HafalanController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        protected HafalanService $hafalanService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        // Get filter options
        $query = Classes::where('status', 'active')->orderBy('name');

        // If user is ustadz, limit to classes they teach
        // If user is santri, limit to classes they're enrolled in
        $user = auth()->user();
        if ($user && method_exists($user, 'isUstadz') && $user->isUstadz()) {
            $ustadzProfileId = $user->ustadzProfile?->id;
            if ($ustadzProfileId) {
                $query->whereHas('activeUstadz', function ($q) use ($ustadzProfileId) {
                    $q->where('ustadz_profile_id', $ustadzProfileId);
                });
            } else {
                // No profile found — return empty
                $query->whereRaw('0 = 1');
            }
        } elseif ($user && method_exists($user, 'isSantri') && $user->isSantri()) {
            $santriProfileId = $user->santriProfile?->id;
            if ($santriProfileId) {
                $query->whereHas('activeSantri', function ($q) use ($santriProfileId) {
                    $q->where('santri_profile_id', $santriProfileId);
                });
            } else {
                // No profile found — return empty
                $query->whereRaw('0 = 1');
            }
        }

        $classes = $query->get(['id', 'name']);
        $surahs = QuranHelper::getAllSurahs();

        return view('hafalan.index', compact('classes', 'surahs'));
    }

    /**
     * DataTable server-side processing.
     */
    protected function datatable(Request $request)
    {
        $query = Hafalan::with(['user:id,name', 'class:id,name', 'verifiedBy.user:id,name', 'audios'])
            ->select('hafalans.*');

        // If user is ustadz, limit to hafalans for classes they teach
        $user = auth()->user();
        if ($user && method_exists($user, 'isUstadz') && $user->isUstadz()) {
            $ustadzProfileId = $user->ustadzProfile?->id;
            if ($ustadzProfileId) {
                $query->whereHas('class.activeUstadz', function ($q) use ($ustadzProfileId) {
                    $q->where('ustadz_profile_id', $ustadzProfileId)
                        ->where('class_ustadz.status', 'active');
                });
            } else {
                // No profile found — return empty
                $query->whereRaw('0 = 1');
            }
        } elseif ($user && method_exists($user, 'isSantri') && $user->isSantri()) {
            // If user is santri, limit to their own hafalans only
            $query->where('user_id', $user->id);
        }

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->class_id);
        }

        if ($request->filled('juz_number')) {
            $query->where('juz_number', $request->juz_number);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('hafalan_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('hafalan_date', '<=', $request->date_to);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('user_name', fn($hafalan) => $hafalan->user->name)
            ->addColumn('class_name', fn($hafalan) => $hafalan->class?->name ?? '-')
            ->addColumn('surah_info', function ($hafalan) {
                $surahName = $hafalan->surah_name;
                return "{$surahName} ({$hafalan->surah_number})";
            })
            ->addColumn('ayat_range', fn($hafalan) => "{$hafalan->ayat_start}-{$hafalan->ayat_end}")
            ->addColumn('ayat_count', fn($hafalan) => $hafalan->ayat_count)
            ->addColumn('type_badge', function ($hafalan) {
                $class = $hafalan->type === 'setoran' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800';
                $label = $hafalan->type === 'setoran' ? 'Setoran' : 'Muraja\'ah';
                return "<span class='px-2 py-1 text-xs font-semibold rounded-full {$class}'>{$label}</span>";
            })
            ->addColumn('status_badge', function ($hafalan) {
                return match ($hafalan->status) {
                    'verified' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>',
                    'rejected' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Rejected</span>',
                    default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                };
            })
            ->addColumn('has_audio', function ($hafalan) {
                if ($hafalan->has_audio) {
                    return '<i class="fas fa-volume-up text-blue-500" title="Ada audio"></i>';
                }
                return '<i class="fas fa-volume-mute text-gray-400" title="Tidak ada audio"></i>';
            })
            ->addColumn('verified_info', function ($hafalan) {
                if ($hafalan->verified_at) {
                    $verifier = $hafalan->verifiedBy?->user?->name ?? '-';
                    return "<small class='text-gray-600'>" .
                        $hafalan->verified_at->format('d M Y') . "<br>" .
                        "oleh " . $verifier .
                        "</small>";
                }
                return '-';
            })
            ->addColumn('action', function ($hafalan) {
                $actions = '';

                // View button
                $actions .= '<a href="' . route('hafalan.show', $hafalan->id) . '" 
                               class="text-blue-600 hover:text-blue-900 mr-2" 
                               title="Lihat Detail">
                               <i class="fas fa-eye"></i>
                            </a>';

                // Edit button (only if pending and own hafalan)
                if (
                    $hafalan->status === 'pending' &&
                    (auth()->id() === $hafalan->created_by_user_id || auth()->user()->can('edit_hafalan'))
                ) {
                    $actions .= '<a href="' . route('hafalan.edit', $hafalan->id) . '" 
                                   class="text-yellow-600 hover:text-yellow-900 mr-2" 
                                   title="Edit">
                                   <i class="fas fa-edit"></i>
                                </a>';
                }

                // Verify button (only for ustadz and pending)
                if ($hafalan->status === 'pending' && auth()->user()->can('verify_hafalan')) {
                    $actions .= '<button onclick="verifyHafalan(' . $hafalan->id . ')" 
                                   class="text-green-600 hover:text-green-900 mr-2" 
                                   title="Verifikasi">
                                   <i class="fas fa-check-circle"></i>
                                </button>';
                }

                // Delete button (only if pending and own hafalan)
                if (
                    $hafalan->status === 'pending' &&
                    (auth()->id() === $hafalan->created_by_user_id || auth()->user()->can('delete_hafalan'))
                ) {
                    $actions .= '<button onclick="deleteHafalan(' . $hafalan->id . ')" 
                                   class="text-red-600 hover:text-red-900" 
                                   title="Hapus">
                                   <i class="fas fa-trash"></i>
                                </button>';
                }

                return $actions;
            })
            ->rawColumns(['type_badge', 'status_badge', 'has_audio', 'verified_info', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();

        // Only ustadz and admin can create hafalan entries
        if ($user->isSantri()) {
            abort(403, 'Santri tidak memiliki akses untuk menambahkan hafalan. Silakan minta ustadz untuk menambahkan hafalan Anda.');
        }

        // Get users based on role
        if ($user->isUstadz()) {
            // Ustadz can create for their class students
            $users = User::whereHas('santriProfile.activeClasses.activeUstadz', function ($q) use ($user) {
                $q->where('ustadz_profile_id', $user->ustadzProfile->id);
            })->where('user_type', 'santri')
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name']);
        } else {
            // Admin can create for all students
            $users = User::where('user_type', 'santri')
                ->where('status', 'active')
                ->orderBy('name')
                ->get(['id', 'name']);
        }

        $classes = Classes::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $surahs = QuranHelper::getAllSurahs();

        return view('hafalan.create', compact('users', 'classes', 'surahs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateHafalanRequest $request)
    {
        try {
            $this->hafalanService->createHafalan(
                $request->validated(),
                $request->file('audio_file')
            );

            return redirect()
                ->route('hafalan.index')
                ->with('success', 'Hafalan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan hafalan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Hafalan $hafalan)
    {
        $hafalan->load(['user', 'class', 'createdBy', 'verifiedBy.user', 'audios']);

        return view('hafalan.show', compact('hafalan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Hafalan $hafalan)
    {
        // Authorization check
        if ($hafalan->status !== 'pending') {
            return redirect()
                ->route('hafalan.index')
                ->with('error', 'Hafalan yang sudah diverifikasi tidak dapat diedit.');
        }

        $users = User::where('user_type', 'santri')
            ->where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $classes = Classes::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $surahs = QuranHelper::getAllSurahs();

        return view('hafalan.edit', compact('hafalan', 'users', 'classes', 'surahs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateHafalanRequest $request, Hafalan $hafalan)
    {
        try {
            $this->hafalanService->updateHafalan(
                $hafalan->id,
                $request->validated(),
                $request->file('audio_file')
            );

            return redirect()
                ->route('hafalan.index')
                ->with('success', 'Hafalan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui hafalan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Hafalan $hafalan)
    {
        try {
            $this->hafalanService->deleteHafalan($hafalan->id);

            return response()->json([
                'success' => true,
                'message' => 'Hafalan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus hafalan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify hafalan.
     */
    public function verify(Request $request, Hafalan $hafalan)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $this->hafalanService->verifyHafalan(
                $hafalan->id,
                auth()->user()->ustadzProfile->id,
                $request->notes
            );

            return response()->json([
                'success' => true,
                'message' => 'Hafalan berhasil diverifikasi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memverifikasi hafalan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject hafalan.
     */
    public function reject(Request $request, Hafalan $hafalan)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        try {
            $this->hafalanService->rejectHafalan(
                $hafalan->id,
                auth()->user()->ustadzProfile->id,
                $request->reason
            );

            return response()->json([
                'success' => true,
                'message' => 'Hafalan berhasil ditolak.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menolak hafalan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get progress by user.
     */
    public function progress($userId = null)
    {
        // Determine which santri to show
        if (auth()->user()->user_type === 'santri') {
            $santri = auth()->user()->santriProfile;
        } elseif (auth()->user()->user_type === 'wali') {
            // Get santri from wali's children
            $santri = auth()->user()->waliProfile->santriProfiles()->first();
        } else {
            // Admin/Ustadz viewing specific santri
            $santri = SantriProfile::findOrFail($userId);
        }

        if (!$santri) {
            abort(404, 'Data santri tidak ditemukan');
        }

        $userId = $santri->user_id;

        // Statistics
        $stats = [
            'progress_percentage' => $santri->progress_percentage ?? 0,
            'completed_juz' => Certificate::where('user_id', $userId)
                ->where('type', 'per_juz')
                ->count(),
            'total_verified' => Hafalan::where('user_id', $userId)
                ->whereHas('audios', function ($q) {
                    $q->where('status', 'verified');
                })
                ->count(),
            'certificates' => Certificate::where('user_id', $userId)->count(),
            'current_streak' => $this->calculateStreak($userId),
            'avg_per_day' => $this->calculateAvgPerDay($userId),
            'total_hours' => $this->calculateTotalHours($userId),
            'consistency' => $this->calculateConsistency($userId),
        ];

        // 30 Juz Progress
        $juzProgress = $this->getJuzProgress($userId);

        // Calendar data (current month)
        $calendar = $this->getMonthlyCalendar($userId);

        // Recent achievements
        $recentAchievements = $this->getRecentAchievements($userId);

        return view('hafalan.progress', compact(
            'santri',
            'stats',
            'juzProgress',
            'calendar',
            'recentAchievements'
        ));
    }

    /**
     * Get progress for all 30 Juz
     */
    private function getJuzProgress($santriId)
    {
        $juzData = [
            1 => ['surah_range' => 'Al-Fatihah - Al-Baqarah 141', 'ayat' => 148],
            2 => ['surah_range' => 'Al-Baqarah 142-252', 'ayat' => 111],
            3 => ['surah_range' => 'Al-Baqarah 253 - Ali Imran 92', 'ayat' => 126],
            4 => ['surah_range' => 'Ali Imran 93 - An-Nisa 23', 'ayat' => 132],
            5 => ['surah_range' => 'An-Nisa 24-147', 'ayat' => 124],
            6 => ['surah_range' => 'An-Nisa 148 - Al-Maidah 81', 'ayat' => 111],
            7 => ['surah_range' => 'Al-Maidah 82 - Al-An\'am 110', 'ayat' => 149],
            8 => ['surah_range' => 'Al-An\'am 111 - Al-A\'raf 87', 'ayat' => 142],
            9 => ['surah_range' => 'Al-A\'raf 88 - Al-Anfal 40', 'ayat' => 159],
            10 => ['surah_range' => 'Al-Anfal 41 - At-Taubah 92', 'ayat' => 127],
            11 => ['surah_range' => 'At-Taubah 93 - Hud 5', 'ayat' => 151],
            12 => ['surah_range' => 'Hud 6 - Yusuf 52', 'ayat' => 134],
            13 => ['surah_range' => 'Yusuf 53 - Ibrahim 52', 'ayat' => 146],
            14 => ['surah_range' => 'Al-Hijr 1 - An-Nahl 128', 'ayat' => 227],
            15 => ['surah_range' => 'Al-Isra 1 - Al-Kahf 74', 'ayat' => 185],
            16 => ['surah_range' => 'Al-Kahf 75 - Ta-Ha 135', 'ayat' => 171],
            17 => ['surah_range' => 'Al-Anbiya 1 - Al-Hajj 78', 'ayat' => 190],
            18 => ['surah_range' => 'Al-Mu\'minun 1 - Al-Furqan 20', 'ayat' => 201],
            19 => ['surah_range' => 'Al-Furqan 21 - An-Naml 55', 'ayat' => 186],
            20 => ['surah_range' => 'An-Naml 56 - Al-Ankabut 45', 'ayat' => 190],
            21 => ['surah_range' => 'Al-Ankabut 46 - Al-Ahzab 30', 'ayat' => 170],
            22 => ['surah_range' => 'Al-Ahzab 31 - Ya-Sin 27', 'ayat' => 168],
            23 => ['surah_range' => 'Ya-Sin 28 - Az-Zumar 31', 'ayat' => 178],
            24 => ['surah_range' => 'Az-Zumar 32 - Fussilat 46', 'ayat' => 173],
            25 => ['surah_range' => 'Fussilat 47 - Al-Jasiyah 37', 'ayat' => 177],
            26 => ['surah_range' => 'Al-Ahqaf 1 - Adh-Dhariyat 30', 'ayat' => 195],
            27 => ['surah_range' => 'Adh-Dhariyat 31 - Al-Hadid 29', 'ayat' => 161],
            28 => ['surah_range' => 'Al-Mujadilah 1 - At-Tahrim 12', 'ayat' => 184],
            29 => ['surah_range' => 'Al-Mulk 1 - Al-Mursalat 50', 'ayat' => 231],
            30 => ['surah_range' => 'An-Naba 1 - An-Nas 6', 'ayat' => 564],
        ];

        $progress = [];

        foreach ($juzData as $juzNumber => $data) {
            // Check if completed (has certificate)
            $certificate = Certificate::where('user_id', $santriId)
                ->where('type', 'per_juz')
                ->where('juz_completed', $juzNumber)
                ->first();

            // Count verified hafalan in this juz
            $verified = Hafalan::where('user_id', $santriId)
                ->where('juz_number', $juzNumber)
                ->whereHas('audios', function ($q) {
                    $q->where('status', 'verified');
                })
                ->count();

            // Calculate progress
            $progressPercentage = $certificate ? 100 : min(($verified / $data['ayat']) * 100, 99);

            // Determine status
            $status = 'pending';
            if ($certificate) {
                $status = 'completed';
            } elseif ($progressPercentage > 0) {
                $status = 'in_progress';
            }

            $progress[] = [
                'number' => $juzNumber,
                'surah_range' => $data['surah_range'],
                'ayat_count' => $data['ayat'],
                'verified' => $verified,
                'progress' => round($progressPercentage, 0),
                'status' => $status,
                'certificate_date' => $certificate ? ($certificate->issued_at ? $certificate->issued_at->format('d M Y') : null) : null,
            ];
        }

        return $progress;
    }

    /**
     * Get monthly calendar with activity
     */
    private function getMonthlyCalendar($santriId)
    {
        $calendar = [];
        $now = now();
        $daysInMonth = $now->daysInMonth;
        $firstDayOfWeek = $now->copy()->startOfMonth()->dayOfWeek;

        // Fill empty days at start
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendar[] = ['day' => '', 'has_activity' => false, 'is_today' => false];
        }

        // Get activity dates
        $activityDates = Hafalan::where('user_id', $santriId)
            ->whereMonth('hafalan_date', $now->month)
            ->whereYear('hafalan_date', $now->year)
            ->pluck('hafalan_date')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->unique()
            ->toArray();

        // Fill actual days
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateString = $now->copy()->setDay($day)->format('Y-m-d');

            $calendar[] = [
                'day' => $day,
                'has_activity' => in_array($dateString, $activityDates),
                'is_today' => $day == $now->day,
            ];
        }

        return $calendar;
    }

    /**
     * Calculate current streak
     */
    private function calculateStreak($santriId)
    {
        $streak = 0;
        $currentDate = now();

        while (true) {
            $hasActivity = Hafalan::where('user_id', $santriId)
                ->whereDate('hafalan_date', $currentDate->format('Y-m-d'))
                ->exists();

            if (!$hasActivity) {
                break;
            }

            $streak++;
            $currentDate->subDay();

            if ($streak > 365) break; // Max 1 year
        }

        return $streak;
    }

    /**
     * Calculate average per day
     */
    private function calculateAvgPerDay($santriId)
    {
        $totalVerified = Hafalan::where('user_id', $santriId)
            ->whereHas('audios', function ($q) {
                $q->where('status', 'verified');
            })
            ->count();

        $firstHafalan = Hafalan::where('user_id', $santriId)
            ->oldest('hafalan_date')
            ->first();

        if (!$firstHafalan || !$firstHafalan->hafalan_date) return 0;

        $days = max($firstHafalan->hafalan_date->diffInDays(now()), 1);

        return round($totalVerified / $days, 1);
    }

    /**
     * Calculate total hours
     */
    private function calculateTotalHours($santriId)
    {
        // Assume average 5 minutes per hafalan
        $totalHafalan = Hafalan::where('user_id', $santriId)
            ->whereHas('audios', function ($q) {
                $q->where('status', 'verified');
            })
            ->count();

        return round(($totalHafalan * 5) / 60, 1);
    }

    /**
     * Calculate consistency percentage
     */
    private function calculateConsistency($santriId)
    {
        $totalDays = 30; // Last 30 days
        $activeDays = Hafalan::where('user_id', $santriId)
            ->where('hafalan_date', '>=', now()->subDays(30))
            ->selectRaw('DATE(hafalan_date) as date')
            ->groupBy('date')
            ->get()
            ->count();

        return round(($activeDays / $totalDays) * 100, 0);
    }

    /**
     * Get recent achievements
     */
    private function getRecentAchievements($santriId)
    {
        $achievements = [];

        // Recent certificates
        $certificates = Certificate::where('user_id', $santriId)
            ->orderByDesc('issued_at')
            ->limit(3)
            ->get();

        foreach ($certificates as $cert) {
            $achievements[] = [
                'icon' => 'certificate',
                'title' => $cert->type === 'khatam' ? 'Khatam 30 Juz!' : 'Selesai Juz ' . $cert->juz_completed,
                'date' => $cert->issued_at ? $cert->issued_at->diffForHumans() : 'Tanggal tidak tersedia',
            ];
        }

        return $achievements;
    }
}
