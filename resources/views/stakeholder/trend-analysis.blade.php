@extends('layouts.app-enhanced')

@section('title', 'Trend Analysis')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            @include('components.stakeholder-report-sidebar')
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Trend Analysis</h1>
                <p class="text-gray-600 mt-1">Analisis tren perkembangan pesantren secara keseluruhan</p>
            </div>
            <div class="flex gap-3">
                <select id="periodFilter" class="rounded-lg border-gray-300 focus:border-blue-500">
                    <option value="6">6 Bulan Terakhir</option>
                    <option value="12" selected>12 Bulan Terakhir</option>
                    <option value="24">24 Bulan Terakhir</option>
                </select>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>

        <!-- Key Metrics Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                    <span class="text-xs bg-green-500 px-2 py-1 rounded-full">
                        +{{ $trends['santri_growth'] }}%
                    </span>
                </div>
                <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                <p class="text-4xl font-bold">{{ $trends['total_santri'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                    <span class="text-xs bg-green-500 px-2 py-1 rounded-full">
                        +{{ $trends['hafalan_growth'] }}%
                    </span>
                </div>
                <p class="text-green-100 text-sm mb-1">Hafalan Verified</p>
                <p class="text-4xl font-bold">{{ number_format($trends['total_hafalan']) }}</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                    <span class="text-xs bg-green-500 px-2 py-1 rounded-full">
                        +{{ $trends['certificate_growth'] }}%
                    </span>
                </div>
                <p class="text-purple-100 text-sm mb-1">Sertifikat</p>
                <p class="text-4xl font-bold">{{ $trends['total_certificates'] }}</p>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                </div>
                <p class="text-orange-100 text-sm mb-1">Avg Progress</p>
                <p class="text-4xl font-bold">{{ number_format($trends['avg_progress'], 1) }}%</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Santri Growth Trend -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-users text-blue-600 mr-2"></i>
                        Pertumbuhan Santri
                    </h2>
                    <span class="text-sm text-gray-500">Per Bulan</span>
                </div>

                <div class="h-80" id="santriGrowthChart"></div>

                <div class="mt-4 grid grid-cols-3 gap-4 pt-4 border-t">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $trends['new_santri'] }}</p>
                        <p class="text-xs text-gray-500">Santri Baru</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $trends['active_santri'] }}</p>
                        <p class="text-xs text-gray-500">Aktif</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-purple-600">{{ $trends['alumni'] }}</p>
                        <p class="text-xs text-gray-500">Alumni</p>
                    </div>
                </div>
            </div>

            <!-- Hafalan Submission Trend -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-chart-area text-green-600 mr-2"></i>
                        Tren Hafalan
                    </h2>
                    <span class="text-sm text-gray-500">Per Bulan</span>
                </div>

                <div class="h-80" id="hafalanTrendChart"></div>

                <div class="mt-4 grid grid-cols-3 gap-4 pt-4 border-t">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-green-600">{{ $trends['monthly_avg'] }}</p>
                        <p class="text-xs text-gray-500">Rata-rata/Bulan</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-blue-600">{{ $trends['peak_month'] }}</p>
                        <p class="text-xs text-gray-500">Bulan Tertinggi</p>
                    </div>
                    <div class="text-center">
                        <p class="text-2xl font-bold text-orange-600">{{ $trends['verification_rate'] }}%</p>
                        <p class="text-xs text-gray-500">Verification Rate</p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Certificate & Progress Trends -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Certificate Issuance -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-certificate text-purple-600 mr-2"></i>
                        Penerbitan Sertifikat
                    </h2>
                    <span class="text-sm text-gray-500">Per Bulan</span>
                </div>

                <div class="h-80" id="certificateChart"></div>

                <div class="mt-4 pt-4 border-t">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-gray-600">Sertifikat Per Juz</span>
                        <span class="font-bold text-purple-600">{{ $trends['per_juz_certs'] }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Sertifikat Khatam</span>
                        <span class="font-bold text-blue-600">{{ $trends['khatam_certs'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Average Progress Over Time -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-chart-line text-orange-600 mr-2"></i>
                        Progress Keseluruhan
                    </h2>
                    <span class="text-sm text-gray-500">Per Bulan</span>
                </div>

                <div class="h-80" id="progressChart"></div>

                <div class="mt-4 pt-4 border-t">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Progress Tertinggi</p>
                            <p class="text-2xl font-bold text-green-600">
                                {{ number_format($trends['highest_progress'], 1) }}%</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Progress Terendah</p>
                            <p class="text-2xl font-bold text-orange-600">
                                {{ number_format($trends['lowest_progress'], 1) }}%</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Class Performance Comparison -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-gray-900">
                    <i class="fas fa-school text-indigo-600 mr-2"></i>
                    Perbandingan Kelas
                </h2>
                <span class="text-sm text-gray-500">Progress & Hafalan</span>
            </div>

            <div class="h-80" id="classComparisonChart"></div>
        </div>

        <!-- Insights & Recommendations -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Key Insights -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border-2 border-blue-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                    Key Insights
                </h3>

                <div class="space-y-3">
                    @foreach ($insights as $insight)
                        <div class="flex items-start p-3 bg-white rounded-lg">
                            <div
                                class="w-8 h-8 bg-{{ $insight['color'] }}-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-{{ $insight['icon'] }} text-{{ $insight['color'] }}-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $insight['title'] }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $insight['description'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recommendations -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Rekomendasi
                </h3>

                <div class="space-y-3">
                    @foreach ($recommendations as $recommendation)
                        <div class="flex items-start p-3 bg-white rounded-lg">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-arrow-right text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 text-sm">{{ $recommendation['title'] }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $recommendation['action'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Santri Growth Chart
            var santriGrowthChart = new ApexCharts(document.querySelector("#santriGrowthChart"), {
                series: [{
                    name: 'Total Santri',
                    data: @json($chartData['santri_growth'])
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3B82F6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($chartData['months'])
                },
                tooltip: {
                    theme: 'dark'
                }
            });
            santriGrowthChart.render();

            // Hafalan Trend Chart
            var hafalanTrendChart = new ApexCharts(document.querySelector("#hafalanTrendChart"), {
                series: [{
                    name: 'Submitted',
                    data: @json($chartData['hafalan_submitted'])
                }, {
                    name: 'Verified',
                    data: @json($chartData['hafalan_verified'])
                }],
                chart: {
                    type: 'line',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#10B981', '#3B82F6'],
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($chartData['months'])
                },
                tooltip: {
                    theme: 'dark'
                }
            });
            hafalanTrendChart.render();

            // Certificate Chart
            var certificateChart = new ApexCharts(document.querySelector("#certificateChart"), {
                series: [{
                    name: 'Sertifikat',
                    data: @json($chartData['certificates'])
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#8B5CF6'],
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        columnWidth: '60%'
                    }
                },
                xaxis: {
                    categories: @json($chartData['months'])
                },
                tooltip: {
                    theme: 'dark'
                }
            });
            certificateChart.render();

            // Progress Chart
            var progressChart = new ApexCharts(document.querySelector("#progressChart"), {
                series: [{
                    name: 'Avg Progress',
                    data: @json($chartData['avg_progress'])
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#F59E0B'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                xaxis: {
                    categories: @json($chartData['months'])
                },
                yaxis: {
                    max: 100,
                    labels: {
                        formatter: function(val) {
                            return val.toFixed(1) + '%';
                        }
                    }
                },
                tooltip: {
                    theme: 'dark'
                }
            });
            progressChart.render();

            // Class Comparison Chart
            var classComparisonChart = new ApexCharts(document.querySelector("#classComparisonChart"), {
                series: [{
                    name: 'Progress (%)',
                    data: @json($chartData['class_progress'])
                }, {
                    name: 'Hafalan Verified',
                    data: @json($chartData['class_hafalan'])
                }],
                chart: {
                    type: 'bar',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#3B82F6', '#10B981'],
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 6
                    }
                },
                xaxis: {
                    categories: @json($chartData['class_names'])
                },
                tooltip: {
                    theme: 'dark'
                }
            });
            classComparisonChart.render();
        </script>
    @endpush
@endsection
