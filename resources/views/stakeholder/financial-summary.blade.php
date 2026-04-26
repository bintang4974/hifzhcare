@extends('layouts.app-enhanced')

@section('title', 'Financial Summary')
@section('breadcrumb', 'Financial Summary')

@section('content')
    <div class="space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-[280px_minmax(0,1fr)] gap-6 items-start">
            <aside class="xl:sticky xl:top-6">
                @include('components.stakeholder-report-sidebar')
            </aside>

            <div class="min-w-0 space-y-6">
                <section class="rounded-3xl border border-slate-200 bg-white shadow-sm p-6 lg:p-8">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-end xl:justify-between">
                        <div class="max-w-2xl">
                            <p class="text-sm font-semibold uppercase tracking-[0.18em] text-blue-600">Financial Report</p>
                            <h1 class="mt-2 text-3xl lg:text-4xl font-bold tracking-tight text-slate-900">Financial Summary</h1>
                            <p class="mt-3 text-slate-600 leading-relaxed">
                                Ringkasan keuangan dan dana apresiasi pesantren dalam tampilan yang lebih rapi,
                                mudah dipindai, dan siap dipakai untuk review cepat.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <select id="periodFilter"
                                class="h-11 rounded-xl border-slate-300 bg-white px-4 text-sm text-slate-700 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="month" {{ $period === 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="quarter" {{ $period === 'quarter' ? 'selected' : '' }}>Kuartal Ini</option>
                                <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Tahun Ini</option>
                                <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Semua Waktu</option>
                            </select>
                            <button type="button" onclick="exportFinancial()"
                                class="inline-flex h-11 items-center justify-center rounded-xl bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                <i class="fas fa-file-excel mr-2"></i>Export
                            </button>
                            <button type="button" onclick="window.print()"
                                class="inline-flex h-11 items-center justify-center rounded-xl bg-slate-700 px-4 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2">
                                <i class="fas fa-print mr-2"></i>Print
                            </button>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 to-teal-600 p-6 text-white shadow-lg">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                                    <i class="fas fa-wallet text-xl"></i>
                                </div>
                                <p class="text-sm font-medium text-emerald-50">Total Pendapatan</p>
                                <p class="mt-3 text-3xl font-bold leading-none">Rp {{ number_format($financial['total_revenue'], 0, ',', '.') }}</p>
                                <p class="mt-3 text-xs text-emerald-50/90">Dana apresiasi masuk</p>
                            </div>
                            @if ($financial['revenue_growth'] > 0)
                                <span class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold">
                                    +{{ $financial['revenue_growth'] }}%
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-sky-500 to-blue-600 p-6 text-white shadow-lg">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <i class="fas fa-percent text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-sky-50">Platform Fee ({{ $financial['platform_percentage'] }}%)</p>
                        <p class="mt-3 text-3xl font-bold leading-none">Rp {{ number_format($financial['platform_fee'], 0, ',', '.') }}</p>
                        <p class="mt-3 text-xs text-sky-50/90">Fee untuk platform</p>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-500 to-purple-600 p-6 text-white shadow-lg">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <i class="fas fa-mosque text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-violet-50">Dana Pesantren ({{ $financial['pesantren_percentage'] }}%)</p>
                        <p class="mt-3 text-3xl font-bold leading-none">Rp {{ number_format($financial['pesantren_share'], 0, ',', '.') }}</p>
                        <p class="mt-3 text-xs text-violet-50/90">Bagian pesantren</p>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-orange-500 to-amber-600 p-6 text-white shadow-lg">
                        <div class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-white/20">
                            <i class="fas fa-hand-holding-usd text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-orange-50">Dana Ustadz ({{ $financial['ustadz_percentage'] }}%)</p>
                        <p class="mt-3 text-3xl font-bold leading-none">Rp {{ number_format($financial['ustadz_total'], 0, ',', '.') }}</p>
                        <p class="mt-3 text-xs text-orange-50/90">Total untuk ustadz</p>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 flex items-center">
                                <i class="fas fa-chart-pie text-emerald-600 mr-3"></i>
                                Distribusi Pendapatan
                            </h2>
                            <p class="mt-2 text-sm text-slate-500">Komposisi pembagian dana secara visual dan ringkas.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 xl:grid-cols-[360px_minmax(0,1fr)] gap-8 items-center">
                        <div class="rounded-2xl bg-slate-50 p-4">
                            <canvas id="revenueDistributionChart" height="300"></canvas>
                        </div>

                        <div class="space-y-4">
                            <div class="rounded-2xl border border-emerald-200 bg-emerald-50/70 p-5">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <div class="flex items-center gap-3">
                                        <span class="h-3 w-3 rounded-full bg-emerald-500"></span>
                                        <span class="font-semibold text-slate-900">Total Pendapatan</span>
                                    </div>
                                    <span class="text-lg font-bold text-emerald-600">100%</span>
                                </div>
                                <p class="text-2xl font-bold text-slate-900">Rp {{ number_format($financial['total_revenue'], 0, ',', '.') }}</p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="rounded-2xl border border-orange-200 bg-orange-50 p-4">
                                    <p class="text-xs font-medium text-orange-700">Dana Ustadz</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $financial['ustadz_percentage'] }}%</p>
                                    <p class="mt-1 text-sm text-orange-700">Rp {{ number_format($financial['ustadz_total'], 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-2xl border border-violet-200 bg-violet-50 p-4">
                                    <p class="text-xs font-medium text-violet-700">Dana Pesantren</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $financial['pesantren_percentage'] }}%</p>
                                    <p class="mt-1 text-sm text-violet-700">Rp {{ number_format($financial['pesantren_share'], 0, ',', '.') }}</p>
                                </div>
                                <div class="rounded-2xl border border-sky-200 bg-sky-50 p-4">
                                    <p class="text-xs font-medium text-sky-700">Platform Fee</p>
                                    <p class="mt-2 text-lg font-bold text-slate-900">{{ $financial['platform_percentage'] }}%</p>
                                    <p class="mt-1 text-sm text-sky-700">Rp {{ number_format($financial['platform_fee'], 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between mb-6">
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 flex items-center">
                                <i class="fas fa-chart-line text-blue-600 mr-3"></i>
                                Tren Pendapatan Bulanan
                            </h2>
                            <p class="mt-2 text-sm text-slate-500">Pergerakan pendapatan dalam enam bulan terakhir.</p>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-slate-50 p-4">
                        <div class="h-80" id="monthlyRevenueChart"></div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 gap-4 border-t border-slate-200 pt-6 sm:grid-cols-3">
                        <div class="rounded-2xl bg-blue-50 p-5 text-center">
                            <p class="text-sm text-slate-500 mb-1">Rata-rata/Bulan</p>
                            <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($financial['monthly_avg'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl bg-emerald-50 p-5 text-center">
                            <p class="text-sm text-slate-500 mb-1">Bulan Tertinggi</p>
                            <p class="text-2xl font-bold text-emerald-600">Rp {{ number_format($financial['highest_month'], 0, ',', '.') }}</p>
                        </div>
                        <div class="rounded-2xl bg-slate-50 p-5 text-center">
                            <p class="text-sm text-slate-500 mb-1">Pertumbuhan</p>
                            <p class="text-2xl font-bold {{ $financial['revenue_growth'] >= 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $financial['revenue_growth'] >= 0 ? '+' : '' }}{{ $financial['revenue_growth'] }}%
                            </p>
                        </div>
                    </div>
                </section>

                <section class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <div class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center mb-6">
                            <i class="fas fa-tasks text-violet-600 mr-3"></i>
                            Status Donasi
                        </h2>

                        <div class="space-y-4">
                            @foreach ($donation_status as $status)
                                <div class="flex items-center justify-between rounded-2xl border border-{{ $status['color'] }}-200 bg-{{ $status['color'] }}-50 p-4">
                                    <div class="flex items-center gap-4">
                                        <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-{{ $status['color'] }}-100">
                                            <i class="fas fa-{{ $status['icon'] }} text-{{ $status['color'] }}-600"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $status['label'] }}</p>
                                            <p class="text-sm text-slate-500">{{ $status['count'] }} transaksi</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-lg font-bold text-{{ $status['color'] }}-600">Rp {{ number_format($status['amount'], 0, ',', '.') }}</p>
                                        <p class="text-xs text-slate-500">{{ $status['percentage'] }}%</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                        <h2 class="text-xl font-bold text-slate-900 flex items-center mb-6">
                            <i class="fas fa-exchange-alt text-indigo-600 mr-3"></i>
                            Statistik Transaksi
                        </h2>

                        <div class="space-y-4">
                            <div class="rounded-2xl bg-gradient-to-r from-blue-50 to-indigo-50 p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-slate-600 mb-1">Total Transaksi</p>
                                        <p class="text-3xl font-bold text-blue-600">{{ $financial['total_transactions'] }}</p>
                                    </div>
                                    <div class="flex h-14 w-14 items-center justify-center rounded-full bg-blue-100">
                                        <i class="fas fa-receipt text-2xl text-blue-600"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div class="rounded-2xl bg-emerald-50 p-5">
                                    <p class="text-sm text-slate-600 mb-1">Success Rate</p>
                                    <p class="text-2xl font-bold text-emerald-600">{{ $financial['success_rate'] }}%</p>
                                </div>
                                <div class="rounded-2xl bg-violet-50 p-5">
                                    <p class="text-sm text-slate-600 mb-1">Avg. Donation</p>
                                    <p class="text-xl font-bold text-violet-600">Rp {{ number_format($financial['avg_donation'], 0, ',', '.') }}</p>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-orange-50 p-5">
                                <p class="text-sm text-slate-600 mb-3">Pending Approval</p>
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-2xl font-bold text-orange-600">{{ $financial['pending_count'] }}</span>
                                    <span class="text-lg font-semibold text-slate-900">Rp {{ number_format($financial['pending_amount'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center mb-6">
                        <i class="fas fa-star text-amber-500 mr-3"></i>
                        Top 10 Contributors (Wali Donatur Terbanyak)
                    </h2>

                    <div class="overflow-hidden rounded-2xl border border-slate-200">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Rank</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Nama Wali</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Total Donasi</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Jumlah Transaksi</th>
                                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-slate-500">Rata-rata</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Terakhir Donasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white">
                                    @foreach ($top_contributors as $index => $contributor)
                                        <tr class="hover:bg-slate-50/80">
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                @if ($index < 3)
                                                    <span class="text-2xl">
                                                        @if ($index === 0)
                                                            🥇
                                                        @elseif($index === 1)
                                                            🥈
                                                        @else
                                                            🥉
                                                        @endif
                                                    </span>
                                                @else
                                                    <span class="font-bold text-slate-600">{{ $index + 1 }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-pink-400 to-rose-500 font-bold text-white">
                                                        {{ strtoupper(substr($contributor['name'], 0, 1)) }}
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-slate-900">{{ $contributor['name'] }}</p>
                                                        <p class="text-xs text-slate-500">{{ $contributor['email'] }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                                <p class="text-lg font-bold text-emerald-600">Rp {{ number_format($contributor['total_amount'], 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                                <span class="text-lg font-bold text-blue-600">{{ $contributor['transaction_count'] }}</span>
                                            </td>
                                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                                <p class="text-sm font-semibold text-slate-900">Rp {{ number_format($contributor['avg_amount'], 0, ',', '.') }}</p>
                                            </td>
                                            <td class="px-4 py-4 whitespace-nowrap">
                                                <p class="text-sm text-slate-600">{{ $contributor['last_donation'] }}</p>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="rounded-3xl border border-slate-200 bg-white p-6 lg:p-8 shadow-sm">
                    <h2 class="text-xl font-bold text-slate-900 flex items-center mb-6">
                        <i class="fas fa-award text-indigo-600 mr-3"></i>
                        Top 10 Ustadz Penerima Dana Terbanyak
                    </h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-2">
                        @foreach ($top_ustadz as $index => $ustadz)
                            <div class="rounded-2xl border border-indigo-200 bg-gradient-to-r from-indigo-50 to-purple-50 p-5">
                                <div class="mb-4 flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">
                                            @if ($index === 0)
                                                🥇
                                            @elseif($index === 1)
                                                🥈
                                            @elseif($index === 2)
                                                🥉
                                            @else
                                                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600">{{ $index + 1 }}</span>
                                            @endif
                                        </span>
                                        <div>
                                            <p class="font-bold text-slate-900">{{ $ustadz['name'] }}</p>
                                            <p class="text-xs text-slate-500">{{ $ustadz['class'] }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                                    <div class="rounded-xl bg-white/80 p-3 text-center">
                                        <p class="text-xs text-slate-500">Total</p>
                                        <p class="mt-1 text-sm font-bold text-emerald-600">Rp {{ number_format($ustadz['total_received'], 0, ',', '.') }}</p>
                                    </div>
                                    <div class="rounded-xl bg-white/80 p-3 text-center">
                                        <p class="text-xs text-slate-500">Donasi</p>
                                        <p class="mt-1 text-sm font-bold text-blue-600">{{ $ustadz['donation_count'] }}</p>
                                    </div>
                                    <div class="rounded-xl bg-white/80 p-3 text-center">
                                        <p class="text-xs text-slate-500">Dicairkan</p>
                                        <p class="mt-1 text-sm font-bold text-violet-600">Rp {{ number_format($ustadz['disbursed'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <section class="grid grid-cols-1 gap-5 md:grid-cols-3">
                    <div class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-teal-50 p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900">Cash Flow Health</h3>
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-100">
                                <i class="fas fa-check-circle text-xl text-emerald-600"></i>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-emerald-600 mb-2">{{ $financial['cash_flow_score'] }}/100</p>
                        <p class="text-sm text-slate-600">Status: <strong class="text-emerald-600">Sehat</strong></p>
                        <div class="mt-4 h-3 w-full rounded-full bg-slate-200">
                            <div class="h-3 rounded-full bg-emerald-500" style="width: {{ $financial['cash_flow_score'] }}%"></div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-sky-200 bg-gradient-to-br from-sky-50 to-indigo-50 p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900">Collection Rate</h3>
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-sky-100">
                                <i class="fas fa-chart-line text-xl text-sky-600"></i>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-sky-600 mb-2">{{ $financial['collection_rate'] }}%</p>
                        <p class="text-sm text-slate-600">Tingkat Koleksi Dana</p>
                        <div class="mt-4 h-3 w-full rounded-full bg-slate-200">
                            <div class="h-3 rounded-full bg-sky-500" style="width: {{ $financial['collection_rate'] }}%"></div>
                        </div>
                    </div>

                    <div class="rounded-3xl border border-violet-200 bg-gradient-to-br from-violet-50 to-pink-50 p-6 shadow-sm">
                        <div class="mb-4 flex items-center justify-between">
                            <h3 class="font-bold text-slate-900">Disbursement Rate</h3>
                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-violet-100">
                                <i class="fas fa-hand-holding-usd text-xl text-violet-600"></i>
                            </div>
                        </div>
                        <p class="text-4xl font-bold text-violet-600 mb-2">{{ $financial['disbursement_rate'] }}%</p>
                        <p class="text-sm text-slate-600">Tingkat Pencairan</p>
                        <div class="mt-4 h-3 w-full rounded-full bg-slate-200">
                            <div class="h-3 rounded-full bg-violet-500" style="width: {{ $financial['disbursement_rate'] }}%"></div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            // Revenue Distribution Pie Chart
            var ctx = document.getElementById('revenueDistributionChart').getContext('2d');
            const ustadzPercentage = {{ $financial['ustadz_percentage'] }};
            const pesantrenPercentage = {{ $financial['pesantren_percentage'] }};
            const platformPercentage = {{ $financial['platform_percentage'] }};

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: [
                        `Dana Ustadz (${ustadzPercentage}%)`,
                        `Dana Pesantren (${pesantrenPercentage}%)`,
                        `Platform Fee (${platformPercentage}%)`
                    ],
                    datasets: [{
                        data: [ustadzPercentage, pesantrenPercentage, platformPercentage],
                        backgroundColor: ['#F97316', '#A855F7', '#3B82F6'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Monthly Revenue Trend
            var monthlyRevenueChart = new ApexCharts(document.querySelector("#monthlyRevenueChart"), {
                series: [{
                    name: 'Revenue',
                    data: @json($chartData['monthly_revenue'])
                }],
                chart: {
                    type: 'area',
                    height: 320,
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#10B981'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.3,
                    }
                },
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return 'Rp ' + (val / 1000000).toFixed(1) + 'jt';
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
                    labels: {
                        formatter: function(val) {
                            return 'Rp ' + (val / 1000000).toFixed(0) + 'jt';
                        }
                    }
                },
                tooltip: {
                    theme: 'dark',
                    y: {
                        formatter: function(val) {
                            return 'Rp ' + val.toLocaleString('id-ID');
                        }
                    }
                }
            });
            monthlyRevenueChart.render();

            document.getElementById('periodFilter').addEventListener('change', function() {
                const url = new URL(window.location.href);
                url.searchParams.set('period', this.value);
                window.location.href = url.toString();
            });

            function exportFinancial() {
                const url = new URL('{{ route('stakeholder.export') }}', window.location.origin);
                url.searchParams.set('type', document.getElementById('periodFilter').value || 'year');
                window.location.href = url.toString();
            }
        </script>
    @endpush
@endsection
