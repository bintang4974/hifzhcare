@extends('layouts.app-enhanced')

@section('title', 'Dashboard Santri')
@section('breadcrumb', 'Dashboard Santri')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header with Progress -->
        <div
            class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>

            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Assalamualaikum, {{ auth()->user()->name }}! 📖</h1>
                <p class="text-blue-100 text-lg">Mari lanjutkan perjalanan menghafal Al-Quran</p>

                <!-- Progress Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-blue-100 text-sm mb-1">Juz Selesai</p>
                        <h3 class="text-4xl font-bold">{{ $stats['total_juz_completed'] }}<span
                                class="text-2xl text-blue-200">/30</span></h3>
                        <div class="mt-2 bg-white bg-opacity-30 rounded-full h-2">
                            <div class="bg-white h-2 rounded-full transition-all duration-500"
                                style="width: {{ ($stats['total_juz_completed'] / 30) * 100 }}%"></div>
                        </div>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-blue-100 text-sm mb-1">Total Ayat</p>
                        <h3 class="text-4xl font-bold">{{ number_format($stats['total_ayat_completed']) }}</h3>
                        <p class="text-blue-100 text-xs mt-1">Dari 6236 ayat</p>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-blue-100 text-sm mb-1">Progress Keseluruhan</p>
                        <h3 class="text-4xl font-bold">{{ $stats['progress_percentage'] }}%</h3>
                        <p class="text-blue-100 text-xs mt-1">Terus semangat!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Hafalan Verified</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['verified_hafalan'] }}</h3>
                        <p class="text-green-100 text-xs">Alhamdulillah</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Menunggu Verifikasi</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['pending_hafalan'] }}</h3>
                        <p class="text-yellow-100 text-xs">Sabar menunggu</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Hafalan</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['total_hafalan'] }}</h3>
                        <p class="text-purple-100 text-xs">Setoran & Muraja'ah</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-book-open text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-pink-500 to-rose-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-pink-100 text-sm mb-1">Kelas Saya</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $myClasses->count() }}</h3>
                        <p class="text-pink-100 text-xs">Kelas aktif</p>
                    </div>
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chalkboard text-3xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Juz Progress Tracker (30 Juz Visual) -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900 flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-bookmark text-blue-600"></i>
                    </div>
                    Progress Per Juz
                </h3>
                <div class="text-sm text-gray-600">
                    <span class="font-semibold text-blue-600">{{ $stats['total_juz_completed'] }}</span> dari 30 Juz selesai
                </div>
            </div>

            <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-10 lg:grid-cols-15 gap-3">
                @foreach ($progressByJuz as $juzData)
                    <div class="relative group">
                        <div
                            class="w-full aspect-square rounded-xl flex flex-col items-center justify-center font-bold text-sm shadow-md
                            {{ $juzData['percentage'] == 100
                                ? 'bg-gradient-to-br from-green-500 to-green-600 text-white'
                                : ($juzData['percentage'] > 50
                                    ? 'bg-gradient-to-br from-yellow-400 to-orange-500 text-gray-900'
                                    : ($juzData['percentage'] > 0
                                        ? 'bg-gradient-to-br from-blue-400 to-blue-500 text-white'
                                        : 'bg-gray-200 text-gray-600')) }}
                            hover:scale-110 transition-all duration-300 cursor-pointer border-2 
                            {{ $juzData['percentage'] == 100 ? 'border-green-600' : 'border-transparent' }}">
                            <span class="text-lg">{{ $juzData['juz'] }}</span>
                            @if ($juzData['percentage'] == 100)
                                <i class="fas fa-check-circle text-xs mt-1"></i>
                            @endif
                        </div>
                        <div
                            class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                            <div class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl">
                                <div class="font-semibold mb-1">Juz {{ $juzData['juz'] }}</div>
                                <div class="text-gray-300">Progress: {{ $juzData['percentage'] }}%</div>
                                <div class="text-gray-300">{{ $juzData['count'] }} hafalan</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-center gap-8 mt-6 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow"></div>
                    <span class="text-gray-700">Selesai (100%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg shadow"></div>
                    <span class="text-gray-700">Dalam Progress (&gt;50%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-400 to-blue-500 rounded-lg shadow"></div>
                    <span class="text-gray-700">Baru Dimulai</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-gray-200 rounded-lg shadow"></div>
                    <span class="text-gray-700">Belum Dimulai</span>
                </div>
            </div>
        </div>

        <!-- My Classes & Recent Hafalan -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- My Classes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard-teacher text-purple-600"></i>
                    </div>
                    Kelas Saya
                </h3>
                <div class="space-y-3">
                    @forelse($myClasses as $class)
                        <div
                            class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-4 hover:shadow-md transition-all">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="font-bold text-gray-900">{{ $class->name }}</h4>
                                <span class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                                    {{ $class->activeSantri->count() }} santri
                                </span>
                            </div>
                            <div class="text-sm text-gray-600 space-y-1">
                                <p><i
                                        class="fas fa-user-tie w-5 text-purple-600"></i>{{ $class->activeUstadz->first()?->user->name ?? 'Belum ada ustadz' }}
                                </p>
                                <p><i class="fas fa-code w-5 text-purple-600"></i>{{ $class->code }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-chalkboard text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500">Belum terdaftar di kelas manapun</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Hafalan -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-history text-green-600"></i>
                        </div>
                        Hafalan Terbaru
                    </h3>
                    <a href="{{ route('hafalan.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @forelse($recentHafalans as $hafalan)
                        <div
                            class="flex items-center justify-between p-3 
                            {{ $hafalan->status === 'verified'
                                ? 'bg-green-50 border-green-200'
                                : ($hafalan->status === 'rejected'
                                    ? 'bg-red-50 border-red-200'
                                    : 'bg-yellow-50 border-yellow-200') }} 
                            border rounded-lg hover:shadow-md transition-all">
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900 truncate">{{ $hafalan->surah_name }}</p>
                                <p class="text-sm text-gray-600">Ayat {{ $hafalan->ayat_start }}-{{ $hafalan->ayat_end }}
                                    • Juz {{ $hafalan->juz_number }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <i class="fas fa-clock mr-1"></i>{{ $hafalan->created_at->diffForHumans() }}
                                </p>
                            </div>
                            <div class="ml-3 flex-shrink-0">
                                <span
                                    class="px-3 py-1 text-xs font-semibold rounded-full 
                            {{ $hafalan->status === 'verified'
                                ? 'bg-green-100 text-green-800'
                                : ($hafalan->status === 'rejected'
                                    ? 'bg-red-100 text-red-800'
                                    : 'bg-yellow-100 text-yellow-800') }}">
                                    @if ($hafalan->status === 'verified')
                                        <i class="fas fa-check mr-1"></i>Verified
                                    @elseif($hafalan->status === 'rejected')
                                        <i class="fas fa-times mr-1"></i>Rejected
                                    @else
                                        <i class="fas fa-clock mr-1"></i>Pending
                                    @endif
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-book-open text-3xl text-gray-400"></i>
                            </div>
                            <p class="text-gray-500 mb-3">Belum ada hafalan</p>
                            <a href="{{ route('hafalan.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                                <i class="fas fa-plus-circle mr-2"></i>Tambah Hafalan Pertama
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Aksi Cepat</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('hafalan.create') }}"
                    class="flex items-center p-6 bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-plus-circle text-3xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Tambah Hafalan</p>
                        <p class="text-blue-100 text-sm">Setoran baru</p>
                    </div>
                </a>

                <a href="{{ route('hafalan.progress') }}"
                    class="flex items-center p-6 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-chart-line text-3xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Lihat Progress</p>
                        <p class="text-green-100 text-sm">Detail statistik</p>
                    </div>
                </a>

                {{-- <a href="{{ route('certificates.index') }}" --}}
                <a href=""
                    class="flex items-center p-6 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-white bg-opacity-20 rounded-xl flex items-center justify-center mr-4">
                        <i class="fas fa-certificate text-3xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-lg">Sertifikat</p>
                        <p class="text-purple-100 text-sm">Lihat sertifikat</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
@endsection
