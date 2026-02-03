<?php

namespace App\Http\Controllers;

use App\Http\Requests\Wali\CreateWaliRequest;
use App\Http\Requests\Wali\UpdateWaliRequest;
use App\Models\WaliProfile;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Str;

class WaliController extends Controller
{
    public function __construct(protected UserRepositoryInterface $userRepository)
    {
        $this->middleware('can:manage_users');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->datatable($request);
        }

        return view('users.wali.index');
    }

    public function stats(Request $request)
    {
        $pesantrenId = auth()->user()->pesantren_id;

        $base = WaliProfile::whereHas('user', fn($q) => $q->where('pesantren_id', $pesantrenId));

        $total = $base->count();
        $active = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'active'))->count();
        $pending = (clone $base)->whereHas('user', fn($q) => $q->where('status', 'pending'))->count();
        $totalSantri = WaliProfile::whereHas('user', fn($q) => $q->where('pesantren_id', $pesantrenId))->withCount('santriProfiles')->get()->sum('santri_profiles_count');

        return response()->json(['total' => $total, 'active' => $active, 'pending' => $pending, 'total_santri' => $totalSantri]);
    }

    protected function datatable(Request $request)
    {
        $query = WaliProfile::with(['user'])->select('wali_profiles.*');

        if ($request->filled('status')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('name', fn($w) => $w->user->name)
            ->addColumn('nik', fn($w) => $w->nik)
            ->addColumn('email', fn($w) => $w->user->email)
            ->addColumn('phone', fn($w) => $w->user->phone)
            ->addColumn('relation', fn($w) => $w->relation)
            ->addColumn('action', function ($w) {
                $actions = '';
                $actions .= '<a href="' . route('users.wali.show', $w->id) . '" class="text-blue-600 hover:text-blue-900 mr-2" title="Lihat Detail"><i class="fas fa-eye"></i></a>';

                if (auth()->user()->can('edit_users')) {
                    $actions .= '<a href="' . route('users.wali.edit', $w->id) . '" class="text-yellow-600 hover:text-yellow-900 mr-2" title="Edit"><i class="fas fa-edit"></i></a>';
                }

                if (auth()->user()->can('delete_users')) {
                    $actions .= '<button onclick="deleteWali(' . $w->id . ')" class="text-red-600 hover:text-red-900" title="Hapus"><i class="fas fa-trash"></i></button>';
                }

                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        return view('users.wali.create');
    }

    public function store(CreateWaliRequest $request)
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
                'nik' => $request->nik,
                'relation' => $request->relation,
                'occupation' => $request->occupation,
                'address' => $request->address,
            ];

            $user = $this->userRepository->createWithProfile($userData, $profileData, 'wali');
            $user->assignRole('Wali Santri');

            return redirect()->route('users.wali.index')->with('success', 'Wali berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal menambahkan wali: ' . $e->getMessage());
        }
    }

    public function show(WaliProfile $wali)
    {
        $wali->load(['user', 'santriProfiles']);
        return view('users.wali.show', compact('wali'));
    }

    public function edit(WaliProfile $wali)
    {
        $wali->load('user');
        return view('users.wali.edit', compact('wali'));
    }

    public function update(UpdateWaliRequest $request, WaliProfile $wali)
    {
        try {
            $wali->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
            ]);

            $wali->update([
                'nik' => $request->nik,
                'relation' => $request->relation,
                'occupation' => $request->occupation,
                'address' => $request->address,
            ]);

            return redirect()->route('users.wali.index')->with('success', 'Data wali berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memperbarui data wali: ' . $e->getMessage());
        }
    }

    public function destroy(WaliProfile $wali)
    {
        try {
            $wali->user->delete();

            return response()->json(['success' => true, 'message' => 'Wali berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal menghapus wali: ' . $e->getMessage()], 500);
        }
    }
}
