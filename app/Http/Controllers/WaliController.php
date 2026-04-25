<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\CreateWaliRequest;
use App\Http\Requests\User\UpdateWaliRequest;
use App\Models\User;
use App\Models\WaliProfile;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\User\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class WaliController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth');
        $this->middleware('tenant');
    }

    /**
     * Display a listing of wali
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('users.wali.index');
    }

    /**
     * DataTable for wali
     */
    protected function datatable(Request $request)
    {
        $query = WaliProfile::with(['user', 'santriProfiles.user'])
            ->join('users', 'users.id', '=', 'wali_profiles.user_id')
            ->select('wali_profiles.*')
            ->whereHas('user'); // Only show wali with existing (non-deleted) users

        // Only filter by pesantren if NOT Super Admin
        if (!auth()->user()->isSuperAdmin()) {
            $query->where('wali_profiles.pesantren_id', session('current_pesantren_id'));
        }

        // Filters
        if ($request->filled('status')) {
            $query->where('users.status', $request->status);
        }

        if ($request->filled('relation')) {
            $query->where('wali_profiles.relation', $request->relation);
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', function ($wali) {
                return $wali->user?->name ?? '-';
            })
            ->addColumn('email', function ($wali) {
                return $wali->user?->email ?? '-';
            })
            ->addColumn('phone', function ($wali) {
                return $wali->user?->phone ?? '-';
            })
            ->addColumn('relation_label', function ($wali) {
                $relations = [
                    'ayah' => '<span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">Ayah</span>',
                    'ibu' => '<span class="px-2 py-1 bg-pink-100 text-pink-800 text-xs font-semibold rounded">Ibu</span>',
                    'wali' => '<span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">Wali</span>',
                ];
                return $relations[$wali->relation] ?? '-';
            })
            ->addColumn('children_count', function ($wali) {
                return $wali->santriProfiles->count();
            })
            ->addColumn('children', function ($wali) {
                if ($wali->santriProfiles->isEmpty()) {
                    return '<span class="text-gray-500 text-sm">Belum ada santri</span>';
                }

                $children = $wali->santriProfiles->take(2)->map(function ($santri) {
                    return '<span class="inline-block px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded mr-1 mb-1">' .
                        e($santri->user?->name ?? '-') . '</span>';
                })->join('');

                if ($wali->santriProfiles->count() > 2) {
                    $children .= '<span class="text-xs text-gray-600">+' . ($wali->santriProfiles->count() - 2) . ' lainnya</span>';
                }

                return $children;
            })
            ->addColumn('total_donations', function ($wali) {
                $total = $wali->appreciationFunds()->where('status', 'verified')->sum('amount');
                return 'Rp ' . number_format($total, 0, ',', '.');
            })
            ->addColumn('status_badge', function ($wali) {
                $status = $wali->user?->status ?? 'unknown';
                $badges = [
                    'active' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Aktif</span>',
                    'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Pending</span>',
                    'inactive' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fas fa-ban mr-1"></i>Inactive</span>',
                ];
                return $badges[$status] ?? $badges['inactive'];
            })
            ->addColumn('status', function ($wali) {
                return $wali->user?->status ?? 'unknown';
            })
            ->addColumn('action', function ($wali) {
                $actions = '<div class="flex items-center gap-2 justify-center">';

                // View button
                $actions .= '<a href="' . route('users.wali.show', $wali->id) . '" 
                              class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" 
                              title="Detail">
                              <i class="fas fa-eye"></i>
                            </a>';

                // Edit button
                if (auth()->user()->can('edit_users')) {
                    $actions .= '<a href="' . route('users.wali.edit', $wali->id) . '" 
                                  class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" 
                                  title="Edit">
                                  <i class="fas fa-edit"></i>
                                </a>';
                }

                // Delete button
                if (auth()->user()->can('delete_users')) {
                    $actions .= '<button onclick="deleteWali(' . $wali->id . ')" 
                                  class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" 
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </button>';
                }

                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['relation_label', 'children', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new wali
     */
    public function create()
    {
        $this->authorize('create_users');
        return view('users.wali.create');
    }

    /**
     * Store a newly created wali
     */
    public function store(CreateWaliRequest $request)
    {
        try {
            $pesantrenId = session('current_pesantren_id') ?? $request->user()->pesantren_id;

            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password ?: Str::random(8),
                'user_type' => 'wali',
                'status' => 'active',
                'pesantren_id' => $pesantrenId,
            ];

            $profileData = [
                'nik' => $request->nik,
                'relation' => $request->relation,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'pesantren_id' => $pesantrenId,
            ];

            $user = $this->userService->createWali($userData, $profileData);

            return redirect()
                ->route('users.wali.index')
                ->with('success', 'Wali berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan wali: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified wali
     */
    public function show($id)
    {
        $wali = WaliProfile::with([
            'user',
            'santriProfiles.user',
            'santriProfiles.hafalans' => function ($q) {
                $q->where('status', 'verified');
            },
            'appreciationFunds' => function ($q) {
                $q->latest()->take(10);
            }
        ])->findOrFail($id);

        // Statistics
        $stats = [
            'total_children' => $wali->santriProfiles->count(),
            'total_donations' => $wali->appreciationFunds()->where('status', 'verified')->sum('amount'),
            'pending_donations' => $wali->appreciationFunds()->where('status', 'pending')->count(),
            'total_hafalan' => $wali->santriProfiles->sum(function ($santri) {
                return $santri->hafalans()->where('status', 'verified')->count();
            }),
        ];

        // Children statistics
        $childrenStats = $wali->santriProfiles->map(function ($santri) {
            return [
                'santri' => $santri,
                'total_hafalan' => $santri->hafalans()->count(),
                'verified_hafalan' => $santri->hafalans()->where('status', 'verified')->count(),
                'progress_percentage' => $santri->progress_percentage,
            ];
        });

        return view('users.wali.show', compact('wali', 'stats', 'childrenStats'));
    }

    /**
     * Show the form for editing the specified wali
     */
    public function edit($id)
    {
        $this->authorize('edit_users');

        $wali = WaliProfile::with(['user', 'santriProfiles', 'appreciationFunds'])->findOrFail($id);
        return view('users.wali.edit', compact('wali'));
    }

    /**
     * Update the specified wali
     */
    public function update(UpdateWaliRequest $request, $id)
    {
        try {
            $wali = WaliProfile::findOrFail($id);

            // Update user data
            $wali->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Update profile data
            $wali->update([
                'nik' => $request->nik,
                'relation' => $request->relation,
                'occupation' => $request->occupation,
                'address' => $request->address,
            ]);

            return redirect()
                ->route('users.wali.index')
                ->with('success', 'Data wali berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate wali: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified wali
     */
    public function destroy($id)
    {
        try {
            $this->authorize('delete_users');

            $wali = WaliProfile::findOrFail($id);

            // Check if wali has children
            if ($wali->santriProfiles()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus wali yang masih memiliki santri!'
                ], 400);
            }

            $wali->user->delete(); // Soft delete user
            $wali->delete(); // Also soft delete wali profile
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus wali: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats()
    {
        // For Super Admin, return global stats across all pesantrens
        if (auth()->user()->isSuperAdmin()) {
            $stats = [
                'total_wali' => WaliProfile::whereHas('user')->count(),
                'active_wali' => WaliProfile::whereHas('user', function($q) {
                    $q->where('status', 'active');
                })->count(),
                'total_santri' => DB::table('santri_profiles')
                    ->join('wali_profiles', 'santri_profiles.wali_id', '=', 'wali_profiles.id')
                    ->join('users', 'wali_profiles.user_id', '=', 'users.id')
                    ->whereNull('users.deleted_at')
                    ->count(),
                'total_donations' => 'Rp ' . number_format(
                    DB::table('donations')
                        ->join('wali_profiles', 'donations.wali_id', '=', 'wali_profiles.id')
                        ->join('users', 'wali_profiles.user_id', '=', 'users.id')
                        ->whereIn('donations.status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
                        ->whereNull('users.deleted_at')
                        ->sum('donations.amount') ?? 0,
                    0,
                    ',',
                    '.'
                ),
            ];
            return response()->json($stats);
        }

        // For pesantren admins, return pesantren-specific stats
        $pesantrenId = session('current_pesantren_id');

        $stats = [
            'total_wali' => WaliProfile::where('pesantren_id', $pesantrenId)
                ->whereHas('user')
                ->count(),
            'active_wali' => WaliProfile::where('pesantren_id', $pesantrenId)
                ->whereHas('user', function($q) {
                    $q->where('status', 'active');
                })
                ->count(),
            'total_santri' => DB::table('santri_profiles')
                ->join('wali_profiles', 'santri_profiles.wali_id', '=', 'wali_profiles.id')
                ->join('users', 'wali_profiles.user_id', '=', 'users.id')
                ->where('wali_profiles.pesantren_id', $pesantrenId)
                ->whereNull('users.deleted_at')
                ->count(),
            'total_donations' => 'Rp ' . number_format(
                DB::table('donations')
                    ->join('wali_profiles', 'donations.wali_id', '=', 'wali_profiles.id')
                    ->join('users', 'wali_profiles.user_id', '=', 'users.id')
                    ->where('wali_profiles.pesantren_id', $pesantrenId)
                    ->whereIn('donations.status', ['verified', 'transferred', 'available', 'requested', 'disbursed'])
                    ->whereNull('users.deleted_at')
                    ->sum('donations.amount') ?? 0,
                0,
                ',',
                '.'
            ),
        ];

        return response()->json($stats);
    }
}
