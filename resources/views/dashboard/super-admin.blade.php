@extends('layouts.app-enhanced')

@section('title', 'Super Admin Dashboard')
@section('breadcrumb', 'Dashboard / Super Admin')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Super Admin Dashboard</h1>
                <p class="text-gray-600 mt-1">Kelola seluruh sistem dan pesantren</p>
            </div>
            <div class="flex gap-3">
                <button onclick="refreshDashboard()"
                    class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
                <a href="{{ route('superadmin.pesantrens') }}"
                    class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition">
                    <i class="fas fa-building mr-2"></i>Kelola Pesantren
                </a>
            </div>
        </div>

        <!-- Global Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Pesantrens -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Pesantren</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_pesantrens'] }}</h3>
                        <p class="text-xs text-purple-100 mt-2">
                            <span class="font-semibold">{{ $stats['active_pesantrens'] }}</span> aktif
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Pengguna</p>
                        <h3 class="text-4xl font-bold">{{ number_format($stats['total_users']) }}</h3>
                        <p class="text-xs text-blue-100 mt-2">
                            Semua pesantren
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Santri -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-4xl font-bold">{{ number_format($stats['total_santri']) }}</h3>
                        <p class="text-xs text-green-100 mt-2">
                            {{ $stats['total_ustadz'] }} ustadz
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Total Hafalans -->
            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm mb-1">Hafalan Verified</p>
                        <h3 class="text-4xl font-bold">{{ number_format($stats['total_hafalans']) }}</h3>
                        <p class="text-xs text-orange-100 mt-2">
                            {{ $stats['total_certificates'] }} sertifikat
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Growth Chart -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Pertumbuhan Sistem</h3>
                    <div class="flex gap-2">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                            <i class="fas fa-circle mr-1"></i>Santri
                        </span>
                        <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">
                            <i class="fas fa-circle mr-1"></i>Hafalan
                        </span>
                    </div>
                </div>
                <canvas id="growth-chart" height="250"></canvas>
            </div>

            <!-- User Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-900">Distribusi Pengguna</h3>
                    <button onclick="updateChart('distribution')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
                <canvas id="distribution-chart" height="250"></canvas>
            </div>
        </div>

        <!-- Pesantren Performance & Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Pesantrens -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 5 Pesantren (Berdasarkan Santri)
                </h3>

                <div class="space-y-3">
                    @forelse($topPesantrens as $index => $pesantren)
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div
                                class="flex-shrink-0 w-10 h-10 {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-400' : ($index === 2 ? 'bg-orange-600' : 'bg-blue-500')) }} rounded-full flex items-center justify-center text-white font-bold mr-4">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $pesantren->name }}</h4>
                                <p class="text-sm text-gray-600">{{ $pesantren->code }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">{{ $pesantren->santris_count }}</p>
                                <p class="text-xs text-gray-500">santri</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-building text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Belum ada data pesantren</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-clock text-blue-500 mr-2"></i>
                    Aktivitas Terbaru
                </h3>

                <div class="space-y-3">
                    @forelse($recentActivities as $activity)
                        <div class="flex items-start">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-{{ $activity['color'] }}-100 rounded-full flex items-center justify-center text-{{ $activity['color'] }}-600 mr-3">
                                <i class="fas fa-{{ $activity['icon'] }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-900">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['time']->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Belum ada aktivitas</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- System Health -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-heartbeat text-red-500 mr-2"></i>
                Kesehatan Sistem
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Status</span>
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-green-600">Healthy</p>
                </div>

                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Database</span>
                        <i class="fas fa-database text-blue-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ $systemHealth['database_size_mb'] }} MB</p>
                </div>

                <div class="p-4 bg-purple-50 border border-purple-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Storage</span>
                        <i class="fas fa-hdd text-purple-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-purple-600">{{ $systemHealth['storage_used_gb'] }} GB</p>
                </div>

                <div class="p-4 bg-orange-50 border border-orange-200 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Total Records</span>
                        <i class="fas fa-table text-orange-600 text-xl"></i>
                    </div>
                    <p class="text-2xl font-bold text-orange-600">{{ number_format($systemHealth['total_records']) }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Growth Chart
        const growthCtx = document.getElementById('growth-chart').getContext('2d');
        const growthChart = new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyGrowth['labels']) !!},
                datasets: [{
                    label: 'Santri',
                    data: {!! json_encode($monthlyGrowth['santri']) !!},
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Hafalan',
                    data: {!! json_encode($monthlyGrowth['hafalan']) !!},
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
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

        // Distribution Chart
        const distributionCtx = document.getElementById('distribution-chart').getContext('2d');
        const distributionChart = new Chart(distributionCtx, {
            type: 'doughnut',
            data: {
                labels: ['Admin', 'Ustadz', 'Santri', 'Wali'],
                datasets: [{
                    data: [
                        {{ $userDistribution['admins'] }},
                        {{ $userDistribution['ustadz'] }},
                        {{ $userDistribution['santri'] }},
                        {{ $userDistribution['wali'] }}
                    ],
                    backgroundColor: [
                        'rgb(99, 102, 241)',
                        'rgb(16, 185, 129)',
                        'rgb(59, 130, 246)',
                        'rgb(139, 92, 246)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        function refreshDashboard() {
            location.reload();
        }

        function updateChart(type) {
            // Implement chart update via AJAX if needed
            console.log('Updating chart:', type);
        }
    </script>
@endpush
