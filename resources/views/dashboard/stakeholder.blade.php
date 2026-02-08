@extends('layouts.app-enhanced')

@section('title', 'Dashboard Pimpinan')
@section('breadcrumb', 'Dashboard / Stakeholder')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Dashboard Pimpinan Pesantren</h1>
                <p class="text-gray-600 mt-1">Monitoring dan laporan eksekutif</p>
            </div>
            <button onclick="window.print()"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-md hover:shadow-lg transition">
                <i class="fas fa-print mr-2"></i>Cetak Laporan
            </button>
        </div>

        <!-- Executive KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Santri -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-4xl font-bold">{{ $kpis['total_santri'] }}</h3>
                        <p class="text-xs text-blue-100 mt-2">
                            {{ $kpis['active_santri'] }} aktif
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                </div>
                @if (isset($trends['santri_change']))
                    <div class="flex items-center text-xs">
                        <i class="fas fa-arrow-{{ $trends['santri_change'] >= 0 ? 'up' : 'down' }} mr-1"></i>
                        <span>{{ abs($trends['santri_change']) }}% vs bulan lalu</span>
                    </div>
                @endif
            </div>

            <!-- Total Ustadz -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Ustadz</p>
                        <h3 class="text-4xl font-bold">{{ $kpis['total_ustadz'] }}</h3>
                        <p class="text-xs text-green-100 mt-2">
                            {{ $kpis['total_classes'] }} kelas
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-3xl"></i>
                    </div>
                </div>
            </div>

            <!-- Hafalan Verified -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Hafalan Verified</p>
                        <h3 class="text-4xl font-bold">{{ number_format($kpis['total_hafalan_verified']) }}</h3>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-3xl"></i>
                    </div>
                </div>
                @if (isset($trends['hafalan_change']))
                    <div class="flex items-center text-xs">
                        <i class="fas fa-arrow-{{ $trends['hafalan_change'] >= 0 ? 'up' : 'down' }} mr-1"></i>
                        <span>{{ abs($trends['hafalan_change']) }}% vs bulan lalu</span>
                    </div>
                @endif
            </div>

            <!-- Completion Rate -->
            <div
                class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                <div class="flex items-center justify-between mb-3">
                    <div>
                        <p class="text-orange-100 text-sm mb-1">Tingkat Kelulusan</p>
                        <h3 class="text-4xl font-bold">{{ number_format($kpis['completion_rate'], 1) }}%</h3>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-3xl"></i>
                    </div>
                </div>
                <div class="w-full bg-white bg-opacity-20 rounded-full h-2">
                    <div class="bg-white h-2 rounded-full transition-all"
                        style="width: {{ min(100, $kpis['completion_rate']) }}%"></div>
                </div>
            </div>
        </div>

        <!-- Secondary KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Certificates -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Sertifikat Diterbitkan</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ $kpis['certificates_issued'] }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <!-- Donations -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Total Donasi</p>
                        <h3 class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($kpis['total_donations'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hand-holding-usd text-2xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <!-- This Month Donations -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Donasi Bulan Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($financialSummary['this_month'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl text-blue-600"></i>
                    </div>
                </div>
                @if ($financialSummary['change'] != 0)
                    <p class="text-xs text-gray-600 mt-2">
                        <i class="fas fa-arrow-{{ $financialSummary['change'] >= 0 ? 'up' : 'down' }} mr-1"></i>
                        {{ abs($financialSummary['change']) }}% vs bulan lalu
                    </p>
                @endif
            </div>

            <!-- This Year Donations -->
            <div class="bg-white rounded-xl p-6 shadow-md border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm mb-1">Donasi Tahun Ini</p>
                        <h3 class="text-2xl font-bold text-gray-900">Rp
                            {{ number_format($financialSummary['total_year'], 0, ',', '.') }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-2xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Progress Distribution -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                    Distribusi Progress Santri
                </h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="progress-chart"></canvas>
                </div>
            </div>

            <!-- Hafalan Trend -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-green-600 mr-2"></i>
                    Trend Hafalan (6 Bulan Terakhir)
                </h3>
                <div class="relative" style="height: 300px;">
                    <canvas id="hafalan-chart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Performers & Needs Attention -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Top Performers -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                    Top 10 Santri Berprestasi
                </h3>

                <div class="space-y-2">
                    @forelse($topPerformers as $index => $santri)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div
                                class="flex-shrink-0 w-8 h-8 {{ $index < 3 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500' : 'bg-gray-300' }} rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ $index + 1 }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $santri->user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $santri->verified_hafalans }} hafalan verified</p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ number_format($santri->progress_percentage, 1) }}%</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-user-graduate text-4xl text-gray-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Belum ada data</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Students Needing Attention -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                    Santri Perlu Perhatian
                </h3>

                <div class="space-y-2">
                    @forelse($studentsNeedingAttention as $santri)
                        <div class="flex items-center p-3 bg-orange-50 border border-orange-200 rounded-lg">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white mr-3">
                                <i class="fas fa-user text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $santri->user->name }}</h4>
                                <p class="text-xs text-gray-600">Progress rendah atau tidak aktif</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-orange-600">
                                    {{ number_format($santri->progress_percentage, 1) }}%</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-4xl text-green-300 mb-2"></i>
                            <p class="text-sm text-gray-500">Semua santri dalam kondisi baik!</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Class Performance -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-chalkboard text-purple-600 mr-2"></i>
                Performa Per Kelas
            </h3>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-600 uppercase">Kelas</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Jumlah Santri</th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Rata-rata Progress
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-bold text-gray-600 uppercase">Total Verified</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($classPerformance as $class)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <span class="font-semibold text-gray-900">{{ $class['name'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 bg-blue-100 text-blue-800 text-sm font-semibold rounded-full">
                                        {{ $class['total_santri'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <div class="w-24 bg-gray-200 rounded-full h-2">
                                            <div class="bg-green-600 h-2 rounded-full"
                                                style="width: {{ min(100, $class['avg_progress']) }}%"></div>
                                        </div>
                                        <span
                                            class="font-bold text-gray-900">{{ number_format($class['avg_progress'], 1) }}%</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold text-green-600">{{ $class['total_verified'] }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <i class="fas fa-chalkboard text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500">Belum ada data kelas</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Certificates -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-certificate text-yellow-500 mr-2"></i>
                Sertifikat Terbaru
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($recentCertificates as $cert)
                    <div class="p-4 bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-award text-2xl text-yellow-600 mr-3"></i>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $cert->user->name ?? 'N/A' }}</h4>
                                <p class="text-xs text-gray-600">{{ $cert->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">{{ $cert->certificate_number }}</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-certificate text-6xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">Belum ada sertifikat diterbitkan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Progress Distribution Chart
            const progressCtx = document.getElementById('progress-chart');
            if (progressCtx) {
                new Chart(progressCtx, {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($progressData['labels']) !!},
                        datasets: [{
                            label: 'Jumlah Santri',
                            data: {!! json_encode($progressData['data']) !!},
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(251, 191, 36, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)'
                            ],
                            borderColor: [
                                'rgb(239, 68, 68)',
                                'rgb(251, 191, 36)',
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)'
                            ],
                            borderWidth: 2
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
            }

            // Hafalan Trend Chart
            const hafalanCtx = document.getElementById('hafalan-chart');
            if (hafalanCtx) {
                new Chart(hafalanCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($hafalanTrend['labels']) !!},
                        datasets: [{
                            label: 'Hafalan Verified',
                            data: {!! json_encode($hafalanTrend['data']) !!},
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
            }
        });
    </script>
@endpush
