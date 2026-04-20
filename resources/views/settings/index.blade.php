@extends('layouts.app-enhanced')

@section('title', 'Pengaturan')
@section('breadcrumb', 'Pengaturan')

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pengaturan</h1>
                <p class="text-gray-600 mt-1">Kelola preferensi dan konfigurasi akun Anda</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Sidebar Menu -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <nav class="space-y-2">
                    <a href="#general" onclick="showTab('general')"
                        class="tab-link active flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-sliders-h w-5 mr-3 text-blue-600"></i>
                        <span class="font-medium">Umum</span>
                    </a>
                    <a href="#appearance" onclick="showTab('appearance')"
                        class="tab-link flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-palette w-5 mr-3 text-purple-600"></i>
                        <span class="font-medium">Tampilan</span>
                    </a>
                    <a href="#notifications" onclick="showTab('notifications')"
                        class="tab-link flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-bell w-5 mr-3 text-yellow-600"></i>
                        <span class="font-medium">Notifikasi</span>
                    </a>
                    <a href="#privacy" onclick="showTab('privacy')"
                        class="tab-link flex items-center p-3 rounded-lg hover:bg-gray-50 transition">
                        <i class="fas fa-shield-alt w-5 mr-3 text-green-600"></i>
                        <span class="font-medium">Privasi</span>
                    </a>
                </nav>
            </div>

            <!-- Settings Content -->
            <div class="lg:col-span-2 space-y-6">

                <!-- General Settings -->
                <div id="general-tab" class="tab-content bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Pengaturan Umum</h2>

                    <form method="POST" action="{{ route('settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            <!-- Language -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Bahasa</label>
                                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                                    <option value="id">Bahasa Indonesia</option>
                                    <option value="en">English</option>
                                    <option value="ar">العربية (Arabic)</option>
                                </select>
                            </div>

                            <!-- Timezone -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Zona Waktu</label>
                                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                                    <option value="Asia/Jakarta">WIB (Jakarta)</option>
                                    <option value="Asia/Makassar">WITA (Makassar)</option>
                                    <option value="Asia/Jayapura">WIT (Jayapura)</option>
                                </select>
                            </div>

                            <!-- Date Format -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Format Tanggal</label>
                                <select class="w-full rounded-lg border-gray-300 focus:border-blue-500">
                                    <option value="d/m/Y">DD/MM/YYYY</option>
                                    <option value="m/d/Y">MM/DD/YYYY</option>
                                    <option value="Y-m-d">YYYY-MM-DD</option>
                                </select>
                            </div>

                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Appearance Settings -->
                <div id="appearance-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Tampilan</h2>

                    <div class="space-y-6">
                        <!-- Theme -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Tema</label>
                            <div class="grid grid-cols-3 gap-4">
                                <label class="cursor-pointer">
                                    <input type="radio" name="theme" value="light" class="sr-only peer">
                                    <div
                                        class="p-4 border-2 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <i class="fas fa-sun text-2xl text-yellow-500 mb-2"></i>
                                        <p class="text-sm font-medium">Terang</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="theme" value="dark" class="sr-only peer">
                                    <div
                                        class="p-4 border-2 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <i class="fas fa-moon text-2xl text-blue-600 mb-2"></i>
                                        <p class="text-sm font-medium">Gelap</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="theme" value="auto" class="sr-only peer" checked>
                                    <div
                                        class="p-4 border-2 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50">
                                        <i class="fas fa-adjust text-2xl text-gray-600 mb-2"></i>
                                        <p class="text-sm font-medium">Otomatis</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Font Size -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ukuran Font</label>
                            <select class="w-full rounded-lg border-gray-300">
                                <option value="small">Kecil</option>
                                <option value="medium" selected>Sedang</option>
                                <option value="large">Besar</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div id="notifications-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Notifikasi</h2>

                    <div class="space-y-4">
                        <!-- Email Notifications -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Notifikasi Email</h4>
                                <p class="text-sm text-gray-600">Terima pembaruan melalui email</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>

                        <!-- Hafalan Verified -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Hafalan Diverifikasi</h4>
                                <p class="text-sm text-gray-600">Notifikasi saat hafalan diverifikasi ustadz</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>

                        <!-- Certificate Issued -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Sertifikat Diterbitkan</h4>
                                <p class="text-sm text-gray-600">Notifikasi saat sertifikat dibuat</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div id="privacy-tab" class="tab-content hidden bg-white rounded-2xl shadow-lg p-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Privasi & Keamanan</h2>

                    <div class="space-y-4">
                        <!-- Profile Visibility -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Profil Publik</h4>
                                <p class="text-sm text-gray-600">Tampilkan profil di daftar santri/ustadz</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>

                        <!-- Show Progress -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <h4 class="font-semibold text-gray-900">Tampilkan Progress</h4>
                                <p class="text-sm text-gray-600">Izinkan orang lain melihat progress hafalan</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer" checked>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600">
                                </div>
                            </label>
                        </div>

                        <!-- Data Download -->
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <h4 class="font-semibold text-gray-900 mb-2">Download Data Anda</h4>
                            <p class="text-sm text-gray-600 mb-3">Unduh salinan semua data Anda</p>
                            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                <i class="fas fa-download mr-2"></i>Download Data
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showTab(tabName) {
                // Hide all tabs
                document.querySelectorAll('.tab-content').forEach(tab => {
                    tab.classList.add('hidden');
                });

                // Remove active class from all links
                document.querySelectorAll('.tab-link').forEach(link => {
                    link.classList.remove('bg-blue-50', 'active');
                });

                // Show selected tab
                document.getElementById(tabName + '-tab').classList.remove('hidden');

                // Add active class to clicked link
                event.target.closest('.tab-link').classList.add('bg-blue-50', 'active');
            }
        </script>
    @endpush
@endsection
