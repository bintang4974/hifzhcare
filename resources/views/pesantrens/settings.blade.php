@extends('layouts.app-enhanced')

@section('title', 'Pengaturan Pesantren')
@section('breadcrumb', 'Super Admin / Pesantren / Settings')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Pengaturan Pesantren</h1>
                <p class="text-gray-600 mt-1">{{ $pesantren->name }} ({{ $pesantren->code }})</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('superadmin.pesantrens.show', $pesantren->id) }}"
                    class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg shadow-md transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Settings Sections -->
        <form action="{{ route('superadmin.pesantrens.updateSettings', $pesantren->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-cog text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pengaturan Dasar</h3>
                        <p class="text-sm text-gray-600">Konfigurasi utama sistem</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Enable Registration -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="allow_registration" value="1"
                                {{ old('allow_registration', $pesantren->settings['allow_registration'] ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Izinkan Pendaftaran Santri Baru</span>
                                <p class="text-xs text-gray-600">Nonaktifkan jika pesantren sudah mencapai kapasitas
                                    maksimal</p>
                            </div>
                        </label>
                    </div>

                    <!-- Auto Approve Santri -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_approve_santri" value="1"
                                {{ old('auto_approve_santri', $pesantren->settings['auto_approve_santri'] ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Auto-Approve Santri Baru</span>
                                <p class="text-xs text-gray-600">Santri otomatis aktif tanpa perlu persetujuan admin</p>
                            </div>
                        </label>
                    </div>

                    <!-- Enable Public Profile -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="public_profile_enabled" value="1"
                                {{ old('public_profile_enabled', $pesantren->settings['public_profile_enabled'] ?? true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Aktifkan Profil Publik</span>
                                <p class="text-xs text-gray-600">Tampilkan informasi pesantren di halaman publik</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Hafalan Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pengaturan Hafalan</h3>
                        <p class="text-sm text-gray-600">Konfigurasi sistem hafalan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Minimum Ayat per Setoran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Minimal Ayat per Setoran
                        </label>
                        <input type="number" name="min_ayat_per_setoran"
                            value="{{ old('min_ayat_per_setoran', $pesantren->settings['min_ayat_per_setoran'] ?? 1) }}"
                            min="1" max="100"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <p class="text-xs text-gray-600 mt-1">Jumlah ayat minimum untuk satu setoran</p>
                    </div>

                    <!-- Maximum Ayat per Setoran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maksimal Ayat per Setoran
                        </label>
                        <input type="number" name="max_ayat_per_setoran"
                            value="{{ old('max_ayat_per_setoran', $pesantren->settings['max_ayat_per_setoran'] ?? 50) }}"
                            min="1" max="286"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                        <p class="text-xs text-gray-600 mt-1">Jumlah ayat maksimal untuk satu setoran</p>
                    </div>

                    <!-- Require Audio -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="require_audio_recording" value="1"
                                {{ old('require_audio_recording', $pesantren->settings['require_audio_recording'] ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Wajibkan Rekaman Audio</span>
                                <p class="text-xs text-gray-600">Santri harus upload rekaman audio setiap setoran</p>
                            </div>
                        </label>
                    </div>

                    <!-- Auto Verify -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_verify_hafalan" value="1"
                                {{ old('auto_verify_hafalan', $pesantren->settings['auto_verify_hafalan'] ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Auto-Verify Hafalan</span>
                                <p class="text-xs text-gray-600 text-red-500">Tidak direkomendasikan - hafalan otomatis
                                    terverifikasi tanpa review ustadz</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Certificate Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pengaturan Sertifikat</h3>
                        <p class="text-sm text-gray-600">Konfigurasi penerbitan sertifikat</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Minimum Progress for Certificate -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Progress Minimal untuk Sertifikat
                        </label>
                        <input type="number" name="min_progress_for_certificate"
                            value="{{ old('min_progress_for_certificate', $pesantren->settings['min_progress_for_certificate'] ?? 100) }}"
                            min="0" max="100"
                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <p class="text-xs text-gray-600 mt-1">Progress minimum (%) untuk mendapat sertifikat</p>
                    </div>

                    <!-- Certificate Prefix -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Prefix Nomor Sertifikat
                        </label>
                        <input type="text" name="certificate_prefix"
                            value="{{ old('certificate_prefix', $pesantren->settings['certificate_prefix'] ?? $pesantren->code) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                        <p class="text-xs text-gray-600 mt-1">Contoh: {{ $pesantren->code }}-CERT-2024-001</p>
                    </div>

                    <!-- Auto Issue Certificate -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="auto_issue_certificate" value="1"
                                {{ old('auto_issue_certificate', $pesantren->settings['auto_issue_certificate'] ?? false) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-yellow-600 focus:ring-yellow-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Terbitkan Sertifikat Otomatis</span>
                                <p class="text-xs text-gray-600">Sertifikat otomatis diterbitkan saat santri mencapai
                                    progress yang ditentukan</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-bell text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Pengaturan Notifikasi</h3>
                        <p class="text-sm text-gray-600">Konfigurasi notifikasi sistem</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <!-- Email Notifications -->
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_email_notifications" value="1"
                            {{ old('enable_email_notifications', $pesantren->settings['enable_email_notifications'] ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 mr-3">
                        <div>
                            <span class="font-medium text-gray-900">Notifikasi Email</span>
                            <p class="text-xs text-gray-600">Kirim notifikasi via email untuk event penting</p>
                        </div>
                    </label>

                    <!-- WhatsApp Notifications -->
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="enable_whatsapp_notifications" value="1"
                            {{ old('enable_whatsapp_notifications', $pesantren->settings['enable_whatsapp_notifications'] ?? false) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 mr-3">
                        <div>
                            <span class="font-medium text-gray-900">Notifikasi WhatsApp</span>
                            <p class="text-xs text-gray-600">Kirim notifikasi via WhatsApp (memerlukan integrasi)</p>
                        </div>
                    </label>

                    <!-- Notify Wali on Hafalan Verified -->
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="notify_wali_on_verification" value="1"
                            {{ old('notify_wali_on_verification', $pesantren->settings['notify_wali_on_verification'] ?? true) ? 'checked' : '' }}
                            class="rounded border-gray-300 text-green-600 focus:ring-green-500 mr-3">
                        <div>
                            <span class="font-medium text-gray-900">Notifikasi Wali saat Hafalan Diverifikasi</span>
                            <p class="text-xs text-gray-600">Wali mendapat notifikasi ketika hafalan anaknya diverifikasi
                                ustadz</p>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Warning Box -->
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-800">
                            <strong>Perhatian:</strong> Perubahan pengaturan akan berlaku segera dan mempengaruhi semua
                            pengguna di pesantren ini.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('superadmin.pesantrens.show', $pesantren->id) }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                </button>
            </div>
        </form>
    </div>
@endsection
