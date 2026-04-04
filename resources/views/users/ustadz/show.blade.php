@extends('layouts.app-enhanced')

@section('title', 'Detail Ustadz')
@section('breadcrumb', 'Pengguna / Ustadz / Detail')

@section('content')
    <div class="space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Ustadz</h1>
                <p class="text-gray-600">Informasi lengkap pengajar</p>
            </div>
            <div class="flex gap-3">
                @can('edit_users')
                    <a href="{{ route('users.ustadz.edit', $ustadz->id) }}"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl shadow-md transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('users.ustadz.index') }}"
                    class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Profile Card -->
        <div
            class="relative bg-gradient-to-br from-green-600 via-emerald-700 to-teal-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>

            <div class="relative z-10">
                <div class="flex items-start gap-6 flex-wrap">
                    <div
                        class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center text-green-600 font-bold text-6xl shadow-2xl flex-shrink-0">
                        {{ substr($ustadz->user->name, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-3xl font-bold mb-2">{{ $ustadz->user->name }}</h2>
                        <div class="flex items-center gap-4 mb-4 flex-wrap">
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-id-card mr-2"></i>NIP: {{ $ustadz->nip }}
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-calendar-alt mr-2"></i>Bergabung: {{ $ustadz->join_date->format('d M Y') }}
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-{{ $ustadz->user->status === 'active' ? 'check-circle text-green-300' : 'ban text-red-300' }} mr-2"></i>{{ ucfirst($ustadz->user->status) }}
                            </span>
                        </div>
                        @if($ustadz->specialization)
                            <p class="text-lg opacity-90">
                                <i class="fas fa-book mr-2"></i>{{ $ustadz->specialization }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Classes -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Kelas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_classes'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                        <i class="fas fa-chalkboard"></i>
                    </div>
                </div>
            </div>

            <!-- Total Students -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Santri</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_students'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Verified Hafalan -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Hafalan Diverifikasi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_verified'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 text-xl">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
            </div>

            <!-- Total Appreciation -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Apresiasi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">Rp{{ number_format($stats['total_appreciation'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 text-xl">
                        <i class="fas fa-gift"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Personal Information -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-user-circle text-green-600 mr-3"></i>Informasi Pribadi
                </h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->user->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">NIP</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->nip }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Email</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->user->email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nomor HP</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->user->phone ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-medium text-gray-600">Spesialisasi</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->specialization ?? 'Belum diisi' }}</p>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm font-medium text-gray-600">Alamat</label>
                        <p class="text-lg font-semibold text-gray-900 mt-1">{{ $ustadz->address ?? 'Belum diisi' }}</p>
                    </div>
                </div>
            </div>

            <!-- Statistics Summary -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-chart-bar text-blue-600 mr-3"></i>Statistik
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Verifikasi Hari Ini</span>
                        <span class="font-bold text-lg text-green-600">{{ $stats['verified_today'] }}</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                        <span class="text-gray-600">Verifikasi Bulan Ini</span>
                        <span class="font-bold text-lg text-blue-600">{{ $stats['verified_this_month'] }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Hafalan Pending</span>
                        <span class="font-bold text-lg text-orange-600">{{ $stats['pending_hafalan'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes Section -->
        @if($stats['total_classes'] > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-list text-green-600 mr-3"></i>Daftar Kelas Aktif
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">No.</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Nama Kelas</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Juz</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Total Santri</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ustadz->activeClasses as $class)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $class->name }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $class->juz_range ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $class->activeSantri->count() }} santri</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- Recent Verified Hafalans -->
        @if($ustadz->verifiedHafalans->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-history text-purple-600 mr-3"></i>10 Hafalan Terakhir Diverifikasi
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Santri</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Juz</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal Verifikasi</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ustadz->verifiedHafalans as $hafalan)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $hafalan->santriProfile->user->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $hafalan->juz ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $hafalan->verified_at?->format('d M Y') ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($hafalan->status === 'verified')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Terverifikasi</span>
                                        @elseif($hafalan->status === 'pending')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full">Pending</span>
                                        @else
                                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Ditolak</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection

