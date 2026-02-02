@extends('layouts.app-enhanced')

@section('title', 'Detail Santri')
@section('breadcrumb', 'Pengguna / Santri / Detail')

@section('content')
    <div class="space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Santri</h1>
                <p class="text-gray-600">Informasi lengkap dan progress hafalan</p>
            </div>
            <div class="flex gap-3">
                @can('edit_users')
                    <a href="{{ route('users.santri.edit', $santri->id) }}"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl shadow-md transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('users.santri.index') }}"
                    class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Profile Card -->
        <div
            class="relative bg-gradient-to-br from-blue-600 via-indigo-700 to-purple-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>

            <div class="relative z-10">
                <div class="flex items-start gap-6 flex-wrap">
                    <div
                        class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center text-blue-600 font-bold text-6xl shadow-2xl flex-shrink-0">
                        {{ substr($santri->user->name, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-3xl font-bold mb-2">{{ $santri->user->name }}</h2>
                        <div class="flex items-center gap-4 mb-4 flex-wrap">
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-id-card mr-2"></i>NIS: {{ $santri->nis }}
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-birthday-cake mr-2"></i>{{ $santri->age }} tahun
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-{{ $santri->gender === 'L' ? 'mars' : 'venus' }} mr-2"></i>
                                {{ $santri->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                                <p class="text-blue-100 text-sm mb-1">Total Hafalan</p>
                                <h3 class="text-3xl font-bold">{{ $santri->hafalans->count() }}</h3>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                                <p class="text-blue-100 text-sm mb-1">Juz Selesai</p>
                                <h3 class="text-3xl font-bold">{{ $santri->total_juz_completed }}/30</h3>
                            </div>
                            <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                                <p class="text-blue-100 text-sm mb-1">Progress</p>
                                <h3 class="text-3xl font-bold">{{ $santri->progress_percentage }}%</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Hafalan Verified</p>
                        <h3 class="text-3xl font-bold text-gray-900">
                            {{ $santri->hafalans()->where('status', 'verified')->count() }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-3xl text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pending</p>
                        <h3 class="text-3xl font-bold text-gray-900">
                            {{ $santri->hafalans()->where('status', 'pending')->count() }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-3xl text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total Ayat</p>
                        <h3 class="text-3xl font-bold text-gray-900">{{ number_format($santri->total_ayat_completed) }}
                        </h3>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-list-ol text-3xl text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500 card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kelas</p>
                        <h3 class="text-2xl font-bold text-gray-900">{{ $santri->activeClasses->count() }}</h3>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chalkboard text-3xl text-purple-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Content -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden" x-data="{ activeTab: 'profile' }">
            <!-- Tab Headers -->
            <div class="border-b border-gray-200 bg-gray-50">
                <div class="flex overflow-x-auto">
                    <button @click="activeTab = 'profile'"
                        :class="activeTab === 'profile' ? 'border-blue-600 text-blue-600 bg-white' :
                            'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
                        class="px-6 py-4 border-b-2 font-semibold transition whitespace-nowrap">
                        <i class="fas fa-user mr-2"></i>Profil
                    </button>
                    <button @click="activeTab = 'hafalan'"
                        :class="activeTab === 'hafalan' ? 'border-blue-600 text-blue-600 bg-white' :
                            'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
                        class="px-6 py-4 border-b-2 font-semibold transition whitespace-nowrap">
                        <i class="fas fa-book-open mr-2"></i>Hafalan ({{ $santri->hafalans->count() }})
                    </button>
                    <button @click="activeTab = 'progress'"
                        :class="activeTab === 'progress' ? 'border-blue-600 text-blue-600 bg-white' :
                            'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
                        class="px-6 py-4 border-b-2 font-semibold transition whitespace-nowrap">
                        <i class="fas fa-chart-line mr-2"></i>Progress
                    </button>
                    <button @click="activeTab = 'wali'"
                        :class="activeTab === 'wali' ? 'border-blue-600 text-blue-600 bg-white' :
                            'border-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100'"
                        class="px-6 py-4 border-b-2 font-semibold transition whitespace-nowrap">
                        <i class="fas fa-user-friends mr-2"></i>Wali
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Profile Tab -->
                <div x-show="activeTab === 'profile'">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Informasi Pribadi</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">NIS</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->nis }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Lahir</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->birth_date->format('d F Y') }}
                                ({{ $santri->age }} tahun)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jenis Kelamin</label>
                            <p class="text-base font-semibold text-gray-900">
                                {{ $santri->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->user->email ?? '-' }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->user->phone }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Masuk</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->entry_date->format('d F Y') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Status</label>
                            <span
                                class="px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $santri->user->status === 'active'
                                ? 'bg-green-100 text-green-800'
                                : ($santri->user->status === 'pending'
                                    ? 'bg-yellow-100 text-yellow-800'
                                    : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($santri->user->status) }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                            <p class="text-base font-semibold text-gray-900">{{ $santri->address }}</p>
                        </div>
                    </div>

                    <!-- Kelas Info -->
                    <div class="mt-8">
                        <h4 class="text-lg font-bold text-gray-900 mb-4">Kelas</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @forelse($santri->activeClasses as $class)
                                <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <h5 class="font-bold text-gray-900">{{ $class->name }}</h5>
                                    <p class="text-sm text-gray-600">{{ $class->code }}</p>
                                    <p class="text-sm text-gray-600 mt-1">
                                        Ustadz: {{ $class->activeUstadz->first()?->user->name ?? 'Belum ada' }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-gray-500 col-span-2">Belum terdaftar di kelas manapun</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Hafalan Tab -->
                <div x-show="activeTab === 'hafalan'" x-cloak>
                    <div class="space-y-3">
                        @forelse($santri->hafalans()->latest()->get() as $hafalan)
                            <div
                                class="p-4 bg-gray-50 rounded-lg border {{ $hafalan->status === 'verified' ? 'border-green-200' : ($hafalan->status === 'rejected' ? 'border-red-200' : 'border-yellow-200') }}">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <h5 class="font-bold text-gray-900">{{ $hafalan->surah_name }}</h5>
                                        <p class="text-sm text-gray-600">Ayat
                                            {{ $hafalan->ayat_start }}-{{ $hafalan->ayat_end }} • Juz
                                            {{ $hafalan->juz_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $hafalan->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span
                                            class="px-3 py-1 text-xs font-semibold rounded-full 
                                    {{ $hafalan->status === 'verified'
                                        ? 'bg-green-100 text-green-800'
                                        : ($hafalan->status === 'rejected'
                                            ? 'bg-red-100 text-red-800'
                                            : 'bg-yellow-100 text-yellow-800') }}">
                                            {{ ucfirst($hafalan->status) }}
                                        </span>
                                        <a href="{{ route('hafalan.show', $hafalan->id) }}"
                                            class="text-blue-600 hover:text-blue-700">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <i class="fas fa-book-open text-5xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada hafalan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Progress Tab -->
                <div x-show="activeTab === 'progress'" x-cloak>
                    <h3 class="text-xl font-bold text-gray-900 mb-6">Progress Hafalan Per Juz</h3>
                    <div class="grid grid-cols-5 sm:grid-cols-6 md:grid-cols-10 gap-3">
                        @for ($juz = 1; $juz <= 30; $juz++)
                            @php
                                $count = $santri
                                    ->hafalans()
                                    ->where('juz_number', $juz)
                                    ->where('status', 'verified')
                                    ->count();
                                $percentage = min(100, $count * 10);
                            @endphp
                            <div class="relative group">
                                <div
                                    class="w-full aspect-square rounded-xl flex flex-col items-center justify-center font-bold text-sm shadow-md
                            {{ $percentage == 100
                                ? 'bg-gradient-to-br from-green-500 to-green-600 text-white'
                                : ($percentage > 50
                                    ? 'bg-gradient-to-br from-yellow-400 to-orange-500 text-gray-900'
                                    : ($percentage > 0
                                        ? 'bg-gradient-to-br from-blue-400 to-blue-500 text-white'
                                        : 'bg-gray-200 text-gray-600')) }}
                            hover:scale-110 transition-all cursor-pointer">
                                    <span class="text-lg">{{ $juz }}</span>
                                    @if ($percentage == 100)
                                        <i class="fas fa-check-circle text-xs mt-1"></i>
                                    @endif
                                </div>
                                <div
                                    class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                    <div
                                        class="bg-gray-900 text-white text-xs rounded-lg px-3 py-2 whitespace-nowrap shadow-xl">
                                        <div class="font-semibold mb-1">Juz {{ $juz }}</div>
                                        <div class="text-gray-300">{{ $count }} hafalan verified</div>
                                        <div class="text-gray-300">Progress: {{ $percentage }}%</div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                <!-- Wali Tab -->
                <div x-show="activeTab === 'wali'" x-cloak>
                    @if ($santri->wali)
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Informasi Wali</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nama Wali</label>
                                <p class="text-base font-semibold text-gray-900">{{ $santri->wali->user->name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Hubungan</label>
                                <p class="text-base font-semibold text-gray-900">{{ ucfirst($santri->wali->relation) }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Email</label>
                                <p class="text-base font-semibold text-gray-900">{{ $santri->wali->user->email ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Nomor HP</label>
                                <p class="text-base font-semibold text-gray-900">{{ $santri->wali->user->phone }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">NIK</label>
                                <p class="text-base font-semibold text-gray-900">{{ $santri->wali->nik ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Pekerjaan</label>
                                <p class="text-base font-semibold text-gray-900">{{ $santri->wali->occupation ?? '-' }}
                                </p>
                            </div>
                            @if ($santri->wali->address)
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Alamat</label>
                                    <p class="text-base font-semibold text-gray-900">{{ $santri->wali->address }}</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-user-friends text-5xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500">Data wali belum tersedia</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
