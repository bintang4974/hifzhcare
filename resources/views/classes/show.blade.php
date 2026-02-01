@extends('layouts.app-enhanced')
@section('title', 'Detail Kelas')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ $class->name }}</h1>
                <p class="text-gray-600">Detail kelas dan anggota</p>
            </div>
            <div class="flex gap-3">
                @can('edit_classes')
                    <a href="{{ route('classes.edit', $class->id) }}"
                        class="px-6 py-3 bg-yellow-600 hover:bg-yellow-700 text-white font-semibold rounded-xl">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                @endcan
                <a href="{{ route('classes.index') }}"
                    class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Class Info Card -->
        <div class="bg-gradient-to-br from-blue-600 to-purple-700 rounded-2xl p-8 text-white shadow-2xl">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-blue-100 text-sm mb-1">Kode Kelas</p>
                    <h3 class="text-2xl font-bold">{{ $class->code }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-blue-100 text-sm mb-1">Kapasitas</p>
                    <h3 class="text-2xl font-bold">{{ $class->current_student_count }}/{{ $class->max_capacity }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-blue-100 text-sm mb-1">Total Ustadz</p>
                    <h3 class="text-2xl font-bold">{{ $stats['total_ustadz'] }}</h3>
                </div>
                <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-blue-100 text-sm mb-1">Status</p>
                    <h3 class="text-2xl font-bold">{{ ucfirst($class->status) }}</h3>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500">
                <p class="text-gray-600 text-sm">Total Hafalan</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['total_hafalan'] }}</h3>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
                <p class="text-gray-600 text-sm">Hafalan Verified</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['verified_hafalan'] }}</h3>
            </div>
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-yellow-500">
                <p class="text-gray-600 text-sm">Hafalan Pending</p>
                <h3 class="text-3xl font-bold text-gray-900">{{ $stats['pending_hafalan'] }}</h3>
            </div>
        </div>

        <!-- Members -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Ustadz List -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold">Ustadz Pengajar</h3>
                    @can('assign_ustadz')
                        <a href="{{ route('classes.members', $class->id) }}"
                            class="text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-edit mr-1"></i>Kelola
                        </a>
                    @endcan
                </div>
                <div class="space-y-3">
                    @forelse($class->activeUstadz as $ustadz)
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <h4 class="font-bold text-gray-900">{{ $ustadz->user->name }}</h4>
                            <p class="text-sm text-gray-600">{{ $ustadz->nip }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada ustadz</p>
                    @endforelse
                </div>
            </div>

            <!-- Santri List -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold">Santri Terdaftar ({{ $class->current_student_count }})</h3>
                    @can('enroll_santri')
                        <a href="{{ route('classes.members', $class->id) }}"
                            class="text-blue-600 hover:text-blue-700 font-medium">
                            <i class="fas fa-edit mr-1"></i>Kelola
                        </a>
                    @endcan
                </div>
                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($class->activeSantri as $santri)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $santri->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $santri->nis }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada santri</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
