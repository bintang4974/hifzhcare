@extends('layouts.app-enhanced')

@section('title', 'Profil Saya')
@section('breadcrumb', 'Profil')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Profil Saya</h1>
            <p class="text-gray-600 mt-2">Kelola informasi profil dan akun Anda</p>
        </div>

        <!-- Profile Header Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
            <!-- Cover Image -->
            <div class="h-40 bg-gradient-to-r from-blue-500 via-purple-500 to-pink-500"></div>

            <!-- Profile Content -->
            <div class="px-6 sm:px-8 pb-8">
                <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-6 -mt-20 mb-8">
                    <!-- Avatar & Basic Info -->
                    <div class="flex flex-col sm:flex-row sm:items-end gap-6">
                        <!-- Avatar -->
                        <div class="relative">
                            <div class="w-24 h-24 sm:w-32 sm:h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-5xl font-bold shadow-lg border-4 border-white">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <button onclick="document.getElementById('avatarInput').click()"
                                class="absolute bottom-0 right-0 w-9 h-9 bg-blue-600 rounded-full flex items-center justify-center text-white hover:bg-blue-700 transition shadow-lg border-2 border-white">
                                <i class="fas fa-camera text-sm"></i>
                            </button>
                            <input type="file" id="avatarInput" class="hidden" accept="image/*">
                        </div>

                        <!-- User Info -->
                        <div class="flex-1 pb-2">
                            <h2 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ auth()->user()->name }}</h2>
                            <p class="text-gray-600 text-sm sm:text-base mt-1">{{ auth()->user()->email }}</p>
                            
                            <!-- Badges -->
                            <div class="flex flex-wrap gap-2 mt-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs sm:text-sm font-semibold rounded-full">
                                    <i class="fas fa-user-shield mr-1"></i>{{ ucfirst(auth()->user()->user_type) }}
                                </span>
                                @if (auth()->user()->pesantren)
                                    <span class="px-3 py-1 bg-green-100 text-green-700 text-xs sm:text-sm font-semibold rounded-full">
                                        <i class="fas fa-building mr-1"></i>{{ auth()->user()->pesantren->name }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Button -->
                    <a href="{{ route('profile.edit') }}"
                        class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg shadow-sm transition inline-flex items-center justify-center gap-2">
                        <i class="fas fa-edit"></i>Edit Profil
                    </a>
                </div>
            </div>
        </div>

        <!-- Two Column Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Left Column - Personal Information (spans 2 cols on large screens) -->
            <div class="lg:col-span-2">
                <!-- Personal Information Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mb-8">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900">
                            <i class="fas fa-user text-blue-600 mr-2"></i>Informasi Personal
                        </h3>
                        <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:text-blue-700 text-sm font-semibold flex items-center gap-1">
                            Edit <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>

                    <div class="space-y-4">
                        <!-- Name -->
                        <div class="flex flex-col sm:flex-row sm:items-center py-4 border-b border-gray-100 last:border-b-0">
                            <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">Nama Lengkap</div>
                            <div class="flex-1 text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->name }}</div>
                        </div>

                        <!-- Email -->
                        <div class="flex flex-col sm:flex-row sm:items-start py-4 border-b border-gray-100 last:border-b-0">
                            <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">Email</div>
                            <div class="flex-1">
                                <div class="text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->email }}</div>
                                @if (auth()->user()->email_verified_at)
                                    <span class="text-xs text-green-600 mt-1 inline-flex items-center gap-1">
                                        <i class="fas fa-check-circle"></i>Terverifikasi
                                    </span>
                                @else
                                    <span class="text-xs text-red-600 mt-1 inline-flex items-center gap-1">
                                        <i class="fas fa-exclamation-circle"></i>Belum diverifikasi
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex flex-col sm:flex-row sm:items-center py-4 border-b border-gray-100 last:border-b-0">
                            <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">No. Telepon</div>
                            <div class="flex-1 text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->phone ?? '-' }}</div>
                        </div>

                        <!-- User Type -->
                        <div class="flex flex-col sm:flex-row sm:items-center py-4 border-b border-gray-100 last:border-b-0">
                            <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">Tipe Pengguna</div>
                            <div class="flex-1 text-sm sm:text-base font-semibold text-gray-900">{{ ucfirst(auth()->user()->user_type) }}</div>
                        </div>

                        @if (auth()->user()->pesantren)
                            <!-- Pesantren -->
                            <div class="flex flex-col sm:flex-row sm:items-center py-4 border-b border-gray-100 last:border-b-0">
                                <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">Pesantren</div>
                                <div class="flex-1 text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->pesantren->name }}</div>
                            </div>
                        @endif

                        <!-- Member Since -->
                        <div class="flex flex-col sm:flex-row sm:items-center py-4">
                            <div class="w-full sm:w-32 text-sm font-medium text-gray-500 mb-1 sm:mb-0">Bergabung Sejak</div>
                            <div class="flex-1 text-sm sm:text-base font-semibold text-gray-900">{{ auth()->user()->created_at->format('d F Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Security & Settings (1 col on large screens) -->
            <div class="space-y-6">

                <!-- Security Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-shield-alt text-green-600 mr-2"></i>Keamanan
                    </h3>

                    <div class="space-y-3">
                        <!-- Change Password -->
                        <a href="{{ route('profile.change-password') }}"
                            class="flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition group">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-key text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">Ubah Password</div>
                                    <div class="text-xs text-gray-500">Perbarui password Anda</div>
                                </div>
                            </div>
                            <i class="fas fa-chevron-right text-gray-400 group-hover:text-gray-600"></i>
                        </a>

                        <!-- Last Password Change -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Password terakhir diubah</div>
                            <div class="text-sm font-semibold text-gray-900">
                                {{ auth()->user()->updated_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b border-gray-200">
                        <i class="fas fa-cog text-purple-600 mr-2"></i>Pengaturan
                    </h3>

                    <div class="space-y-2">
                        <a href="{{ route('settings.index') }}"
                            class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition group">
                            <i class="fas fa-sliders-h text-gray-400 group-hover:text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Preferensi</span>
                        </a>

                        @if (auth()->user()->user_type === 'admin' || auth()->user()->user_type === 'super_admin')
                            <a href="{{ route('settings.notifications') }}"
                                class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition group">
                                <i class="fas fa-bell text-gray-400 group-hover:text-blue-600 mr-3"></i>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Notifikasi</span>
                            </a>
                        @endif

                        <a href="{{ route('profile.change-password') }}"
                            class="flex items-center p-3 hover:bg-gray-50 rounded-lg transition group">
                            <i class="fas fa-user text-gray-400 group-hover:text-blue-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900">Profil</span>
                        </a>

                        <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                            class="w-full flex items-center p-3 hover:bg-red-50 rounded-lg transition group text-left">
                            <i class="fas fa-sign-out-alt text-gray-400 group-hover:text-red-600 mr-3"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-red-600">Keluar</span>
                        </button>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>

            </div>
        </div>

        <!-- Activity Log -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sm:p-8 mt-8">
            <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-6 pb-4 border-b border-gray-200">
                <i class="fas fa-history text-gray-600 mr-2"></i>Aktivitas Terakhir
            </h3>

            <div class="space-y-3">
                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-sign-in-alt text-green-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-900">Login ke sistem</div>
                        <div class="text-xs text-gray-500">{{ now()->subMinutes(5)->diffForHumans() }}</div>
                    </div>
                </div>

                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-user-edit text-blue-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-900">Memperbarui profil</div>
                        <div class="text-xs text-gray-500">{{ now()->subHours(2)->diffForHumans() }}</div>
                    </div>
                </div>

                <div class="flex items-start p-4 bg-gray-50 rounded-lg">
                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                        <i class="fas fa-key text-yellow-600"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-semibold text-gray-900">Mengubah password</div>
                        <div class="text-xs text-gray-500">{{ now()->subDays(3)->diffForHumans() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('avatarInput')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Here you would typically upload the file via AJAX
                alert('Fitur upload avatar akan segera tersedia!');
            }
        });
    </script>
@endpush
