@extends('layouts.app-enhanced')

@section('title', 'Tambah Ustadz')
@section('breadcrumb', 'Pengguna / Ustadz / Tambah')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Ustadz Baru</h1>
                <p class="text-gray-600 mt-1">Lengkapi data ustadz pengajar</p>
            </div>
            <a href="{{ route('users.ustadz.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('users.ustadz.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Data Ustadz Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Data Ustadz</h3>
                        <p class="text-sm text-gray-600">Informasi pribadi ustadz</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-green-600"></i>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap ustadz">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-green-600"></i>
                            NIP (Nomor Induk Pengajar) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nip" value="{{ old('nip') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('nip') border-red-500 @enderror"
                            placeholder="Contoh: UST001">
                        @error('nip')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Bergabung -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-green-600"></i>
                            Tanggal Bergabung <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="join_date" value="{{ old('join_date', date('Y-m-d')) }}" required
                            max="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('join_date') border-red-500 @enderror">
                        @error('join_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Spesialisasi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-1 text-green-600"></i>
                            Spesialisasi/Keahlian (Opsional)
                        </label>
                        <input type="text" name="specialization" value="{{ old('specialization') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('specialization') border-red-500 @enderror"
                            placeholder="Contoh: Tahfidz, Tajwid, Tahsin">
                        @error('specialization')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-green-600"></i>
                            Email (Opsional)
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('email') border-red-500 @enderror"
                            placeholder="ustadz@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-green-600"></i>
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-green-600"></i>
                            Alamat Lengkap (Opsional)
                        </label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Password Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Informasi:</strong> Password akan di-generate otomatis dan dikirimkan kepada ustadz via
                            email/SMS.
                            Ustadz dapat mengubah password setelah login pertama kali.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('users.ustadz.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Simpan Ustadz
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-format phone numbers
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    </script>
@endpush
