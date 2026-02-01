@extends('layouts.app-enhanced')

@section('title', 'Dashboard Wali')
@section('breadcrumb', 'Dashboard Wali')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div
            class="relative bg-gradient-to-br from-purple-600 via-purple-700 to-indigo-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>

            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Assalamualaikum, {{ auth()->user()->name }}! 👨‍👩‍👧</h1>
                <p class="text-purple-100 text-lg">Monitor perkembangan hafalan putra-putri Anda</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-purple-100 text-sm mb-1">Jumlah Putra/Putri</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_children'] }}</h3>
                        <p class="text-purple-100 text-xs mt-1">Santri terdaftar</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-purple-100 text-sm mb-1">Total Donasi</p>
                        <h3 class="text-2xl font-bold">Rp {{ number_format($stats['total_donations'], 0, ',', '.') }}</h3>
                        <p class="text-purple-100 text-xs mt-1">Dana apresiasi ustadz</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Children Progress Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($childrenStats as $childStat)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                    <!-- Card Header -->
                    <div class="bg-gradient-to-r from-blue-500 to-purple-600 p-6 text-white">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-blue-600 font-bold text-2xl shadow-lg">
                                {{ substr($childStat['santri']->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-xl">{{ $childStat['santri']->user->name }}</h4>
                                <p class="text-blue-100 text-sm">{{ $childStat['santri']->nis }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <!-- Class Info -->
                        <div class="mb-4 p-3 bg-purple-50 rounded-lg border border-purple-200">
                            <p class="text-xs text-purple-600 font-semibold mb-1">KELAS</p>
                            <p class="font-bold text-gray-900">
                                {{ $childStat['santri']->activeClasses->first()?->name ?? 'Belum ada kelas' }}</p>
                        </div>

                        <!-- Stats Grid -->
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="text-center p-3 bg-green-50 rounded-lg">
                                <p class="text-2xl font-bold text-green-600">{{ $childStat['total_hafalan'] }}</p>
                                <p class="text-xs text-gray-600">Total Hafalan</p>
                            </div>
                            <div class="text-center p-3 bg-blue-50 rounded-lg">
                                <p class="text-2xl font-bold text-blue-600">{{ $childStat['verified_hafalan'] }}</p>
                                <p class="text-xs text-gray-600">Terverifikasi</p>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-semibold text-gray-700">Progress Hafalan</span>
                                <span
                                    class="text-sm font-bold text-blue-600">{{ $childStat['progress_percentage'] }}%</span>
                            </div>
                            <div class="bg-gray-200 rounded-full h-3 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-purple-600 h-3 rounded-full transition-all duration-500 shadow-inner"
                                    style="width: {{ $childStat['progress_percentage'] }}%"></div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <a href="{{ route('hafalan.progress', $childStat['santri']->user_id) }}"
                            class="block text-center px-4 py-3 bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                            <i class="fas fa-chart-line mr-2"></i>Lihat Detail Progress
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <div class="bg-white rounded-xl shadow-lg p-12 text-center">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-graduate text-5xl text-gray-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Santri Terdaftar</h3>
                        <p class="text-gray-600">Silakan hubungi admin pesantren untuk mendaftarkan putra/putri Anda</p>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Recent Donations & Statistics -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Donations -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hand-holding-usd text-purple-600"></i>
                        </div>
                        Riwayat Donasi
                    </h3>
                    <a href="#" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @forelse($recentDonations as $donation)
                        <div
                            class="flex items-center justify-between p-4 bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-lg hover:shadow-md transition-all">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                                    <p class="font-semibold text-gray-900 truncate">{{ $donation->ustadz->user->name }}</p>
                                </div>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-user-graduate text-blue-600 mr-1"></i>
                                    Untuk: {{ $donation->santri->user->name }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-calendar mr-1"></i>{{ $donation->created_at->format('d M Y') }}
                                </p>
                            </div>
                            <div class="ml-4 text-right flex-shrink-0">
                                <p class="text-lg font-bold text-purple-600">Rp
                                    {{ number_format($donation->amount, 0, ',', '.') }}</p>
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $donation->status === 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $donation->status === 'verified' ? 'Verified' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-gift text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 mb-3">Belum ada riwayat donasi</p>
                            <button
                                class="px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition">
                                <i class="fas fa-plus-circle mr-2"></i>Donasi Sekarang
                            </button>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Summary Statistics -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chart-pie text-blue-600"></i>
                    </div>
                    Ringkasan Statistik
                </h3>

                <div class="space-y-6">
                    <!-- Total Hafalan All Children -->
                    <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Total Hafalan (Semua Anak)</span>
                            <span class="text-2xl font-bold text-blue-600">
                                {{ $childrenStats->sum('total_hafalan') }}
                            </span>
                        </div>
                        <div class="h-2 bg-blue-200 rounded-full overflow-hidden">
                            <div class="h-2 bg-blue-600 rounded-full" style="width: 100%"></div>
                        </div>
                    </div>

                    <!-- Total Verified -->
                    <div class="p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border border-green-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Hafalan Terverifikasi</span>
                            <span class="text-2xl font-bold text-green-600">
                                {{ $childrenStats->sum('verified_hafalan') }}
                            </span>
                        </div>
                        <div class="h-2 bg-green-200 rounded-full overflow-hidden">
                            <div class="h-2 bg-green-600 rounded-full"
                                style="width: {{ $childrenStats->sum('total_hafalan') > 0 ? ($childrenStats->sum('verified_hafalan') / $childrenStats->sum('total_hafalan')) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Average Progress -->
                    <div class="p-4 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-200">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700">Rata-rata Progress</span>
                            <span class="text-2xl font-bold text-purple-600">
                                {{ $stats['total_children'] > 0 ? round($childrenStats->avg('progress_percentage'), 1) : 0 }}%
                            </span>
                        </div>
                        <div class="h-2 bg-purple-200 rounded-full overflow-hidden">
                            <div class="h-2 bg-purple-600 rounded-full"
                                style="width: {{ $stats['total_children'] > 0 ? round($childrenStats->avg('progress_percentage'), 1) : 0 }}%">
                            </div>
                        </div>
                    </div>

                    <!-- Most Active Child -->
                    @if ($childrenStats->count() > 0)
                        <div class="p-4 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-xl border border-yellow-200">
                            <p class="text-sm font-semibold text-gray-700 mb-2">🏆 Paling Aktif</p>
                            @php
                                $mostActive = $childrenStats->sortByDesc('total_hafalan')->first();
                            @endphp
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr($mostActive['santri']->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $mostActive['santri']->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $mostActive['total_hafalan'] }} hafalan</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
