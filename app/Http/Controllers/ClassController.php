<?php

namespace App\Http\Controllers;

use App\Http\Requests\Class\{AssignUstadzRequest, CreateClassRequest, EnrollSantriRequest, UpdateClassRequest};
use App\Models\{Classes, SantriProfile, UstadzProfile};
use App\Services\Class\ClassService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ClassController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        protected ClassService $classService
    ) {
        // Most class actions require management permission, but allow `show`
        // to be accessed by authenticated users (ustadz can view their classes).
        $this->middleware('can:manage_classes')->except(['show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('classes.index');
    }

    /**
     * DataTable server-side processing.
     */
    protected function datatable(Request $request)
    {
        $query = Classes::with(['activeUstadz.user', 'activeSantri'])
            ->withCount(['activeSantri', 'activeUstadz'])
            ->select('classes.*');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', fn($class) => $class->name)
            ->addColumn('code', fn($class) => $class->code)
            ->addColumn('ustadz', function ($class) {
                $ustadzNames = $class->activeUstadz->pluck('user.name')->toArray();
                return count($ustadzNames) > 0
                    ? '<div class="text-sm">' . implode('<br>', array_map(fn($n) => '• ' . $n, $ustadzNames)) . '</div>'
                    : '<span class="text-gray-400">Belum ada ustadz</span>';
            })
            ->addColumn('capacity', function ($class) {
                $percentage = $class->max_capacity > 0
                    ? round(($class->current_student_count / $class->max_capacity) * 100)
                    : 0;
                $color = $percentage >= 90 ? 'red' : ($percentage >= 70 ? 'yellow' : 'green');

                return "<div class='flex items-center gap-2'>
                            <div class='flex-1 bg-gray-200 rounded-full h-2 w-24'>
                                <div class='bg-{$color}-600 h-2 rounded-full' style='width: {$percentage}%'></div>
                            </div>
                            <span class='text-xs text-gray-600'>{$class->current_student_count}/{$class->max_capacity}</span>
                        </div>";
            })
            ->addColumn('status_badge', function ($class) {
                return $class->status === 'active'
                    ? '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>'
                    : '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>';
            })
            ->addColumn('action', function ($class) {
                $actions = '';

                // View button
                $actions .= '<a href="' . route('classes.show', $class->id) . '" 
                               class="text-blue-600 hover:text-blue-900 mr-2" 
                               title="Lihat Detail">
                               <i class="fas fa-eye"></i>
                            </a>';

                // Manage members button
                $actions .= '<a href="' . route('classes.members', $class->id) . '" 
                               class="text-purple-600 hover:text-purple-900 mr-2" 
                               title="Kelola Anggota">
                               <i class="fas fa-users"></i>
                            </a>';

                // Edit button
                if (auth()->user()->can('edit_classes')) {
                    $actions .= '<a href="' . route('classes.edit', $class->id) . '" 
                                   class="text-yellow-600 hover:text-yellow-900 mr-2" 
                                   title="Edit">
                                   <i class="fas fa-edit"></i>
                                </a>';
                }

                // Delete button
                if (auth()->user()->can('delete_classes')) {
                    $actions .= '<button onclick="deleteClass(' . $class->id . ')" 
                                   class="text-red-600 hover:text-red-900" 
                                   title="Hapus">
                                   <i class="fas fa-trash"></i>
                                </button>';
                }

                return $actions;
            })
            ->rawColumns(['ustadz', 'capacity', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('classes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateClassRequest $request)
    {
        try {
            $data = $request->validated();
            $data['pesantren_id'] = auth()->user()->pesantren_id;

            $this->classService->createClass($data);

            return redirect()
                ->route('classes.index')
                ->with('success', 'Kelas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan kelas: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Classes $class)
    {
        $class->load(['activeUstadz.user', 'activeSantri.user', 'hafalans.user']);

        // Get statistics
        $stats = [
            'total_santri' => $class->activeSantri->count(),
            'total_ustadz' => $class->activeUstadz->count(),
            'total_hafalan' => $class->hafalans->count(),
            'verified_hafalan' => $class->hafalans->where('status', 'verified')->count(),
            'pending_hafalan' => $class->hafalans->where('status', 'pending')->count(),
        ];

        // If current user is an ustadz, only allow access if they teach this class
        $user = auth()->user();
        if ($user && method_exists($user, 'isUstadz') && $user->isUstadz()) {
            $ustadzId = $user->ustadzProfile?->id;
            if (! $class->activeUstadz->contains('id', $ustadzId)) {
                abort(403, 'This action is unauthorized.');
            }
        }

        return view('classes.show', compact('class', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Classes $class)
    {
        return view('classes.edit', compact('class'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassRequest $request, Classes $class)
    {
        try {
            $class->update($request->validated());

            return redirect()
                ->route('classes.index')
                ->with('success', 'Data kelas berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data kelas: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Classes $class)
    {
        try {
            $class->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kelas berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus kelas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show members management page.
     */
    public function members(Classes $class)
    {
        $class->load(['activeUstadz.user', 'activeSantri.user']);

        // Get available ustadz (not assigned to this class)
        $availableUstadz = UstadzProfile::whereDoesntHave('activeClasses', function ($q) use ($class) {
            $q->where('class_id', $class->id);
        })->with('user')->get();

        // Get available santri (not enrolled in this class)
        $availableSantri = SantriProfile::whereDoesntHave('activeClasses', function ($q) use ($class) {
            $q->where('class_id', $class->id);
        })->with('user')
            ->whereHas('user', function ($q) {
                $q->where('status', 'active');
            })->get();

        return view('classes.members', compact('class', 'availableUstadz', 'availableSantri'));
    }

    /**
     * Assign ustadz to class.
     */
    public function assignUstadz(AssignUstadzRequest $request, Classes $class)
    {
        try {
            $this->classService->assignUstadz($class->id, $request->ustadz_profile_id);

            return response()->json([
                'success' => true,
                'message' => 'Ustadz berhasil ditugaskan ke kelas.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menugaskan ustadz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove ustadz from class.
     */
    public function removeUstadz(Request $request, Classes $class, UstadzProfile $ustadz)
    {
        try {
            $class->removeUstadz($ustadz);

            return response()->json([
                'success' => true,
                'message' => 'Ustadz berhasil dihapus dari kelas.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ustadz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Enroll santri to class.
     */
    public function enrollSantri(EnrollSantriRequest $request, Classes $class)
    {
        try {
            $this->classService->enrollSantri($class->id, $request->santri_profile_id);

            return response()->json([
                'success' => true,
                'message' => 'Santri berhasil didaftarkan ke kelas.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendaftarkan santri: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove santri from class.
     */
    public function removeSantri(Request $request, Classes $class, SantriProfile $santri)
    {
        try {
            $class->removeSantri($santri);

            return response()->json([
                'success' => true,
                'message' => 'Santri berhasil dihapus dari kelas.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus santri: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Graduate santri from class.
     */
    public function graduateSantri(Request $request, Classes $class, SantriProfile $santri)
    {
        try {
            $this->classService->graduateSantri($class->id, $santri->id);

            return response()->json([
                'success' => true,
                'message' => 'Santri berhasil diluluskan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal meluluskan santri: ' . $e->getMessage()
            ], 500);
        }
    }
}
