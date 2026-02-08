<?php

namespace App\Http\Controllers;

use App\Models\{User, Pesantren, SantriProfile, UstadzProfile, WaliProfile, StakeholderProfile, GeneralUserProfile};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AdminManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:Super Admin');
    }

    /**
     * Display admin list
     */
    public function index(Request $request)
    {
        $query = User::where('user_type', 'admin')
            ->with(['pesantren']);

        // Filter by pesantren
        if ($request->filled('pesantren_id')) {
            $query->where('pesantren_id', $request->pesantren_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $admins = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total_admins' => User::where('user_type', 'admin')->count(),
            'active_admins' => User::where('user_type', 'admin')->where('status', 'active')->count(),
            'pending_admins' => User::where('user_type', 'admin')->where('status', 'pending')->count(),
            'managed_pesantrens' => User::where('user_type', 'admin')
                ->whereNotNull('pesantren_id')
                ->distinct('pesantren_id')
                ->count('pesantren_id'),
        ];

        // Get all pesantrens for filter
        $pesantrens = Pesantren::where('status', 'active')
            ->orderBy('name')
            ->get();

        return view('admin-management.index', compact('admins', 'stats', 'pesantrens'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $pesantrens = Pesantren::where('status', 'active')
            ->withCount('santriProfiles')
            ->orderBy('name')
            ->get();

        return view('admin-management.create', compact('pesantrens'));
    }

    /**
     * Store new admin
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => ['required', 'confirmed', Password::min(8)],
            'pesantren_id' => 'required|exists:pesantrens,id',
            'status' => 'required|in:pending,active,inactive',
            'send_credentials_email' => 'boolean',
        ]);

        // Create user
        $admin = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'user_type' => 'admin',
            'pesantren_id' => $validated['pesantren_id'],
            'status' => $validated['status'],
            'email_verified_at' => $validated['status'] === 'active' ? now() : null,
        ]);

        // Send credentials email if requested
        if ($request->boolean('send_credentials_email')) {
            // Mail::to($admin->email)->send(new AdminCredentials($admin, $validated['password']));
            // TODO: Implement email sending
        }

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil ditambahkan!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $admin = User::where('user_type', 'admin')
            ->with('pesantren')
            ->findOrFail($id);

        $pesantrens = Pesantren::where('status', 'active')
            ->withCount('santriProfiles')
            ->orderBy('name')
            ->get();

        return view('admin-management.edit', compact('admin', 'pesantrens'));
    }

    /**
     * Update admin
     */
    public function update(Request $request, $id)
    {
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20|unique:users,phone,' . $id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
            'pesantren_id' => 'nullable|exists:pesantrens,id',
            'status' => 'required|in:pending,active,inactive',
        ]);

        // Update basic info
        $admin->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'pesantren_id' => $validated['pesantren_id'],
            'status' => $validated['status'],
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $admin->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Auto-verify email if status is active
        if ($validated['status'] === 'active' && !$admin->email_verified_at) {
            $admin->update(['email_verified_at' => now()]);
        }

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil diupdate!');
    }

    /**
     * Delete admin
     */
    public function destroy($id)
    {
        try {
            $admin = User::where('user_type', 'admin')->findOrFail($id);

            // Check if admin has active sessions or important data
            // You might want to add more checks here

            $admin->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Assign admin to pesantren
     */
    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'pesantren_id' => 'required|exists:pesantrens,id',
        ]);

        $admin = User::where('user_type', 'admin')->findOrFail($id);

        // Check if pesantren already has an admin
        $existingAdmin = User::where('user_type', 'admin')
            ->where('pesantren_id', $validated['pesantren_id'])
            ->where('id', '!=', $id)
            ->first();

        if ($existingAdmin) {
            return back()->with('warning', "Pesantren ini sudah memiliki admin: {$existingAdmin->name}. Admin baru akan menjadi admin tambahan.");
        }

        $admin->update([
            'pesantren_id' => $validated['pesantren_id'],
        ]);

        return redirect()
            ->route('superadmin.admins.index')
            ->with('success', 'Admin berhasil di-assign ke pesantren!');
    }

    /**
     * Activate admin
     */
    public function activate($id)
    {
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $admin->update([
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil diaktifkan!'
        ]);
    }

    /**
     * Toggle admin status
     */
    public function toggleStatus($id)
    {
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $newStatus = $admin->status === 'active' ? 'inactive' : 'active';
        
        $admin->update([
            'status' => $newStatus,
            'email_verified_at' => $newStatus === 'active' ? ($admin->email_verified_at ?? now()) : $admin->email_verified_at,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Status admin berhasil diubah menjadi {$newStatus}",
            'status' => $newStatus
        ]);
    }

    /**
     * Unassign admin from pesantren
     */
    public function unassign($id)
    {
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $admin->update([
            'pesantren_id' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Admin berhasil di-unassign dari pesantren!'
        ]);
    }

    /**
     * Reset admin password
     */
    public function resetPassword(Request $request, $id)
    {
        $validated = $request->validate([
            'new_password' => ['required', 'confirmed', Password::min(8)],
            'send_email' => 'boolean',
        ]);

        $admin = User::where('user_type', 'admin')->findOrFail($id);

        $admin->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        // Send email if requested
        if ($request->boolean('send_email')) {
            // Mail::to($admin->email)->send(new PasswordReset($admin, $validated['new_password']));
            // TODO: Implement email sending
        }

        return response()->json([
            'success' => true,
            'message' => 'Password admin berhasil direset!'
        ]);
    }

    /**
     * Send login credentials via email
     */
    public function sendCredentials($id)
    {
        $admin = User::where('user_type', 'admin')->findOrFail($id);

        // Generate temporary password
        $tempPassword = bin2hex(random_bytes(4)); // 8 character random password
        
        $admin->update([
            'password' => Hash::make($tempPassword),
        ]);

        // Send email
        // Mail::to($admin->email)->send(new AdminCredentials($admin, $tempPassword));
        // TODO: Implement email sending

        return response()->json([
            'success' => true,
            'message' => 'Email kredensial berhasil dikirim!',
            'temp_password' => $tempPassword // Only for development, remove in production
        ]);
    }
}
