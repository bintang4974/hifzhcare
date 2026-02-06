<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\{CreateSantriRequest, UpdateSantriRequest};
use App\Models\{Classes, SantriProfile, WaliProfile};
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SantriController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        protected UserService $userService
    ) {
        $this->middleware('can:manage_users');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        $classes = Classes::where('status', 'active')->get(['id', 'name']);

        return view('users.santri.index', compact('classes'));
    }

    /**
     * DataTable server-side processing.
     */
    protected function datatable(Request $request)
    {
        $query = SantriProfile::with(['user', 'wali.user', 'activeClasses'])
            ->select('santri_profiles.*');

        // Filter by class
        if ($request->filled('class_id')) {
            $query->whereHas('activeClasses', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', fn($santri) => $santri->user->name)
            ->addColumn('nis', fn($santri) => $santri->nis)
            ->addColumn('gender_label', fn($santri) => $santri->gender === 'L' ? 'Laki-laki' : 'Perempuan')
            ->addColumn('age', fn($santri) => $santri->age . ' tahun')
            ->addColumn('wali_name', fn($santri) => $santri->wali?->user->name ?? '-')
            ->addColumn('classes', function ($santri) {
                return $santri->activeClasses->pluck('name')->join(', ') ?: '-';
            })
            ->addColumn('progress', function ($santri) {
                $percentage = $santri->progress_percentage;
                $color = $percentage >= 75 ? 'green' : ($percentage >= 50 ? 'blue' : ($percentage >= 25 ? 'yellow' : 'gray'));
                return "<div class='flex items-center gap-2'>
                            <div class='flex-1 bg-gray-200 rounded-full h-2 w-24'>
                                <div class='bg-{$color}-600 h-2 rounded-full' style='width: {$percentage}%'></div>
                            </div>
                            <span class='text-xs text-gray-600'>{$percentage}%</span>
                        </div>";
            })
            ->addColumn('status_badge', function ($santri) {
                return match ($santri->user->status) {
                    'active' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>',
                    'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                    'inactive' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>',
                    'graduated' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Lulus</span>',
                    default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">' . $santri->user->status . '</span>',
                };
            })
            ->addColumn('action', function ($santri) {
                $actions = '';

                // View button
                $actions .= '<a href="' . route('users.santri.show', $santri->id) . '" 
                               class="text-blue-600 hover:text-blue-900 mr-2" 
                               title="Lihat Detail">
                               <i class="fas fa-eye"></i>
                            </a>';

                // Edit button
                if (auth()->user()->can('edit_users')) {
                    $actions .= '<a href="' . route('users.santri.edit', $santri->id) . '" 
                                   class="text-yellow-600 hover:text-yellow-900 mr-2" 
                                   title="Edit">
                                   <i class="fas fa-edit"></i>
                                </a>';
                }

                // Activate button (if pending)
                if ($santri->user->status === 'pending' && auth()->user()->can('activate_users')) {
                    $actions .= '<button onclick="activateSantri(' . $santri->id . ')" 
                                   class="text-green-600 hover:text-green-900 mr-2" 
                                   title="Aktivasi">
                                   <i class="fas fa-check-circle"></i>
                                </button>';
                }

                // Delete button
                if (auth()->user()->can('delete_users')) {
                    $actions .= '<button onclick="deleteSantri(' . $santri->id . ')" 
                                   class="text-red-600 hover:text-red-900" 
                                   title="Hapus">
                                   <i class="fas fa-trash"></i>
                                </button>';
                }

                return $actions;
            })
            ->rawColumns(['progress', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $walis = WaliProfile::with('user')->get();
        $classes = Classes::where('status', 'active')->get(['id', 'name']);

        return view('users.santri.create', compact('walis', 'classes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSantriRequest $request)
    {
        try {
            // Prepare data
            $userData = [
                'pesantren_id' => auth()->user()->pesantren_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password,
                'status' => 'pending',
            ];

            $profileData = [
                'nis' => $request->nis,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'entry_date' => $request->entry_date,
                'wali_id' => $request->wali_id,
            ];

            // Prepare wali data if creating new wali
            $waliData = null;
            if (!$request->wali_id) {
                $waliData = [
                    'user' => [
                        'pesantren_id' => auth()->user()->pesantren_id,
                        'name' => $request->wali_name,
                        'email' => $request->wali_email,
                        'phone' => $request->wali_phone,
                    ],
                    'profile' => [
                        'nik' => $request->wali_nik,
                        'relation' => $request->wali_relation,
                        'occupation' => $request->wali_occupation,
                        'address' => $request->wali_address,
                    ],
                ];
            }

            // Create santri (with wali if provided)
            $user = $this->userService->createSantriWithWali(
                ['user' => $userData, 'profile' => $profileData],
                $waliData
            );

            return redirect()
                ->route('users.santri.index')
                ->with('success', 'Santri berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal menambahkan santri: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SantriProfile $santri)
    {
        $santri->load(['user', 'wali.user', 'activeClasses.activeUstadz', 'hafalans']);

        return view('users.santri.show', compact('santri'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SantriProfile $santri)
    {
        $santri->load('user');
        $walis = WaliProfile::with('user')->get();
        $classes = Classes::where('status', 'active')->get(['id', 'name']);

        return view('users.santri.edit', compact('santri', 'walis', 'classes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSantriRequest $request, SantriProfile $santri)
    {
        try {
            // Update user
            $santri->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'status' => $request->status,
            ]);

            // Update profile
            $santri->update([
                'nis' => $request->nis,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'entry_date' => $request->entry_date,
            ]);

            return redirect()
                ->route('users.santri.index')
                ->with('success', 'Data santri berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memperbarui data santri: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SantriProfile $santri)
    {
        try {
            $santri->user->delete(); // Soft delete

            return response()->json([
                'success' => true,
                'message' => 'Santri berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus santri: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate santri account.
     */
    public function activate(Request $request, SantriProfile $santri)
    {
        try {
            $this->userService->activateAccount($santri->user_id, $request->password);

            return response()->json([
                'success' => true,
                'message' => 'Akun santri berhasil diaktifkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Return simple statistics for santri (used by AJAX in index view).
     */
    public function stats(Request $request)
    {
        $pesantrenId = auth()->user()->pesantren_id;

        $base = SantriProfile::whereHas('user', function ($q) use ($pesantrenId) {
            $q->where('pesantren_id', $pesantrenId);
        });

        $total = $base->count();
        $active = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'active'))->count();
        $pending = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'pending'))->count();
        $graduated = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'graduated'))->count();

        return response()->json([
            'total' => $total,
            'active' => $active,
            'pending' => $pending,
            'graduated' => $graduated,
        ]);
    }

    /**
     * Export santri list as CSV for the current pesantren.
     */
    public function export(Request $request)
    {
        $pesantrenId = auth()->user()->pesantren_id;

        $query = SantriProfile::with(['user', 'activeClasses'])
            ->whereHas('user', function ($q) use ($pesantrenId) {
                $q->where('pesantren_id', $pesantrenId);
            })
            ->orderBy('user.name');

        $fileName = 'santri_export_' . now()->format('Y-m-d') . '.csv';

        $callback = function () use ($query) {
            $handle = fopen('php://output', 'w');
            // Header
            fputcsv($handle, ['Name', 'Email', 'NIS', 'Gender', 'Birth Date', 'Status', 'Classes']);

            $query->chunk(200, function ($items) use ($handle) {
                foreach ($items as $santri) {
                    $classes = $santri->activeClasses->pluck('name')->join(', ');
                    fputcsv($handle, [
                        $santri->user->name,
                        $santri->user->email,
                        $santri->nis,
                        $santri->gender,
                        $santri->birth_date?->format('Y-m-d') ?? '',
                        $santri->user->status,
                        $classes,
                    ]);
                }
            });

            fclose($handle);
        };

        return response()->streamDownload($callback, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}
