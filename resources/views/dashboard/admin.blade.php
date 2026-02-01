@extends('layouts.app-enhanced')

@section('title', 'Dashboard Admin')
@section('breadcrumb', 'Dashboard Admin')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="gradient-bg rounded-2xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-10 rounded-full -ml-24 -mb-24"></div>

            <div class="relative z-10">
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
                        <p class="text-blue-100 text-lg">{{ $pesantren->name }}</p>
                        <div class="flex items-center mt-4 space-x-4">
                            <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span>{{ now()->isoFormat('dddd, D MMMM YYYY') }}</span>
                            </div>
                            <div class="flex items-center bg-white bg-opacity-20 rounded-lg px-4 py-2">
                                <i class="fas fa-clock mr-2"></i>
                                <span id="current-time"></span>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                            <p class="text-sm text-blue-100 mb-1">Kuota Santri</p>
                            <div class="flex items-center space-x-2">
                                <div class="text-3xl font-bold">{{ $stats['total_santri'] }}</div>
                                <div class="text-lg text-blue-100">/ {{ $stats['max_santri'] }}</div>
                            </div>
                            <div class="mt-2 bg-white bg-opacity-30 rounded-full h-2 w-32">
                                <div class="bg-white h-2 rounded-full transition-all duration-500"
                                    style="width: {{ $stats['quota_percentage'] }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Santri -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Santri</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_santri'] }}</h3>
                        <p class="text-sm text-blue-600 mt-2">
                            <i class="fas fa-arrow-up mr-1"></i>
                            {{ $stats['quota_percentage'] }}% dari kuota
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-graduate text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <!-- Total Ustadz -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Ustadz</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_ustadz'] }}</h3>
                        <p class="text-sm text-green-600 mt-2">
                            <i class="fas fa-check-circle mr-1"></i>
                            Aktif mengajar
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- Hafalan Pending -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hafalan Pending</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending_hafalan'] }}</h3>
                        <p class="text-sm text-yellow-600 mt-2">
                            <i class="fas fa-clock mr-1"></i>
                            Menunggu verifikasi
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-3xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Hafalan Verified -->
            <div class="bg-white rounded-xl shadow-md p-6 card-hover border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Hafalan</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_hafalan'] }}</h3>
                        <p class="text-sm text-purple-600 mt-2">
                            <i class="fas fa-check-double mr-1"></i>
                            {{ $stats['verified_hafalan'] }} terverifikasi
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts & Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Monthly Progress Chart -->
            {{-- <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Progress Bulanan</h3>
                    <select class="text-sm border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option>12 Bulan Terakhir</option>
                        <option>6 Bulan Terakhir</option>
                        <option>3 Bulan Terakhir</option>
                    </select>
                </div>
                <canvas id="monthlyChart" height="100"></canvas>
            </div> --}}

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-6">Aksi Cepat</h3>
                <div class="space-y-3">
                    {{-- <a href="{{ route('users.santri.create') }}" --}}
                    <a href=""
                        class="flex items-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Tambah Santri</p>
                            <p class="text-xs text-blue-100">Daftarkan santri baru</p>
                        </div>
                    </a>

                    <a href="{{ route('classes.create') }}"
                        class="flex items-center p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg hover:from-green-600 hover:to-green-700 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-plus-circle"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Buat Kelas</p>
                            <p class="text-xs text-green-100">Tambah kelas baru</p>
                        </div>
                    </a>

                    <a href="{{ route('hafalan.index') }}?status=pending"
                        class="flex items-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-lg hover:from-yellow-600 hover:to-yellow-700 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Hafalan Pending</p>
                            <p class="text-xs text-yellow-100">{{ $stats['pending_hafalan'] }} menunggu</p>
                        </div>
                    </a>

                    {{-- <a href="{{ route('reports.index') }}" --}}
                    <a href=""
                        class="flex items-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-lg hover:from-purple-600 hover:to-purple-700 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div>
                            <p class="font-semibold">Lihat Laporan</p>
                            <p class="text-xs text-purple-100">Analisis & statistik</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity & Pending Items -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Hafalan -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Hafalan Terbaru</h3>
                    <a href="{{ route('hafalan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentHafalans as $hafalan)
                        <div
                            class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div
                                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ substr($hafalan->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $hafalan->user->name }}</p>
                                    <p class="text-sm text-gray-600 truncate">
                                        {{ $hafalan->surah_name }} • Ayat
                                        {{ $hafalan->ayat_start }}-{{ $hafalan->ayat_end }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $hafalan->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div>
                                @if ($hafalan->status === 'verified')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Verified
                                    </span>
                                @elseif($hafalan->status === 'pending')
                                    <span
                                        class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Rejected
                                    </span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-3"></i>
                            <p>Belum ada hafalan terbaru</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pending Certificates -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Sertifikat Pending</h3>
                    {{-- <a href="{{ route('certificates.index') }}?status=pending" --}}
                    <a href="?status=pending"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($pendingCertificates as $certificate)
                        <div
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 hover:shadow-md transition-all">
                            <div class="flex items-center space-x-3 flex-1 min-w-0">
                                <div
                                    class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                    <i class="fas fa-certificate"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 truncate">{{ $certificate->user->name }}</p>
                                    <p class="text-sm text-gray-600">Juz {{ $certificate->juz_completed }}</p>
                                    <p class="text-xs text-gray-500">{{ $certificate->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <a href="{{ route('certificates.show', $certificate->id) }}"
                                class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm font-semibold rounded-lg transition-colors">
                                Review
                            </a>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-check-circle text-4xl text-green-500 mb-3"></i>
                            <p>Semua sertifikat sudah diproses!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Update time
        function updateTime() {
            const now = new Date();
            const time = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('current-time').textContent = time;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // Monthly Progress Chart
        const ctx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = @json($monthlyProgress);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthlyData.map(m => m.month),
                datasets: [{
                        label: 'Total Hafalan',
                        data: monthlyData.map(m => m.hafalans),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Terverifikasi',
                        data: monthlyData.map(m => m.verified),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>
@endpush
