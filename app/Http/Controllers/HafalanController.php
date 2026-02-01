<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hafalan\{CreateHafalanRequest, UpdateHafalanRequest};
use App\Models\{Classes, Hafalan, User};
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
        $classes = Classes::where('status', 'active')
            ->orderBy('name')
            ->get(['id', 'name']);

        $surahs = QuranHelper::getAllSurahs();

        return view('hafalan.index', compact('classes', 'surahs'));
    }

    /**
     * DataTable server-side processing.
     */
    protected function datatable(Request $request)
    {
        $query = Hafalan::with(['user:id,name', 'class:id,name', 'verifiedBy:id,name', 'audios'])
            ->select('hafalans.*');

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
                    return "<small class='text-gray-600'>" .
                        $hafalan->verified_at->format('d M Y') . "<br>" .
                        "oleh " . ($hafalan->verifiedBy?->name ?? '-') .
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
        $hafalan->load(['user', 'class', 'createdBy', 'verifiedBy', 'audios']);

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
    public function progress(Request $request)
    {
        $userId = $request->user_id ?? auth()->id();

        $progress = $this->hafalanService->getProgress($userId);

        if ($request->ajax()) {
            return response()->json($progress);
        }

        return view('hafalan.progress', compact('progress'));
    }
}
