<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ustadz\CreateUstadzRequest;
use App\Http\Requests\Ustadz\UpdateUstadzRequest;
use App\Models\User;
use App\Models\UstadzProfile;
use App\Services\User\UserService;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UstadzController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->middleware('auth');
        $this->middleware('tenant');
    }

    /**
     * Display a listing of ustadz
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('users.ustadz.index');
    }

    /**
     * DataTable for ustadz
     */
    protected function datatable(Request $request)
    {
        $query = UstadzProfile::with(['user', 'activeClasses'])
            ->where('ustadz_profiles.pesantren_id', session('current_pesantren_id'));

        // Filters
        if ($request->filled('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('users.status', $request->status);
            });
        }

        if ($request->filled('specialization')) {
            $query->where('ustadz_profiles.specialization', 'like', '%' . $request->specialization . '%');
        }

        return DataTables::eloquent($query)
            ->orderColumn('name', 'users.name $1')
            ->orderColumn('email', 'users.email $1')
            ->orderColumn('phone', 'users.phone $1')
            ->orderColumn('nip', 'ustadz_profiles.nip $1')
            ->addIndexColumn()
            ->addColumn('name', function ($ustadz) {
                return $ustadz->user->name;
            })
            ->addColumn('email', function ($ustadz) {
                return $ustadz->user->email ?? '-';
            })
            ->addColumn('phone', function ($ustadz) {
                return $ustadz->user->phone;
            })
            ->addColumn('classes_count', function ($ustadz) {
                return $ustadz->activeClasses->count();
            })
            ->addColumn('classes', function ($ustadz) {
                if ($ustadz->activeClasses->isEmpty()) {
                    return '<span class="text-gray-500 text-sm">Belum ada kelas</span>';
                }

                $classes = $ustadz->activeClasses->take(2)->map(function ($class) {
                    return '<span class="inline-block px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded mr-1 mb-1">' .
                        e($class->name) . '</span>';
                })->join('');

                if ($ustadz->activeClasses->count() > 2) {
                    $classes .= '<span class="text-xs text-gray-600">+' . ($ustadz->activeClasses->count() - 2) . ' lainnya</span>';
                }

                return $classes;
            })
            ->addColumn('verified_today', function ($ustadz) {
                $count = $ustadz->verifiedHafalans()
                    ->whereDate('verified_at', today())
                    ->count();
                return $count;
            })
            ->addColumn('status_badge', function ($ustadz) {
                $status = $ustadz->user->status;
                $badges = [
                    'active' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800"><i class="fas fa-check-circle mr-1"></i>Aktif</span>',
                    'pending' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800"><i class="fas fa-clock mr-1"></i>Pending</span>',
                    'inactive' => '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800"><i class="fas fa-ban mr-1"></i>Inactive</span>',
                ];
                return $badges[$status] ?? $badges['inactive'];
            })
            ->addColumn('action', function ($ustadz) {
                $actions = '<div class="flex items-center gap-2 justify-center">';

                // View button
                $actions .= '<a href="' . route('users.ustadz.show', $ustadz->id) . '" 
                              class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" 
                              title="Detail">
                              <i class="fas fa-eye"></i>
                            </a>';

                // Edit button
                if (auth()->user()->can('edit_users')) {
                    $actions .= '<a href="' . route('users.ustadz.edit', $ustadz->id) . '" 
                                  class="p-2 bg-yellow-100 text-yellow-600 rounded-lg hover:bg-yellow-200 transition" 
                                  title="Edit">
                                  <i class="fas fa-edit"></i>
                                </a>';
                }

                // Activate button (if pending)
                if ($ustadz->user->status === 'pending' && auth()->user()->can('activate_users')) {
                    $actions .= '<button onclick="activateUstadz(' . $ustadz->id . ')" 
                                  class="p-2 bg-green-100 text-green-600 rounded-lg hover:bg-green-200 transition" 
                                  title="Aktivasi">
                                  <i class="fas fa-user-check"></i>
                                </button>';
                }

                // Delete button
                if (auth()->user()->can('delete_users')) {
                    $actions .= '<button onclick="deleteUstadz(' . $ustadz->id . ')" 
                                  class="p-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" 
                                  title="Hapus">
                                  <i class="fas fa-trash"></i>
                                </button>';
                }

                $actions .= '</div>';
                return $actions;
            })
            ->rawColumns(['classes', 'status_badge', 'action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new ustadz
     */
    public function create()
    {
        $this->authorize('create_users');
        return view('users.ustadz.create');
    }

    /**
     * Store a newly created ustadz
     */
    public function store(CreateUstadzRequest $request)
    {
        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password ?: Str::random(8),
                'user_type' => 'ustadz',
                'status' => 'active',
                'pesantren_id' => session('current_pesantren_id'),
            ];

            $profileData = [
                'nip' => $request->nip,
                'specialization' => $request->specialization,
                'join_date' => $request->join_date,
                'address' => $request->address,
                'pesantren_id' => session('current_pesantren_id'),
            ];

            $user = $this->userService->createUstadz($userData, $profileData);

            return redirect()
                ->route('users.ustadz.index')
                ->with('success', 'Ustadz berhasil ditambahkan!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan ustadz: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified ustadz
     */
    public function show($id)
    {
        $ustadz = UstadzProfile::with([
            'user',
            'activeClasses.activeSantri',
            'verifiedHafalans' => function ($q) {
                $q->latest()->take(10);
            }
        ])->findOrFail($id);

        // Statistics
        $stats = [
            'total_classes' => $ustadz->activeClasses->count(),
            'total_students' => $ustadz->activeClasses->sum(function ($class) {
                return $class->activeSantri->count();
            }),
            'total_verified' => $ustadz->verifiedHafalans()->count(),
            'verified_today' => $ustadz->verifiedHafalans()->whereDate('verified_at', today())->count(),
            'verified_this_month' => $ustadz->verifiedHafalans()->whereMonth('verified_at', now()->month)->count(),
            'pending_hafalan' => $ustadz->verifiedHafalans()->where('status', 'pending')->count(),
            'total_appreciation' => $ustadz->appreciations()->where('status', 'verified')->sum('amount'),
        ];

        return view('users.ustadz.show', compact('ustadz', 'stats'));
    }

    /**
     * Show the form for editing the specified ustadz
     */
    public function edit($id)
    {
        $this->authorize('edit_users');

        $ustadz = UstadzProfile::with('user')->findOrFail($id);
        return view('users.ustadz.edit', compact('ustadz'));
    }

    /**
     * Update the specified ustadz
     */
    public function update(UpdateUstadzRequest $request, $id)
    {
        try {
            $ustadz = UstadzProfile::findOrFail($id);

            // Update user data
            $ustadz->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            // Update profile data
            $ustadz->update([
                'nip' => $request->nip,
                'specialization' => $request->specialization,
                'join_date' => $request->join_date,
                'address' => $request->address,
            ]);

            return redirect()
                ->route('users.ustadz.index')
                ->with('success', 'Data ustadz berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate ustadz: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified ustadz
     */
    public function destroy($id)
    {
        try {
            $this->authorize('delete_users');

            $ustadz = UstadzProfile::findOrFail($id);

            // Check if ustadz has active classes
            if ($ustadz->activeClasses()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menghapus ustadz yang masih memiliki kelas aktif!'
                ], 400);
            }

            $ustadz->user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ustadz berhasil dihapus!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus ustadz: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Activate ustadz account
     */
    public function activate(Request $request, $id)
    {
        try {
            $this->authorize('activate_users');

            $ustadz = UstadzProfile::findOrFail($id);

            $password = $request->password ?: Str::random(8);

            $this->userService->activateAccount($ustadz->user, $password);

            return response()->json([
                'success' => true,
                'message' => 'Akun ustadz berhasil diaktifkan!',
                'password' => $password
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengaktifkan akun: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get statistics for ustadz
     */
    public function stats()
    {
        $pesantrenId = session('current_pesantren_id');

        $stats = [
            'total' => UstadzProfile::where('pesantren_id', $pesantrenId)->count(),
            'active' => User::where('user_type', 'ustadz')
                ->where('pesantren_id', $pesantrenId)
                ->where('status', 'active')
                ->count(),
            'pending' => User::where('user_type', 'ustadz')
                ->where('pesantren_id', $pesantrenId)
                ->where('status', 'pending')
                ->count(),
            'total_classes' => DB::table('class_ustadz')
                ->join('ustadz_profiles', 'class_ustadz.ustadz_profile_id', '=', 'ustadz_profiles.id')
                ->where('ustadz_profiles.pesantren_id', $pesantrenId)
                ->count(),
        ];

        return response()->json($stats);
    }
}
