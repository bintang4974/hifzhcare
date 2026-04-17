@extends('layouts.app-enhanced')

@section('title', 'Progress Hafalan')
@section('breadcrumb', 'Progress Hafalan')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Progress Hafalan</h1>
                <p class="text-gray-600 mt-1">
                    @if (auth()->user()->user_type === 'santri')
                        Monitor perkembangan hafalan Anda
                    @elseif(auth()->user()->user_type === 'wali')
                        Monitor perkembangan hafalan {{ $santri->user->name }}
                    @else
                        Monitor perkembangan hafalan santri
                    @endif
                </p>
            </div>

            @if (auth()->user()->user_type !== 'wali')
                <div class="flex gap-3">
                    <button onclick="window.print()"
                        class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                        <i class="fas fa-print mr-2"></i>Cetak
                    </button>
                    <a href="{{ route('hafalan.create') }}"
                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <i class="fas fa-plus mr-2"></i>Submit Hafalan
                    </a>
                </div>
            @endif
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-percentage text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ number_format($stats['progress_percentage'], 1) }}%</h3>
                <p class="text-blue-100 text-sm">Total Progress</p>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ $stats['completed_juz'] }}</h3>
                <p class="text-green-100 text-sm">Juz Selesai</p>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ $stats['total_verified'] }}</h3>
                <p class="text-yellow-100 text-sm">Hafalan Terverifikasi</p>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-2">
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                </div>
                <h3 class="text-4xl font-bold mb-1">{{ $stats['certificates'] }}</h3>
                <p class="text-purple-100 text-sm">Sertifikat</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- 30 Juz Progress (Left Side) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-lg p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900">Progress 30 Juz</h2>
                        <p class="text-gray-600 text-sm mt-1">Perjalanan menuju Khatam Al-Quran</p>
                    </div>
                    <div class="text-right">
                        <div
                            class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">
                            {{ $stats['completed_juz'] }}/30
                        </div>
                        <p class="text-sm text-gray-500">Juz Selesai</p>
                    </div>
                </div>

                <!-- Vertical Timeline Progress -->
                <div class="relative">
                    @foreach ($juzProgress as $index => $juz)
                        @php
                            $isCompleted = $juz['status'] === 'completed';
                            $isInProgress = $juz['status'] === 'in_progress';
                            $isPending = $juz['status'] === 'pending';

                            // Color schemes
                            if ($isCompleted) {
                                $bgColor = 'from-green-50 to-emerald-50';
                                $borderColor = 'border-green-200';
                                $dotColor = 'bg-green-500';
                                $lineColor = 'bg-green-300';
                                $textColor = 'text-gray-900';
                                $iconColor = 'text-green-500';
                            } elseif ($isInProgress) {
                                $bgColor = 'from-blue-50 to-indigo-50';
                                $borderColor = 'border-blue-300';
                                $dotColor = 'bg-blue-500 ring-4 ring-blue-200';
                                $lineColor = 'bg-blue-200';
                                $textColor = 'text-gray-900';
                                $iconColor = 'text-blue-500';
                            } else {
                                $bgColor = 'from-gray-50 to-gray-50';
                                $borderColor = 'border-gray-200';
                                $dotColor = 'bg-gray-300';
                                $lineColor = 'bg-gray-200';
                                $textColor = 'text-gray-400';
                                $iconColor = 'text-gray-300';
                            }
                        @endphp

                        <div class="flex items-start mb-4 relative">
                            <!-- Vertical Line -->
                            @if ($index < count($juzProgress) - 1)
                                <div class="absolute left-[18px] top-10 bottom-0 w-0.5 {{ $lineColor }} -mb-4"></div>
                            @endif

                            <!-- Dot -->
                            <div class="relative z-10 flex-shrink-0">
                                <div
                                    class="w-10 h-10 rounded-full {{ $dotColor }} flex items-center justify-center transition-all duration-300">
                                    @if ($isCompleted)
                                        <i class="fas fa-check text-white text-lg"></i>
                                    @elseif($isInProgress)
                                        <div class="w-3 h-3 bg-white rounded-full"></div>
                                    @else
                                        <div class="w-3 h-3 bg-white rounded-full opacity-50"></div>
                                    @endif
                                </div>
                            </div>

                            <!-- Content Card -->
                            <div class="flex-1 ml-4">
                                <div
                                    class="bg-gradient-to-r {{ $bgColor }} border-2 {{ $borderColor }} rounded-xl p-5 transition-all duration-300 hover:shadow-md {{ $isInProgress ? 'shadow-lg' : '' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h3 class="text-xl font-bold {{ $textColor }}">Juz {{ $juz['number'] }}
                                                </h3>
                                                @if ($isCompleted)
                                                    <span
                                                        class="px-3 py-1 bg-green-500 text-white text-xs font-semibold rounded-full">
                                                        <i class="fas fa-check-circle mr-1"></i>Selesai
                                                    </span>
                                                @elseif($isInProgress)
                                                    <span
                                                        class="px-3 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full animate-pulse">
                                                        <i class="fas fa-spinner fa-spin mr-1"></i>Sedang Berjalan
                                                    </span>
                                                @endif
                                            </div>

                                            <p class="text-sm {{ $textColor }} mb-3">
                                                {{ $juz['surah_range'] }}
                                            </p>

                                            <!-- Progress Bar -->
                                            <div class="mb-3">
                                                <div
                                                    class="flex items-center justify-between text-xs {{ $textColor }} mb-1">
                                                    <span>Progress</span>
                                                    <span class="font-bold">{{ $juz['progress'] }}%</span>
                                                </div>
                                                <div class="w-full bg-white rounded-full h-3 overflow-hidden shadow-inner">
                                                    <div class="h-full rounded-full transition-all duration-500 {{ $isCompleted ? 'bg-gradient-to-r from-green-400 to-green-500' : ($isInProgress ? 'bg-gradient-to-r from-blue-400 to-blue-500' : 'bg-gray-300') }}"
                                                        style="width: {{ $juz['progress'] }}%"></div>
                                                </div>
                                            </div>

                                            <!-- Stats -->
                                            <div class="grid grid-cols-3 gap-3 text-xs {{ $textColor }}">
                                                <div class="flex items-center">
                                                    <i class="fas fa-book {{ $iconColor }} mr-1.5"></i>
                                                    <span>{{ $juz['ayat_count'] }} Ayat</span>
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-check-double {{ $iconColor }} mr-1.5"></i>
                                                    <span>{{ $juz['verified'] }} Verified</span>
                                                </div>
                                                @if ($juz['certificate_date'])
                                                    <div class="flex items-center">
                                                        <i class="fas fa-certificate {{ $iconColor }} mr-1.5"></i>
                                                        <span>{{ $juz['certificate_date'] }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Action Button -->
                                        @if ($isInProgress && auth()->user()->user_type === 'santri')
                                            <a href="{{ route('hafalan.create', ['juz' => $juz['number']]) }}"
                                                class="ml-4 px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg text-sm font-semibold transition flex items-center gap-2">
                                                <i class="fas fa-plus"></i>
                                                <span>Submit</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Motivational Message -->
                @if ($stats['completed_juz'] < 30)
                    <div class="mt-6 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-l-4 border-blue-500 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-fire text-blue-500 text-2xl mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Tetap Semangat! 🚀</h4>
                                <p class="text-sm text-gray-700">
                                    Anda sudah menyelesaikan {{ $stats['completed_juz'] }} juz.
                                    Tinggal {{ 30 - $stats['completed_juz'] }} juz lagi menuju Khatam Al-Quran!
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div
                        class="mt-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-trophy text-green-500 text-2xl mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-bold text-gray-900 mb-1">Masha Allah! 🎉</h4>
                                <p class="text-sm text-gray-700">
                                    Alhamdulillah, Anda telah menyelesaikan 30 Juz Al-Quran!
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Sidebar -->
            <div class="space-y-6">

                <!-- Monthly Activity -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Aktivitas Bulanan</h3>

                    <!-- Calendar Mini -->
                    <div class="mb-4">
                        <div class="grid grid-cols-7 gap-1 mb-2">
                            @foreach (['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $day)
                                <div class="text-center text-xs font-semibold text-gray-500">{{ $day }}</div>
                            @endforeach
                        </div>
                        <div class="grid grid-cols-7 gap-1">
                            @foreach ($calendar as $date)
                                <div
                                    class="aspect-square flex items-center justify-center text-xs rounded-lg
                                    {{ $date['has_activity'] ? 'bg-green-500 text-white font-bold' : ($date['is_today'] ? 'bg-blue-500 text-white font-bold' : 'bg-gray-100 text-gray-600') }}">
                                    {{ $date['day'] }}
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-sm">
                        <span class="text-gray-600">Streak saat ini</span>
                        <span class="font-bold text-orange-500 flex items-center">
                            <i class="fas fa-fire mr-1"></i>
                            {{ $stats['current_streak'] }} hari
                        </span>
                    </div>
                </div>

                <!-- Recent Achievements -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pencapaian Terbaru</h3>

                    <div class="space-y-3">
                        @forelse($recentAchievements as $achievement)
                            <div
                                class="flex items-start p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200">
                                <div
                                    class="w-10 h-10 bg-yellow-500 rounded-full flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-{{ $achievement['icon'] }} text-white"></i>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="font-bold text-sm text-gray-900">{{ $achievement['title'] }}</h4>
                                    <p class="text-xs text-gray-600">{{ $achievement['date'] }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-6 text-gray-400">
                                <i class="fas fa-medal text-4xl mb-2"></i>
                                <p class="text-sm">Belum ada pencapaian</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                    <h3 class="text-lg font-bold mb-4">Statistik</h3>

                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-white border-opacity-20">
                            <span class="text-sm opacity-90">Rata-rata per Hari</span>
                            <span class="font-bold">{{ $stats['avg_per_day'] }} ayat</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-white border-opacity-20">
                            <span class="text-sm opacity-90">Total Waktu</span>
                            <span class="font-bold">{{ $stats['total_hours'] }} jam</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm opacity-90">Konsistensi</span>
                            <span class="font-bold">{{ $stats['consistency'] }}%</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endpush
