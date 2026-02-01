@extends('layouts.app-enhanced')

@section('title', 'Dashboard Ustadz')
@section('breadcrumb', 'Dashboard Ustadz')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div
            class="relative bg-gradient-to-br from-green-600 via-green-700 to-emerald-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-white opacity-5 rounded-full -ml-32 -mb-32"></div>

            <div class="relative z-10">
                <h1 class="text-3xl font-bold mb-2">Assalamualaikum, Ustadz {{ auth()->user()->name }}! 🌟</h1>
                <p class="text-green-100 text-lg">Semangat membimbing para santri hari ini</p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Kelas Anda</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_classes'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_students'] }}</h3>
                    </div>
                    <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                        <p class="text-green-100 text-sm mb-1">Verifikasi Hari Ini</p>
                        <h3 class="text-3xl font-bold">{{ $stats['verified_today'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Hafalan Pending</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['pending_hafalan'] }}</h3>
                        <p class="text-yellow-100 text-xs">Menunggu verifikasi</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-4xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Verifikasi</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['verified_today'] }}</h3>
                        <p class="text-green-100 text-xs">Hari ini</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-check-double text-4xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Kelas Aktif</p>
                        <h3 class="text-4xl font-bold mb-2">{{ $stats['total_classes'] }}</h3>
                        <p class="text-blue-100 text-xs">Yang Anda ajar</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chalkboard text-4xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Dana Apresiasi</p>
                        <h3 class="text-2xl font-bold mb-2">Rp
                            {{ number_format($stats['total_appreciation'], 0, ',', '.') }}</h3>
                        <p class="text-purple-100 text-xs">Total diterima</p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-gift text-4xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Pending Hafalan (Priority) -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900 flex items-center">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-tasks text-yellow-600"></i>
                        </div>
                        Hafalan Menunggu Verifikasi
                    </h3>
                    <a href="{{ route('hafalan.index') }}?status=pending"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>

                <div class="space-y-3 max-h-[500px] overflow-y-auto">
                    @forelse($pendingHafalans as $hafalan)
                        <div
                            class="group bg-gradient-to-r from-yellow-50 to-orange-50 hover:from-yellow-100 hover:to-orange-100 border-l-4 border-yellow-500 rounded-lg p-4 transition-all duration-300 hover:shadow-md">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center text-white font-bold text-lg flex-shrink-0 shadow-lg">
                                        {{ substr($hafalan->user->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-gray-900 mb-1 truncate">{{ $hafalan->user->name }}</h4>
                                        <p class="text-sm text-gray-700 mb-1">
                                            <i class="fas fa-book-open text-blue-600 mr-1"></i>
                                            {{ $hafalan->surah_name }} • Ayat
                                            {{ $hafalan->ayat_start }}-{{ $hafalan->ayat_end }}
                                        </p>
                                        <div class="flex items-center gap-3 text-xs text-gray-600">
                                            <span><i class="fas fa-bookmark mr-1"></i>Juz {{ $hafalan->juz_number }}</span>
                                            <span><i class="fas fa-list-ol mr-1"></i>{{ $hafalan->ayat_count }} ayat</span>
                                            <span><i
                                                    class="fas fa-clock mr-1"></i>{{ $hafalan->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 flex-shrink-0">
                                    <a href="{{ route('hafalan.show', $hafalan->id) }}"
                                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg transition shadow-md">
                                        <i class="fas fa-eye mr-1"></i>Review
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16">
                            <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-check-circle text-5xl text-green-600"></i>
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-2">Semua Hafalan Sudah Terverifikasi! 🎉</h4>
                            <p class="text-gray-600">Tidak ada hafalan yang menunggu verifikasi saat ini</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- My Classes -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                    </div>
                    Kelas Saya
                </h3>

                <div class="space-y-4">
                    @forelse($myClasses as $class)
                        <div
                            class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl p-4 border border-blue-200 hover:shadow-lg transition-all duration-300 card-hover">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 mb-1">{{ $class->name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $class->code }}</p>
                                </div>
                                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-semibold rounded-full">
                                    {{ $class->activeSantri->count() }} santri
                                </span>
                            </div>

                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center text-gray-600">
                                    <i class="fas fa-users mr-2"></i>
                                    <span>{{ $class->current_student_count }}/{{ $class->max_capacity }}</span>
                                </div>
                                <a href="{{ route('classes.show', $class->id) }}"
                                    class="text-blue-600 hover:text-blue-700 font-medium">
                                    Lihat Detail <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>

                            <div class="mt-3 bg-blue-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-500"
                                    style="width: {{ ($class->current_student_count / $class->max_capacity) * 100 }}%">
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <i class="fas fa-chalkboard text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Belum ada kelas yang ditugaskan</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Verified -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6">Hafalan Terverifikasi (Terbaru)</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @forelse($recentVerified->take(6) as $hafalan)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 hover:shadow-md transition-all">
                        <div class="flex items-center gap-3 mb-3">
                            <div
                                class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center text-white font-bold">
                                {{ substr($hafalan->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-semibold text-gray-900 truncate">{{ $hafalan->user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $hafalan->verified_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700">
                            <i class="fas fa-book-open text-green-600 mr-1"></i>
                            {{ $hafalan->surah_name }} ({{ $hafalan->ayat_start }}-{{ $hafalan->ayat_end }})
                        </p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <p class="text-gray-500">Belum ada hafalan yang terverifikasi</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
