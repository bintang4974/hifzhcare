@extends('layouts.app-enhanced')

@section('title', 'Laporan & Report')
@section('breadcrumb', 'Laporan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Laporan & Report</h1>
                <p class="text-gray-600 mt-1">Generate dan download laporan pesantren</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_santri'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Hafalan</p>
                        <h3 class="text-4xl font-bold">{{ number_format($stats['total_hafalan']) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Sertifikat</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_certificates'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Kelas</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_classes'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Categories -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- SANTRI REPORTS -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Laporan Santri</h3>
                        <p class="text-sm text-gray-600">Data dan progress santri</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Data Santri -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('santri-data')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Data Santri Lengkap</h4>
                                <p class="text-sm text-gray-600">Daftar semua santri dengan detail</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Progress Hafalan -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('santri-progress')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Progress Hafalan</h4>
                                <p class="text-sm text-gray-600">Laporan kemajuan hafalan per santri</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Ranking -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('santri-ranking')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Ranking & Achievement</h4>
                                <p class="text-sm text-gray-600">Top performers dan pencapaian</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CLASS REPORTS -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-chalkboard text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Laporan Kelas</h3>
                        <p class="text-sm text-gray-600">Overview dan performance kelas</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Kelas Overview -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('class-overview')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Overview Kelas</h4>
                                <p class="text-sm text-gray-600">Ringkasan data semua kelas</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Performance -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('class-performance')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Performance Kelas</h4>
                                <p class="text-sm text-gray-600">Analisis kinerja per kelas</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- HAFALAN REPORTS -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Laporan Hafalan</h3>
                        <p class="text-sm text-gray-600">Statistik dan tracking hafalan</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Hafalan Summary -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('hafalan-summary')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Summary Hafalan</h4>
                                <p class="text-sm text-gray-600">Total hafalan & statistik</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Per Juz -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('hafalan-juz')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Hafalan per Juz</h4>
                                <p class="text-sm text-gray-600">Breakdown per juz & surah</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CERTIFICATE REPORTS -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-4">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Laporan Sertifikat</h3>
                        <p class="text-sm text-gray-600">Data sertifikat yang diterbitkan</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <!-- Certificate Summary -->
                    <div class="p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer"
                        onclick="openReportModal('certificate-summary')">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">Summary Sertifikat</h4>
                                <p class="text-sm text-gray-600">Total sertifikat yang diterbitkan</p>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recent Reports -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Laporan Terakhir</h3>

            <div class="space-y-3">
                @forelse($recentReports as $report)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-2xl text-red-500 mr-4"></i>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $report->name }}</h4>
                                <p class="text-sm text-gray-600">
                                    Generated {{ $report->created_at->diffForHumans() }} •
                                    {{ $report->format }} •
                                    {{ $report->file_size }}
                                </p>
                            </div>
                        </div>
                        <a href="{{ $report->download_url }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <i class="fas fa-file-alt text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada laporan yang dibuat</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Report Modal -->
    <div id="reportModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900" id="modalTitle">Generate Report</h3>
                <button onclick="closeReportModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="reportForm" method="POST" target="_blank">
                @csrf
                <div class="space-y-4">
                    <!-- Report Type (Hidden) -->
                    <input type="hidden" name="report_type" id="reportType">

                    <!-- Date Range -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                            <input type="date" name="start_date" class="w-full rounded-lg border-gray-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                            <input type="date" name="end_date" class="w-full rounded-lg border-gray-300">
                        </div>
                    </div>

                    <!-- Class Filter (conditional) -->
                    <div id="classFilter" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Kelas</label>
                        <select name="class_id" class="w-full rounded-lg border-gray-300">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Status Filter (conditional) -->
                    <div id="statusFilter" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Filter Status</label>
                        <select name="status" class="w-full rounded-lg border-gray-300">
                            <option value="">Semua Status</option>
                            <option value="active">Aktif</option>
                            <option value="alumni">Alumni</option>
                            <option value="inactive">Keluar</option>
                        </select>
                    </div>

                    <!-- Format Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Format Export</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label
                                class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="format" value="pdf" checked class="mr-2">
                                <i class="fas fa-file-pdf text-red-500 mr-2"></i>
                                <span class="font-medium">PDF</span>
                            </label>
                            <label
                                class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="format" value="excel" class="mr-2">
                                <i class="fas fa-file-excel text-green-500 mr-2"></i>
                                <span class="font-medium">Excel</span>
                            </label>
                            <label
                                class="flex items-center p-3 bg-gray-50 rounded-lg cursor-pointer hover:bg-gray-100 transition">
                                <input type="radio" name="format" value="csv" class="mr-2">
                                <i class="fas fa-file-csv text-blue-500 mr-2"></i>
                                <span class="font-medium">CSV</span>
                            </label>
                        </div>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <p class="text-sm text-blue-800" id="reportInfo">
                            Laporan akan dibuat berdasarkan filter yang dipilih.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button type="button" onclick="closeReportModal()"
                        class="flex-1 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 transition">
                        <i class="fas fa-download mr-2"></i>Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const reportConfig = {
            'santri-data': {
                title: 'Laporan Data Santri',
                action: '{{ route('reports.santri-data') }}',
                showClass: true,
                showStatus: true,
                info: 'Laporan lengkap data semua santri termasuk informasi pribadi dan wali.'
            },
            'santri-progress': {
                title: 'Laporan Progress Hafalan',
                action: '{{ route('reports.santri-progress') }}',
                showClass: true,
                showStatus: false,
                info: 'Laporan kemajuan hafalan per santri dengan grafik dan statistik.'
            },
            'santri-ranking': {
                title: 'Laporan Ranking & Achievement',
                action: '{{ route('reports.santri-ranking') }}',
                showClass: true,
                showStatus: false,
                info: 'Daftar top performers dan pencapaian santri.'
            },
            'class-overview': {
                title: 'Laporan Overview Kelas',
                action: '{{ route('reports.class-overview') }}',
                showClass: false,
                showStatus: false,
                info: 'Ringkasan data semua kelas dengan statistik lengkap.'
            },
            'class-performance': {
                title: 'Laporan Performance Kelas',
                action: '{{ route('reports.class-performance') }}',
                showClass: true,
                showStatus: false,
                info: 'Analisis kinerja dan perbandingan antar kelas.'
            },
            'hafalan-summary': {
                title: 'Laporan Summary Hafalan',
                action: '{{ route('reports.hafalan-summary') }}',
                showClass: false,
                showStatus: false,
                info: 'Total hafalan, statistik verifikasi, dan trend.'
            },
            'hafalan-juz': {
                title: 'Laporan Hafalan per Juz',
                action: '{{ route('reports.hafalan-juz') }}',
                showClass: false,
                showStatus: false,
                info: 'Breakdown hafalan per juz dan analisis kesulitan.'
            },
            'certificate-summary': {
                title: 'Laporan Summary Sertifikat',
                action: '{{ route('reports.certificate-summary') }}',
                showClass: true,
                showStatus: false,
                info: 'Data sertifikat yang diterbitkan dengan statistik.'
            }
        };

        function openReportModal(type) {
            const config = reportConfig[type];

            document.getElementById('modalTitle').textContent = config.title;
            document.getElementById('reportType').value = type;
            document.getElementById('reportForm').action = config.action;
            document.getElementById('reportInfo').textContent = config.info;

            // Show/hide filters
            document.getElementById('classFilter').classList.toggle('hidden', !config.showClass);
            document.getElementById('statusFilter').classList.toggle('hidden', !config.showStatus);

            document.getElementById('reportModal').classList.remove('hidden');
        }

        function closeReportModal() {
            document.getElementById('reportModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('reportModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeReportModal();
            }
        });
    </script>
@endpush
