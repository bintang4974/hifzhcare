@extends('layouts.app-enhanced')

@section('title', 'Manajemen Sertifikat')
@section('breadcrumb', 'Sertifikat')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Sertifikat</h1>
                <p class="text-gray-600 mt-1">Kelola sertifikat hafalan santri</p>
            </div>
            <div class="flex gap-3">
                <button onclick="window.print()"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-print mr-2"></i>Cetak Laporan
                </button>
                <a href="{{ route('certificates.generate') }}"
                    class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-certificate mr-2"></i>Generate Manual
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Total Sertifikat</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Bulan Ini</p>
                        <h3 class="text-4xl font-bold">{{ $stats['this_month'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">30 Juz (Khatam)</p>
                        <h3 class="text-4xl font-bold">{{ $stats['khatam'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Per Juz</p>
                        <h3 class="text-4xl font-bold">{{ $stats['per_juz'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-bookmark text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Tipe</label>
                    <select name="type" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <option value="">Semua Tipe</option>
                        <option value="per_juz" {{ request('type') == 'per_juz' ? 'selected' : '' }}>Per Juz</option>
                        <option value="khatam" {{ request('type') == 'khatam' ? 'selected' : '' }}>30 Juz (Khatam)</option>
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kelas</label>
                    <select name="class_id" onchange="this.form.submit()"
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Santri</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau NIS"
                        class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition">
                        <i class="fas fa-search mr-1"></i>Cari
                    </button>
                    <a href="{{ route('certificates.index') }}"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                        <i class="fas fa-redo mr-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Certificate List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <h3 class="text-lg font-bold text-gray-900">Daftar Sertifikat</h3>
            </div>

            <div class="p-6">
                <div class="grid grid-cols-1 gap-4">
                    @forelse($certificates as $cert)
                        <div
                            class="bg-gradient-to-br from-yellow-50 to-orange-50 border-2 border-yellow-200 rounded-xl p-6 hover:shadow-lg transition-all">
                            <div class="flex items-start justify-between">
                                <!-- Certificate Info -->
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div
                                            class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white">
                                            <i class="fas fa-certificate text-2xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900">{{ $cert->santri->user->name }}
                                            </h4>
                                            <p class="text-sm text-gray-600">
                                                <i class="fas fa-id-card mr-1"></i>{{ $cert->santri->nis }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                                        <!-- Certificate Number -->
                                        <div class="flex items-start">
                                            <i class="fas fa-barcode text-yellow-600 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Nomor Sertifikat</p>
                                                <p class="text-sm font-bold text-gray-900">{{ $cert->certificate_number }}
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Type -->
                                        <div class="flex items-start">
                                            <i class="fas fa-bookmark text-yellow-600 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Tipe</p>
                                                @if ($cert->type === 'santri_juz' && ($cert->juz_completed ?? 0) >= 30)
                                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                                        <i class="fas fa-star mr-1"></i>30 Juz (Khatam)
                                                    </span>
                                                @elseif ($cert->type === 'santri_juz' && $cert->juz_completed)
                                                    <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                                        <i class="fas fa-bookmark mr-1"></i>Juz {{ $cert->juz_completed }}
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">-</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Class -->
                                        <div class="flex items-start">
                                            <i class="fas fa-chalkboard text-yellow-600 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Kelas</p>
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $cert->santri->classModel->name ?? '-' }}</p>
                                            </div>
                                        </div>

                                        <!-- Issue Date -->
                                        <div class="flex items-start">
                                            <i class="fas fa-calendar text-yellow-600 mt-1 mr-2"></i>
                                            <div>
                                                <p class="text-xs text-gray-500">Tanggal Terbit</p>
                                                <p class="text-sm font-bold text-gray-900">
                                                    {{ $cert->issued_at?->format('d M Y') ?? '-' }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Achievement Info -->
                                    <div class="p-3 bg-white rounded-lg border border-yellow-200">
                                        <p class="text-sm text-gray-700">
                                            <i class="fas fa-award text-yellow-600 mr-1"></i>
                                            <strong>Pencapaian:</strong>
                                            @if ($cert->type === 'santri_juz' && ($cert->juz_completed ?? 0) >= 30)
                                                Telah menyelesaikan hafalan 30 Juz Al-Quran dengan baik
                                            @elseif ($cert->type === 'santri_juz' && $cert->juz_completed)
                                                Telah menyelesaikan hafalan Juz {{ $cert->juz_completed }} Al-Quran
                                            @else
                                                -
                                            @endif
                                        </p>
                                        @if ($cert->notes)
                                            <p class="text-xs text-gray-600 mt-1">
                                                <i class="fas fa-sticky-note mr-1"></i>{{ $cert->notes }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex flex-col gap-2 ml-4">
                                    <a href="{{ route('certificates.show', $cert->id) }}"
                                        class="p-2 bg-blue-100 text-blue-600 hover:bg-blue-200 rounded-lg transition text-center"
                                        title="Lihat Sertifikat">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="{{ route('certificates.download', $cert->id) }}"
                                        class="p-2 bg-green-100 text-green-600 hover:bg-green-200 rounded-lg transition text-center"
                                        title="Download PDF">
                                        <i class="fas fa-download"></i>
                                    </a>

                                    <a href="{{ route('certificates.print', $cert->id) }}" target="_blank"
                                        class="p-2 bg-purple-100 text-purple-600 hover:bg-purple-200 rounded-lg transition text-center"
                                        title="Cetak">
                                        <i class="fas fa-print"></i>
                                    </a>

                                    <button onclick="sendCertificate({{ $cert->id }})"
                                        class="p-2 bg-yellow-100 text-yellow-600 hover:bg-yellow-200 rounded-lg transition text-center"
                                        title="Kirim ke Wali">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>

                                    @if (auth()->user()->user_type === 'admin')
                                        <button onclick="deleteCertificate({{ $cert->id }})"
                                            class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-lg transition text-center"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">Belum ada sertifikat yang diterbitkan</p>
                            <p class="text-sm text-gray-400 mt-2">Sertifikat akan otomatis dibuat saat santri menyelesaikan
                                hafalan per Juz</p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($certificates->hasPages())
                    <div class="mt-6">
                        {{ $certificates->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-800">
                        <strong>Info Auto-Generate:</strong> Sertifikat akan otomatis dibuat ketika:
                    </p>
                    <ul class="text-sm text-blue-700 mt-2 list-disc list-inside">
                        <li>Santri menyelesaikan 1 Juz lengkap (semua hafalan dalam juz tersebut di-approve ustadz)</li>
                        <li>Santri menyelesaikan 30 Juz (Khatam Al-Quran)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function sendCertificate(id) {
            if (!confirm('Kirim sertifikat ini ke Wali santri via email?')) return;

            fetch(`/certificates/${id}/send`, {
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
                    } else {
                        alert('Error: ' + data.message);
                    }
                });
        }

        function deleteCertificate(id) {
            if (!confirm('Hapus sertifikat ini? Tindakan tidak dapat dibatalkan!')) return;

            fetch(`/certificates/${id}`, {
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
    </script>
@endpush
