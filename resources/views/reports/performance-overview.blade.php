@extends('layouts.app-enhanced')

@section('title', 'Performance Overview')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Performance Overview</h1>
                <p class="text-gray-600 mt-1">Ringkasan performa keseluruhan pesantren</p>
            </div>
            <div class="flex gap-3">
                <button onclick="exportReport()" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
                <button onclick="window.print()" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>

        <!-- Overall Performance Score -->
        <div class="bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-500 rounded-3xl p-10 text-white shadow-2xl">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-center">
                <div class="lg:col-span-2">
                    <p class="text-purple-100 text-sm mb-2">Overall Performance Score</p>
                    <div class="flex items-end gap-4 mb-4">
                        <h1 class="text-7xl font-bold">{{ $performance['overall_score'] }}</h1>
                        <span class="text-3xl mb-2">/100</span>
                    </div>
                    <p class="text-purple-100 mb-4">
                        {{ $performance['score_label'] }} - {{ $performance['score_description'] }}
                    </p>
                    <div class="flex gap-3">
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-lg text-sm">
                            <i class="fas fa-trophy mr-2"></i>Ranking: #{{ $performance['ranking'] }} dari
                            {{ $performance['total_pesantren'] }}
                        </span>
                        <span class="px-4 py-2 bg-white bg-opacity-20 rounded-lg text-sm">
                            <i
                                class="fas fa-chart-line mr-2"></i>{{ $performance['trend'] > 0 ? '+' : '' }}{{ $performance['trend'] }}%
                            vs bulan lalu
                        </span>
                    </div>
                </div>
                <div>
                    <div class="w-48 h-48 mx-auto">
                        <canvas id="performanceGauge"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Key Performance Indicators -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($kpis as $kpi)
                <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-{{ $kpi['color'] }}-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-{{ $kpi['icon'] }} text-{{ $kpi['color'] }}-600 text-xl"></i>
                        </div>
                        @if ($kpi['change'] != 0)
                            <span
                                class="text-xs px-2 py-1 rounded-full {{ $kpi['change'] > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $kpi['change'] > 0 ? '+' : '' }}{{ $kpi['change'] }}%
                            </span>
                        @endif
                    </div>
                    <p class="text-gray-500 text-sm mb-2">{{ $kpi['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-900 mb-1">{{ $kpi['value'] }}</p>
                    <div class="mt-3">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="text-gray-500">Target: {{ $kpi['target'] }}</span>
                            <span class="font-semibold text-{{ $kpi['color'] }}-600">{{ $kpi['achievement'] }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-{{ $kpi['color'] }}-500 h-2 rounded-full"
                                style="width: {{ min($kpi['achievement'], 100) }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Performance by Category -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Academic Performance -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-graduation-cap text-blue-600 mr-2"></i>
                    Performa Akademik
                </h2>

                <div class="space-y-4">
                    @foreach ($academic_metrics as $metric)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span
                                    class="text-sm font-bold text-gray-900">{{ $metric['value'] }}{{ $metric['unit'] }}</span>
                            </div>
                            <div class="relative">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-{{ $metric['color'] }}-400 to-{{ $metric['color'] }}-600 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $metric['percentage'] }}%"></div>
                                </div>
                                <span
                                    class="absolute right-0 -top-6 text-xs font-semibold text-{{ $metric['color'] }}-600">
                                    {{ $metric['percentage'] }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800">
                        <strong>Average Academic Score:</strong>
                        <span class="text-2xl font-bold text-blue-600">{{ $performance['academic_score'] }}/100</span>
                    </p>
                </div>
            </div>

            <!-- Operational Performance -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-cogs text-purple-600 mr-2"></i>
                    Performa Operasional
                </h2>

                <div class="space-y-4">
                    @foreach ($operational_metrics as $metric)
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">{{ $metric['name'] }}</span>
                                <span
                                    class="text-sm font-bold text-gray-900">{{ $metric['value'] }}{{ $metric['unit'] }}</span>
                            </div>
                            <div class="relative">
                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-gradient-to-r from-{{ $metric['color'] }}-400 to-{{ $metric['color'] }}-600 h-3 rounded-full transition-all duration-500"
                                        style="width: {{ $metric['percentage'] }}%"></div>
                                </div>
                                <span
                                    class="absolute right-0 -top-6 text-xs font-semibold text-{{ $metric['color'] }}-600">
                                    {{ $metric['percentage'] }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm text-purple-800">
                        <strong>Average Operational Score:</strong>
                        <span class="text-2xl font-bold text-purple-600">{{ $performance['operational_score'] }}/100</span>
                    </p>
                </div>
            </div>

        </div>

        <!-- Class Performance Ranking -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-trophy text-yellow-600 mr-2"></i>
                Ranking Kelas (Top Performers)
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach ($top_classes as $index => $class)
                    <div
                        class="relative overflow-hidden rounded-xl border-2 
                        {{ $index === 0
                            ? 'border-yellow-400 bg-gradient-to-br from-yellow-50 to-amber-50'
                            : ($index === 1
                                ? 'border-gray-400 bg-gradient-to-br from-gray-50 to-slate-50'
                                : 'border-orange-400 bg-gradient-to-br from-orange-50 to-red-50') }}">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="text-5xl">
                                    @if ($index === 0)
                                        🥇
                                    @elseif($index === 1)
                                        🥈
                                    @else
                                        🥉
                                    @endif
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-3xl font-bold {{ $index === 0 ? 'text-yellow-600' : ($index === 1 ? 'text-gray-600' : 'text-orange-600') }}">
                                        #{{ $index + 1 }}
                                    </p>
                                </div>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $class['name'] }}</h3>
                            <p class="text-sm text-gray-600 mb-4">Ustadz: {{ $class['ustadz'] }}</p>

                            <div class="grid grid-cols-2 gap-3 mb-4">
                                <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Avg Progress</p>
                                    <p class="text-lg font-bold text-green-600">{{ $class['avg_progress'] }}%</p>
                                </div>
                                <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Total Santri</p>
                                    <p class="text-lg font-bold text-blue-600">{{ $class['total_santri'] }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Hafalan</p>
                                    <p class="text-lg font-bold text-purple-600">{{ $class['total_hafalan'] }}</p>
                                </div>
                                <div class="bg-white bg-opacity-60 rounded-lg p-3">
                                    <p class="text-xs text-gray-500">Sertifikat</p>
                                    <p class="text-lg font-bold text-orange-600">{{ $class['certificates'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Ustadz Performance -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-user-tie text-indigo-600 mr-2"></i>
                Performa Ustadz
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Ustadz</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kelas</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Santri</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Avg Progress
                            </th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Verification
                                Rate</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Performance
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($ustadz_performance as $index => $ustadz)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full 
                                         {{ $index < 3 ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600' }} font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                            {{ strtoupper(substr($ustadz['name'], 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900">{{ $ustadz['name'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $ustadz['email'] }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-sm font-medium text-gray-900">{{ $ustadz['class'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-lg font-bold text-blue-600">{{ $ustadz['total_santri'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex flex-col items-center">
                                        <span
                                            class="text-lg font-bold text-green-600">{{ number_format($ustadz['avg_progress'], 1) }}%</span>
                                        <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-green-500 h-2 rounded-full"
                                                style="width: {{ $ustadz['avg_progress'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="text-lg font-bold text-purple-600">{{ $ustadz['verification_rate'] }}%</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="px-3 py-1 rounded-full text-xs font-semibold
                                         {{ $ustadz['performance_score'] >= 80
                                             ? 'bg-green-100 text-green-800'
                                             : ($ustadz['performance_score'] >= 60
                                                 ? 'bg-yellow-100 text-yellow-800'
                                                 : 'bg-red-100 text-red-800') }}">
                                        {{ $ustadz['performance_score'] }}/100
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Strengths & Areas for Improvement -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- Strengths -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-6 border-2 border-green-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-check-circle text-green-600 mr-2"></i>
                    Kekuatan (Strengths)
                </h3>

                <div class="space-y-3">
                    @foreach ($strengths as $strength)
                        <div class="flex items-start p-4 bg-white rounded-lg shadow-sm">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-star text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $strength['title'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $strength['description'] }}</p>
                                <p class="text-xs text-green-600 font-semibold mt-2">
                                    <i class="fas fa-arrow-up mr-1"></i>{{ $strength['score'] }}/100
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Areas for Improvement -->
            <div class="bg-gradient-to-br from-orange-50 to-red-50 rounded-2xl p-6 border-2 border-orange-200">
                <h3 class="text-lg font-bold text-gray-900 mb-4">
                    <i class="fas fa-exclamation-triangle text-orange-600 mr-2"></i>
                    Area Pengembangan
                </h3>

                <div class="space-y-3">
                    @foreach ($improvements as $improvement)
                        <div class="flex items-start p-4 bg-white rounded-lg shadow-sm">
                            <div
                                class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                                <i class="fas fa-arrow-up text-orange-600"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">{{ $improvement['title'] }}</p>
                                <p class="text-sm text-gray-600 mt-1">{{ $improvement['description'] }}</p>
                                <div class="mt-2">
                                    <p class="text-xs text-orange-600 font-semibold">
                                        Target: {{ $improvement['target'] }} | Current: {{ $improvement['current'] }}
                                    </p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-orange-500 h-2 rounded-full"
                                            style="width: {{ ($improvement['current'] / $improvement['target']) * 100 }}%">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Performance Gauge
            var ctx = document.getElementById('performanceGauge').getContext('2d');
            var gradient = ctx.createLinearGradient(0, 0, 0, 200);
            gradient.addColorStop(0, '#10B981');
            gradient.addColorStop(0.5, '#F59E0B');
            gradient.addColorStop(1, '#EF4444');

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [{{ $performance['overall_score'] }},
                            {{ 100 - $performance['overall_score'] }}],
                        backgroundColor: [gradient, '#E5E7EB'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: false
                        }
                    }
                }
            });

            function exportReport() {
                window.location.href = '{{ route('stakeholder.performance.export') }}';
            }
        </script>
    @endpush
@endsection
