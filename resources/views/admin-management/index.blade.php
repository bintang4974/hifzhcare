@extends('layouts.app-enhanced')

@section('title', 'Manajemen Admin Pesantren')
@section('breadcrumb', 'Super Admin / Admin Pesantren')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Admin Pesantren</h1>
                <p class="text-gray-600 mt-1">Kelola admin untuk setiap pesantren</p>
            </div>
            <a href="{{ route('superadmin.admins.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <i class="fas fa-user-plus mr-2"></i>Tambah Admin
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-indigo-100 text-sm mb-1">Total Admin</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_admins'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Aktif</p>
                        <h3 class="text-4xl font-bold">{{ $stats['active_admins'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Pending</p>
                        <h3 class="text-4xl font-bold">{{ $stats['pending_admins'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Pesantren Terkelola</p>
                        <h3 class="text-4xl font-bold">{{ $stats['managed_pesantrens'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Pesantren</label>
                    <select name="pesantren_id" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Semua Pesantren</option>
                        @foreach ($pesantrens as $pesantren)
                            <option value="{{ $pesantren->id }}"
                                {{ request('pesantren_id') == $pesantren->id ? 'selected' : '' }}>
                                {{ $pesantren->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <a href="{{ route('superadmin.admins.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Admin List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Daftar Admin Pesantren</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @forelse($admins as $admin)
                        <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-all border border-gray-200">
                            <div class="flex items-start justify-between">
                                <!-- Admin Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div
                                            class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                                            {{ substr($admin->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900">{{ $admin->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-envelope mr-1"></i>{{ $admin->email }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <!-- Pesantren -->
                                        <div class="flex items-start">
                                            <i class="fas fa-building text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Pesantren</p>
                                                @if ($admin->pesantren)
                                                    <p class="text-sm font-bold text-gray-900">
                                                        {{ $admin->pesantren->name }}</p>
                                                    <p class="text-xs text-gray-600">{{ $admin->pesantren->code }}</p>
                                                @else
                                                    <p class="text-sm text-red-500 italic">Belum di-assign</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Contact -->
                                        <div class="flex items-start">
                                            <i class="fas fa-phone text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Telepon</p>
                                                <p class="text-sm text-gray-900">{{ $admin->phone ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <!-- Last Login -->
                                        <div class="flex items-start">
                                            <i class="fas fa-clock text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Last Login</p>
                                                <p class="text-sm text-gray-900">
                                                    {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Belum pernah' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stats Row -->
                                    <div class="flex items-center gap-4">
                                        <span class="text-xs text-gray-600">
                                            <i class="fas fa-calendar mr-1"></i>Bergabung
                                            {{ $admin->created_at->format('d M Y') }}
                                        </span>
                                        @if ($admin->email_verified_at)
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                <i class="fas fa-check-circle mr-1"></i>Email Verified
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">
                                                <i class="fas fa-exclamation-circle mr-1"></i>Email Belum Verified
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Status & Actions -->
                                <div class="flex flex-col items-end gap-3">
                                    <!-- Status Badge -->
                                    <div>
                                        @if ($admin->status === 'active')
                                            <span
                                                class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @elseif($admin->status === 'pending')
                                            <span
                                                class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-clock mr-1"></i>Pending
                                            </span>
                                        @else
                                            <span
                                                class="px-3 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-ban mr-1"></i>Inactive
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex gap-2">
                                        @if (!$admin->pesantren_id)
                                            <button onclick="showAssignModal({{ $admin->id }}, '{{ $admin->name }}')"
                                                class="p-2 bg-purple-100 text-purple-600 hover:bg-purple-200 rounded-lg transition"
                                                title="Assign ke Pesantren">
                                                <i class="fas fa-link"></i>
                                            </button>
                                        @endif

                                        @if ($admin->status === 'pending')
                                            <button onclick="activateAdmin({{ $admin->id }})"
                                                class="p-2 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg transition"
                                                title="Aktivasi">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        @endif

                                        <button onclick="toggleStatus({{ $admin->id }})"
                                            class="p-2 {{ $admin->status === 'active' ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} rounded-lg transition"
                                            title="{{ $admin->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $admin->status === 'active' ? 'pause' : 'play' }}"></i>
                                        </button>

                                        <a href="{{ route('superadmin.admins.edit', $admin->id) }}"
                                            class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button onclick="deleteAdmin({{ $admin->id }})"
                                            class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-user-shield text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada admin terdaftar</p>
                            <a href="{{ route('superadmin.admins.create') }}"
                                class="inline-block mt-4 px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                Tambah Admin Pertama
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($admins->hasPages())
                    <div class="mt-6">
                        {{ $admins->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assign Modal -->
    <div id="assignModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Assign ke Pesantren</h3>
            <p class="text-sm text-gray-600 mb-4">Assign admin <strong id="adminName"></strong> ke pesantren:</p>

            <form id="assignForm" method="POST">
                @csrf
                <select name="pesantren_id" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 mb-4">
                    <option value="">Pilih Pesantren</option>
                    @foreach ($pesantrens as $pesantren)
                        <option value="{{ $pesantren->id }}">{{ $pesantren->name }} ({{ $pesantren->code }})</option>
                    @endforeach
                </select>

                <div class="flex gap-3">
                    <button type="button" onclick="closeAssignModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Assign
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function showAssignModal(adminId, adminName) {
            document.getElementById('adminName').textContent = adminName;
            document.getElementById('assignForm').action = `/superadmin/admins/${adminId}/assign`;
            document.getElementById('assignModal').classList.remove('hidden');
        }

        function closeAssignModal() {
            document.getElementById('assignModal').classList.add('hidden');
        }

        function activateAdmin(id) {
            if (!confirm('Aktivasi admin ini?')) return;

            fetch(`/superadmin/admins/${id}/activate`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function toggleStatus(id) {
            if (!confirm('Ubah status admin ini?')) return;

            fetch(`/superadmin/admins/${id}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function deleteAdmin(id) {
            if (!confirm('PERHATIAN: Menghapus admin akan menghapus akses mereka ke sistem. Lanjutkan?')) return;

            fetch(`/superadmin/admins/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        // Close modal when clicking outside
        document.getElementById('assignModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAssignModal();
            }
        });
    </script>
@endpush
