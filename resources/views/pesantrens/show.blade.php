@extends('layouts.app-enhanced')

@section('title', 'Detail Pesantren')
@section('breadcrumb', 'Super Admin / Pesantren / Detail')

@section('content')
    <div class="max-w-7xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $pesantren->name }}</h1>
                <p class="text-gray-600 mt-1">
                    <i class="fas fa-code mr-1"></i>{{ $pesantren->code }} •
                    Bergabung {{ $pesantren->created_at->format('d M Y') }}
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('superadmin.pesantrens') }}"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <a href="{{ route('superadmin.pesantrens.edit', $pesantren->id) }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
                <a href="{{ route('superadmin.pesantrens.settings', $pesantren->id) }}"
                    class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-cog mr-2"></i>Settings
                </a>
            </div>
        </div>

        <!-- Status & Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status Card -->
            <div
                class="bg-gradient-to-br {{ $pesantren->status === 'active' ? 'from-green-500 to-green-600' : ($pesantren->status === 'pending' ? 'from-yellow-500 to-yellow-600' : 'from-gray-500 to-gray-600') }} rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-white text-opacity-90 text-sm mb-1">Status</p>
                        <h3 class="text-2xl font-bold">{{ ucfirst($pesantren->status) }}</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i
                            class="fas fa-{{ $pesantren->status === 'active' ? 'check-circle' : ($pesantren->status === 'pending' ? 'clock' : 'ban') }} text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Santri Card -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_santri'] }}</h3>
                        @if ($pesantren->max_santri)
                            <p class="text-xs text-blue-100 mt-1">Dari {{ $pesantren->max_santri }} kapasitas</p>
                        @endif
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Ustadz Card -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Total Ustadz</p>
                        <h3 class="text-3xl font-bold">{{ $stats['total_ustadz'] }}</h3>
                        <p class="text-xs text-green-100 mt-1">{{ $stats['total_classes'] }} kelas</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Hafalan Card -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Hafalan Verified</p>
                        <h3 class="text-3xl font-bold">{{ number_format($stats['total_hafalan']) }}</h3>
                        <p class="text-xs text-purple-100 mt-1">{{ $stats['total_certificates'] }} sertifikat</p>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Contact Information -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-phone text-blue-600 mr-2"></i>
                    Informasi Kontak
                </h3>

                <div class="space-y-3">
                    <div class="flex items-start">
                        <i class="fas fa-phone text-gray-400 mt-1 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Telepon</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $pesantren->phone }}</p>
                        </div>
                    </div>

                    @if ($pesantren->email)
                        <div class="flex items-start">
                            <i class="fas fa-envelope text-gray-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500">Email</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $pesantren->email }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($pesantren->website)
                        <div class="flex items-start">
                            <i class="fas fa-globe text-gray-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500">Website</p>
                                <a href="{{ $pesantren->website }}" target="_blank"
                                    class="text-sm font-semibold text-blue-600 hover:underline">
                                    {{ $pesantren->website }}
                                </a>
                            </div>
                        </div>
                    @endif

                    @if ($pesantren->whatsapp)
                        <div class="flex items-start">
                            <i class="fab fa-whatsapp text-gray-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500">WhatsApp</p>
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $pesantren->whatsapp) }}"
                                    target="_blank" class="text-sm font-semibold text-green-600 hover:underline">
                                    {{ $pesantren->whatsapp }}
                                </a>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-gray-400 mt-1 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Alamat</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $pesantren->address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-purple-600 mr-2"></i>
                    Informasi Tambahan
                </h3>

                <div class="space-y-3">
                    @if ($pesantren->established_year)
                        <div class="flex items-start">
                            <i class="fas fa-calendar-alt text-gray-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500">Tahun Berdiri</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $pesantren->established_year }}</p>
                            </div>
                        </div>
                    @endif

                    @if ($pesantren->max_santri)
                        <div class="flex items-start">
                            <i class="fas fa-users text-gray-400 mt-1 mr-3"></i>
                            <div>
                                <p class="text-xs text-gray-500">Kapasitas</p>
                                <p class="text-sm font-semibold text-gray-900">{{ $pesantren->max_santri }} santri</p>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-blue-600 h-2 rounded-full"
                                        style="width: {{ min(100, ($stats['total_santri'] / $pesantren->max_santri) * 100) }}%">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-start">
                        <i class="fas fa-clock text-gray-400 mt-1 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Terdaftar</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $pesantren->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <i class="fas fa-edit text-gray-400 mt-1 mr-3"></i>
                        <div>
                            <p class="text-xs text-gray-500">Terakhir Update</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ $pesantren->updated_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-align-left text-green-600 mr-2"></i>
                    Deskripsi
                </h3>

                @if ($pesantren->description)
                    <p class="text-sm text-gray-700 leading-relaxed">{{ $pesantren->description }}</p>
                @else
                    <p class="text-sm text-gray-500 italic">Belum ada deskripsi</p>
                @endif
            </div>
        </div>

        <!-- Detailed Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Recent Santri -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center justify-between">
                    <span><i class="fas fa-user-graduate text-blue-600 mr-2"></i>Santri Terbaru</span>
                    <span class="text-sm font-normal text-gray-600">{{ $recentSantri->count() }} dari
                        {{ $stats['total_santri'] }}</span>
                </h3>

                <div class="space-y-2">
                    @forelse($recentSantri as $santri)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div
                                class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($santri->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $santri->user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $santri->nis }} •
                                    {{ $santri->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-blue-600">
                                    {{ number_format($santri->progress_percentage, 1) }}%</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada santri</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Ustadz -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center justify-between">
                    <span><i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>Ustadz Terbaru</span>
                    <span class="text-sm font-normal text-gray-600">{{ $recentUstadz->count() }} dari
                        {{ $stats['total_ustadz'] }}</span>
                </h3>

                <div class="space-y-2">
                    @forelse($recentUstadz as $ustadz)
                        <div class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                            <div
                                class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                {{ substr($ustadz->user->name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $ustadz->user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $ustadz->nip }} •
                                    {{ $ustadz->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-600">{{ $ustadz->assigned_classes_count }} kelas</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 py-8">Belum ada ustadz</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Certificates -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center justify-between">
                <span><i class="fas fa-certificate text-yellow-600 mr-2"></i>Sertifikat Terbaru</span>
                <span class="text-sm font-normal text-gray-600">{{ $recentCertificates->count() }} dari
                    {{ $stats['total_certificates'] }}</span>
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($recentCertificates as $cert)
                    <div class="p-4 bg-gradient-to-br from-yellow-50 to-orange-50 border border-yellow-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fas fa-award text-2xl text-yellow-600 mr-3"></i>
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $cert->santri->user->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $cert->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-700">{{ $cert->certificate_number }}</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-certificate text-6xl text-gray-300 mb-2"></i>
                        <p class="text-gray-500">Belum ada sertifikat</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
