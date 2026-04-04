@extends('layouts.app-enhanced')

@section('title', 'Detail Wali')
@section('breadcrumb', 'Pengguna / Wali / Detail')

@section('content')
    <div class="space-y-6">
        <!-- Header with Actions -->
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Wali Santri</h1>
                <p class="text-gray-600">Informasi lengkap wali/orang tua santri</p>
            </div>
            <div class="flex gap-3">
                @can('edit_users')
                    <a href="{{ route('users.wali.edit', $wali->id) }}"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl shadow-md transition">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('users.wali.index') }}"
                    class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Profile Card -->
        <div
            class="relative bg-gradient-to-br from-purple-600 via-violet-700 to-indigo-800 rounded-2xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>

            <div class="relative z-10">
                <div class="flex items-start gap-6 flex-wrap">
                    <div
                        class="w-32 h-32 bg-white rounded-2xl flex items-center justify-center text-purple-600 font-bold text-6xl shadow-2xl flex-shrink-0">
                        {{ substr($wali->user->name, 0, 1) }}
                    </div>

                    <div class="flex-1 min-w-0">
                        <h2 class="text-3xl font-bold mb-2">{{ $wali->user->name }}</h2>
                        <div class="flex items-center gap-4 mb-4 flex-wrap">
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-heart mr-2"></i>{{ ucfirst($wali->relation) }}
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-users mr-2"></i>{{ $stats['total_children'] }} Santri
                            </span>
                            <span class="px-4 py-2 bg-white bg-opacity-20 backdrop-blur-sm rounded-lg">
                                <i class="fas fa-{{ $wali->user->status === 'active' ? 'check-circle text-green-300' : 'ban text-red-300' }} mr-2"></i>{{ ucfirst($wali->user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Children -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Santri</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_children'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 text-xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Total Hafalan -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Hafalan Terverifikasi</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_hafalan'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl">
                        <i class="fas fa-book"></i>
                    </div>
                </div>
            </div>

            <!-- Total Donations -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Total Donasi</p>
                        <p class="text-2xl font-bold text-gray-900 mt-2">Rp{{ number_format($stats['total_donations'], 0, ',', '.') }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center text-green-600 text-xl">
                        <i class="fas fa-heart"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Donations -->
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">Donasi Pending</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['pending_donations'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 text-xl">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <i class="fas fa-user-circle text-purple-600 mr-3"></i>Informasi Pribadi
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Lengkap</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->user->name }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Hubungan dengan Santri</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ ucfirst($wali->relation) }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Email</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->user->email ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Nomor HP</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->user->phone ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">NIK</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->nik ?? 'Belum diisi' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Pekerjaan</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->occupation ?? 'Belum diisi' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="text-sm font-medium text-gray-600">Alamat</label>
                    <p class="text-lg font-semibold text-gray-900 mt-1">{{ $wali->address ?? 'Belum diisi' }}</p>
                </div>
            </div>
        </div>

        <!-- Children Section -->
        @if($stats['total_children'] > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-list text-purple-600 mr-3"></i>Daftar Santri ({{ $stats['total_children'] }})
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    @foreach($childrenStats as $child)
                        <div class="border border-purple-200 rounded-xl p-5 hover:shadow-lg transition">
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900">{{ $child['santri']->user->name }}</h4>
                                    <p class="text-sm text-gray-600 mt-1">
                                        <i class="fas fa-id-card mr-1"></i>NIS: {{ $child['santri']->nis }}
                                    </p>
                                </div>
                                <a href="{{ route('users.santri.show', $child['santri']->id) }}"
                                    class="text-purple-600 hover:text-purple-700 text-xl">
                                    <i class="fas fa-arrow-right"></i>
                                </a>
                            </div>

                            <div class="grid grid-cols-3 gap-2 mt-4">
                                <div class="bg-purple-50 rounded-lg p-3 text-center">
                                    <p class="text-2xl font-bold text-purple-600">{{ $child['total_hafalan'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Total Hafalan</p>
                                </div>
                                <div class="bg-blue-50 rounded-lg p-3 text-center">
                                    <p class="text-2xl font-bold text-blue-600">{{ $child['verified_hafalan'] }}</p>
                                    <p class="text-xs text-gray-600 mt-1">Terverifikasi</p>
                                </div>
                                <div class="bg-green-50 rounded-lg p-3 text-center">
                                    <p class="text-2xl font-bold text-green-600">{{ $child['progress_percentage'] }}%</p>
                                    <p class="text-xs text-gray-600 mt-1">Progress</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Donations -->
        @if($wali->appreciationFunds->count() > 0)
            <div class="bg-white rounded-xl shadow-md p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-gift text-green-600 mr-3"></i>10 Donasi Terakhir
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Jumlah</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                                <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wali->appreciationFunds as $fund)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $fund->created_at->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-sm font-semibold text-gray-900">Rp{{ number_format($fund->amount, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ $fund->description ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm">
                                        @if($fund->status === 'verified')
                                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Terverifikasi</span>
                                        @elseif($fund->status === 'pending')
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
