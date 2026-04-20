@extends('layouts.app-enhanced')

@section('title', 'Pengaturan Notifikasi')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 flex items-center gap-3">
                <i class="fas fa-bell text-blue-600"></i>
                Pengaturan Notifikasi
            </h1>
            <p class="text-gray-600 mt-2">Atur preferensi notifikasi Anda</p>
        </div>

        <!-- Alert Messages -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <h3 class="font-semibold text-red-800 mb-2">Terjadi kesalahan:</h3>
                <ul class="list-disc list-inside text-sm text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-800">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <!-- Notification Settings Card -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-cogs text-gray-400 mr-2"></i>
                Preferensi Notifikasi
            </h2>

            <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Email Notifications -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>
                        Notifikasi Email
                    </h3>

                    <div class="space-y-4">
                        <!-- Hafalan Verification -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="email_hafalan" name="notification_email_hafalan"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    value="1" checked>
                            </div>
                            <label for="email_hafalan" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Hafalan Menunggu Verifikasi</p>
                                <p class="text-sm text-gray-600 mt-1">Terima notifikasi ketika ada hafalan santri menunggu
                                    verifikasi</p>
                            </label>
                        </div>

                        <!-- Class Updates -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="email_class" name="notification_email_class"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    value="1" checked>
                            </div>
                            <label for="email_class" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Pembaruan Kelas</p>
                                <p class="text-sm text-gray-600 mt-1">Terima notifikasi tentang perubahan kelas atau santri
                                </p>
                            </label>
                        </div>

                        <!-- Certificate Generation -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="email_certificate" name="notification_email_certificate"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    value="1" checked>
                            </div>
                            <label for="email_certificate" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Sertifikat Selesai</p>
                                <p class="text-sm text-gray-600 mt-1">Notifikasi ketika sertifikat selesai digenerate
                                </p>
                            </label>
                        </div>

                        <!-- Payment Notifications -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="email_payment" name="notification_email_payment"
                                    class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500"
                                    value="1" checked>
                            </div>
                            <label for="email_payment" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Pembayaran</p>
                                <p class="text-sm text-gray-600 mt-1">Terima notifikasi tentang pembayaran baru atau
                                    jatuh tempo</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- In-App Notifications -->
                <div class="border-b border-gray-200 pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-bell text-purple-600 mr-2"></i>
                        Notifikasi Di Aplikasi
                    </h3>

                    <div class="space-y-4">
                        <!-- Hafalan In-App -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="app_hafalan" name="notification_app_hafalan"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                    value="1" checked>
                            </div>
                            <label for="app_hafalan" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Hafalan</p>
                                <p class="text-sm text-gray-600 mt-1">Tampilkan notifikasi hafalan di aplikasi</p>
                            </label>
                        </div>

                        <!-- Class In-App -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="app_class" name="notification_app_class"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                    value="1" checked>
                            </div>
                            <label for="app_class" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Kelas</p>
                                <p class="text-sm text-gray-600 mt-1">Tampilkan notifikasi kelas di aplikasi</p>
                            </label>
                        </div>

                        <!-- System In-App -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="app_system" name="notification_app_system"
                                    class="w-4 h-4 text-purple-600 rounded border-gray-300 focus:ring-purple-500"
                                    value="1" checked>
                            </div>
                            <label for="app_system" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Sistem</p>
                                <p class="text-sm text-gray-600 mt-1">Tampilkan notifikasi sistem dan pembaruan</p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- SMS Notifications (Optional) -->
                <div class="pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-comments text-green-600 mr-2"></i>
                        Notifikasi SMS
                    </h3>

                    <div class="space-y-4">
                        <!-- SMS Urgent -->
                        <div class="flex items-start gap-4">
                            <div class="flex items-center h-6 mt-1">
                                <input type="checkbox" id="sms_urgent" name="notification_sms_urgent"
                                    class="w-4 h-4 text-green-600 rounded border-gray-300 focus:ring-green-500"
                                    value="1">
                            </div>
                            <label for="sms_urgent" class="flex-1 cursor-pointer">
                                <p class="font-medium text-gray-900">Notifikasi Penting Saja</p>
                                <p class="text-sm text-gray-600 mt-1">Hanya terima SMS untuk notifikasi yang sangat penting
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition-colors flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Pengaturan
                    </button>
                    <a href="{{ route('profile.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-6 rounded-lg transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
