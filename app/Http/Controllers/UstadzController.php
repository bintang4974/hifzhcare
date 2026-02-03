<?php

namespace App\Http\Controllers;

use App\Http\Requests\Ustadz\CreateUstadzRequest;
use App\Http\Requests\Ustadz\UpdateUstadzRequest;
use App\Models\UstadzProfile;
use App\Services\User\UserService;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class UstadzController extends Controller
{
    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected UserService $userService
    ) {
        $this->middleware('can:manage_users');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('users.ustadz.index');
    }

    protected function datatable(Request $request)
    {
        $query = UstadzProfile::with(['user', 'activeClasses'])
            ->select('ustadz_profiles.*');

        if ($request->filled('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', fn($u) => $u->user->name)
            ->addColumn('nip', fn($u) => $u->nip)
            ->addColumn('email', fn($u) => $u->user->email)
            ->addColumn('phone', fn($u) => $u->user->phone)
            ->addColumn('classes', fn($u) => $u->activeClasses->pluck('name')->join(', ') ?: '-')
            ->addColumn('verified_today', function ($u) {
                return $u->verifiedHafalans()->whereDate('verified_at', now()->toDateString())->count();
            })
            ->addColumn('status_badge', function ($u) {
                return match ($u->user->status) {
                    'active' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>',
                    'pending' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
                    'inactive' => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Tidak Aktif</span>',
                    default => '<span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">' . $u->user->status . '</span>',
                };
            })
            ->addColumn('action', function ($u) {
                $actions = '';
                $actions .= '<a href="' . route('users.ustadz.show', $u->id) . '" class="text-blue-600 hover:text-blue-900 mr-2" title="Lihat Detail"><i class="fas fa-eye"></i></a>';

                if (auth()->user()->can('edit_users')) {
                    $actions .= '<a href="' . route('users.ustadz.edit', $u->id) . '" class="text-yellow-600 hover:text-yellow-900 mr-2" title="Edit"><i class="fas fa-edit"></i></a>';
                }

                if ($u->user->status === 'pending' && auth()->user()->can('activate_users')) {
                    $actions .= '<button onclick="activateUstadz(' . $u->id . ')" class="text-green-600 hover:text-green-900 mr-2" title="Aktivasi"><i class="fas fa-check-circle"></i></button>';
                }

                if (auth()->user()->can('delete_users')) {
                    $actions .= '<button onclick="deleteUstadz(' . $u->id . ')" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button>';
                }

                return $actions;
            })
            ->rawColumns(['status_badge', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('users.ustadz.create');
    }

    public function store(CreateUstadzRequest $request)
    {
        try {
            $userData = [
                'pesantren_id' => auth()->user()->pesantren_id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => $request->password ?? Str::random(10),
                'status' => 'pending',
            ];

            $profileData = [
                'nip' => $request->nip,
                'specialization' => $request->specialization,
                'join_date' => $request->join_date,
                'address' => $request->address,
            ];

            $user = $this->userRepository->createWithProfile($userData, $profileData, 'ustadz');
            $user->assignRole('Ustadz');

            return redirect()->route('users.ustadz.index')->with('success', 'Ustadz berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan ustadz: ' . $e->getMessage());
        }
    }

    public function show(UstadzProfile $ustadz)
    {
        $ustadz->load(['user', 'activeClasses.activeUstadz', 'verifiedHafalans']);
        return view('users.ustadz.show', compact('ustadz'));
    }

    public function edit(UstadzProfile $ustadz)
    {
        $ustadz->load('user');
        return view('users.ustadz.edit', compact('ustadz'));
    }

    public function update(UpdateUstadzRequest $request, UstadzProfile $ustadz)
    {
        try {
            $ustadz->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $ustadz->update([
                'nip' => $request->nip,
                'specialization' => $request->specialization,
                'join_date' => $request->join_date,
                'address' => $request->address,
            ]);

            return redirect()->route('users.ustadz.index')->with('success', 'Data ustadz berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data ustadz: ' . $e->getMessage());
        }
    }

    public function destroy(UstadzProfile $ustadz)
    {
        try {
            $ustadz->user->delete();

            return response()->json(['success' => true, 'message' => 'Ustadz berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus ustadz: ' . $e->getMessage()], 500);
        }
    }

    public function activate(Request $request, UstadzProfile $ustadz)
    {
        $this->authorize('activate_users');

        try {
            $password = $request->password ?: Str::random(10);
            $user = $this->userService->activateAccount($ustadz->user_id, $password);

            return response()->json(['success' => true, 'message' => 'Akun ustadz berhasil diaktifkan.', 'password' => $password]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengaktifkan akun: ' . $e->getMessage()], 500);
        }
    }

    public function stats(Request $request)
    {
        $pesantrenId = auth()->user()->pesantren_id;

        $base = UstadzProfile::whereHas('user', fn($q) => $q->where('pesantren_id', $pesantrenId));

        $total = $base->count();
        $active = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'active'))->count();
        $pending = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'pending'))->count();
        $totalClasses = UstadzProfile::whereHas('user', fn($q) => $q->where('pesantren_id', $pesantrenId))->withCount('activeClasses')->get()->sum('active_classes_count');

        return response()->json(['total' => $total, 'active' => $active, 'pending' => $pending, 'total_classes' => $totalClasses]);
    }
}
