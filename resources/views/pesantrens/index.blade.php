@extends('layouts.app-enhanced')

@section('title', 'Manajemen Pesantren')
@section('breadcrumb', 'Super Admin / Pesantren')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Pesantren</h1>
                <p class="text-gray-600 mt-1">Kelola semua pesantren dalam sistem</p>
            </div>
            <a href="{{ route('superadmin.pesantrens.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                <i class="fas fa-plus-circle mr-2"></i>Tambah Pesantren
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Pesantren</p>
                        <h3 class="text-4xl font-bold">{{ $pesantrens->total() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Aktif</p>
                        <h3 class="text-4xl font-bold">{{ $pesantrens->where('status', 'active')->count() }}</h3>
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
                        <h3 class="text-4xl font-bold">{{ $pesantrens->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-4xl font-bold">{{ $pesantrens->sum('current_santri_count') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pesantren List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Daftar Pesantren</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @forelse($pesantrens as $pesantren)
                        <div class="bg-gray-50 rounded-xl p-6 hover:shadow-md transition-all border border-gray-200">
                            <div class="flex items-start justify-between">
                                <!-- Pesantren Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div
                                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg flex items-center justify-center text-white">
                                            <i class="fas fa-mosque text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900">{{ $pesantren->name }}</h4>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-code mr-1"></i>{{ $pesantren->code }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <!-- Location -->
                                        <div class="flex items-start">
                                            <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Alamat</p>
                                                <p class="text-sm text-gray-900">{{ Str::limit($pesantren->address, 50) }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Contact -->
                                        <div class="flex items-start">
                                            <i class="fas fa-phone text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Kontak</p>
                                                <p class="text-sm text-gray-900">{{ $pesantren->phone }}</p>
                                                @if ($pesantren->email)
                                                    <p class="text-xs text-gray-600">{{ $pesantren->email }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Capacity -->
                                        <div class="flex items-start">
                                            <i class="fas fa-users text-gray-400 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Kapasitas</p>
                                                <div class="flex items-center gap-2">
                                                    <span
                                                        class="text-sm font-bold text-gray-900">{{ $pesantren->current_santri_count ?? 0 }}</span>
                                                    <span class="text-xs text-gray-500">/
                                                        {{ $pesantren->max_santri ?? 0 }}</span>
                                                </div>
                                                @if ($pesantren->max_santri > 0)
                                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                                                        <div class="bg-blue-600 h-1.5 rounded-full"
                                                            style="width: {{ min(100, ($pesantren->current_santri_count / $pesantren->max_santri) * 100) }}%">
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Stats Row -->
                                    <div class="flex items-center gap-6">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                <i
                                                    class="fas fa-user-graduate mr-1"></i>{{ $pesantren->santri_profiles_count ?? 0 }}
                                                Santri
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">
                                                <i
                                                    class="fas fa-chalkboard-teacher mr-1"></i>{{ $pesantren->ustadz_profiles_count ?? 0 }}
                                                Ustadz
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                                <i class="fas fa-user-shield mr-1"></i>{{ $pesantren->users_count ?? 0 }}
                                                Admin
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Actions -->
                                <div class="flex flex-col items-end gap-3">
                                    <!-- Status Badge -->
                                    <div>
                                        @if ($pesantren->status === 'active')
                                            <span
                                                class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                                                <i class="fas fa-check-circle mr-1"></i>Aktif
                                            </span>
                                        @elseif($pesantren->status === 'pending')
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
                                        <a href="{{ route('superadmin.pesantrens.show', $pesantren->id) }}"
                                            class="p-2 bg-purple-100 text-purple-600 hover:bg-purple-200 rounded-lg transition"
                                            title="Detail">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <button onclick="toggleStatus({{ $pesantren->id }})"
                                            class="p-2 {{ $pesantren->status === 'active' ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }} rounded-lg transition"
                                            title="{{ $pesantren->status === 'active' ? 'Nonaktifkan' : 'Aktifkan' }}">
                                            <i class="fas fa-{{ $pesantren->status === 'active' ? 'pause' : 'play' }}"></i>
                                        </button>

                                        <a href="{{ route('superadmin.pesantrens.edit', $pesantren->id) }}"
                                            class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button onclick="deletePesantren({{ $pesantren->id }})"
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
                            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada pesantren terdaftar</p>
                            <a href="{{ route('superadmin.pesantrens.create') }}"
                                class="inline-block mt-4 px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                                Tambah Pesantren Pertama
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($pesantrens->hasPages())
                    <div class="mt-6">
                        {{ $pesantrens->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function toggleStatus(id) {
            if (!confirm('Apakah Anda yakin ingin mengubah status pesantren ini?')) {
                return;
            }

            fetch(`/superadmin/pesantrens/${id}/toggle`, {
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
                })
                .catch(error => {
                    alert('Error: ' + error);
                });
        }

        function deletePesantren(id) {
            if (!confirm(
                    'PERHATIAN: Menghapus pesantren akan menghapus semua data terkait (santri, ustadz, hafalan, dll). Apakah Anda yakin?'
                    )) {
                return;
            }

            if (!confirm('Konfirmasi sekali lagi. Data yang dihapus tidak dapat dikembalikan!')) {
                return;
            }

            // Implement delete logic
            alert('Fitur delete akan diimplementasikan dengan soft delete untuk keamanan data.');
        }
    </script>
@endpush
