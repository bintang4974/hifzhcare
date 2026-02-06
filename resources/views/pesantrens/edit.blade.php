@extends('layouts.app-enhanced')

@section('title', 'Edit Pesantren')
@section('breadcrumb', 'Super Admin / Pesantren / Edit')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Pesantren</h1>
                <p class="text-gray-600 mt-1">Update data pesantren {{ $pesantren->name }}</p>
            </div>
            <a href="{{ route('superadmin.pesantrens') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('superadmin.pesantrens.update', $pesantren->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Informasi Dasar -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-mosque text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Informasi Dasar</h3>
                        <p class="text-sm text-gray-600">Data utama pesantren</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Pesantren -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1 text-purple-600"></i>
                            Nama Pesantren <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $pesantren->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Pesantren Tahfidz Al-Quran Al-Ikhlas">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Pesantren -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-code mr-1 text-purple-600"></i>
                            Kode Pesantren <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" value="{{ old('code', $pesantren->code) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('code') border-red-500 @enderror"
                            placeholder="Contoh: PTQI-001" onblur="this.value = this.value.toUpperCase()">
                        <p class="mt-1 text-xs text-gray-500">Kode unik untuk pesantren (huruf besar)</p>
                        @error('code')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-purple-600"></i>
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('status') border-red-500 @enderror">
                            <option value="">Pilih Status</option>
                            <option value="pending" {{ old('status', $pesantren->status) == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="active" {{ old('status', $pesantren->status) == 'active' ? 'selected' : '' }}>
                                Aktif</option>
                            <option value="inactive"
                                {{ old('status', $pesantren->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Max Santri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-users mr-1 text-purple-600"></i>
                            Kapasitas Maksimal Santri
                        </label>
                        <input type="number" name="max_santri" value="{{ old('max_santri', $pesantren->max_santri) }}"
                            min="0"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('max_santri') border-red-500 @enderror"
                            placeholder="Contoh: 500">
                        <p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak ada batasan</p>
                        @error('max_santri')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tahun Berdiri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-purple-600"></i>
                            Tahun Berdiri
                        </label>
                        <input type="number" name="established_year"
                            value="{{ old('established_year', $pesantren->established_year) }}" min="1900"
                            max="{{ date('Y') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('established_year') border-red-500 @enderror"
                            placeholder="Contoh: 2010">
                        @error('established_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-purple-600"></i>
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" rows="3" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap pesantren">{{ old('address', $pesantren->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-1 text-purple-600"></i>
                            Deskripsi Pesantren
                        </label>
                        <textarea name="description" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('description') border-red-500 @enderror"
                            placeholder="Deskripsi singkat tentang pesantren, visi misi, program unggulan, dll">{{ old('description', $pesantren->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Kontak -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-phone text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Informasi Kontak</h3>
                        <p class="text-sm text-gray-600">Data kontak pesantren</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nomor Telepon -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-blue-600"></i>
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $pesantren->phone) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('phone') border-red-500 @enderror"
                            placeholder="Contoh: (021) 12345678">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-blue-600"></i>
                            Email
                        </label>
                        <input type="email" name="email" value="{{ old('email', $pesantren->email) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror"
                            placeholder="pesantren@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Website -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-globe mr-1 text-blue-600"></i>
                            Website
                        </label>
                        <input type="url" name="website" value="{{ old('website', $pesantren->website) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('website') border-red-500 @enderror"
                            placeholder="https://pesantren.com">
                        @error('website')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fab fa-whatsapp mr-1 text-blue-600"></i>
                            WhatsApp
                        </label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp', $pesantren->whatsapp) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('whatsapp') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('whatsapp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Stats Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Statistik Saat Ini:</strong>
                        </p>
                        <p class="text-xs text-blue-700 mt-1">
                            Santri: {{ $pesantren->santri_profiles_count ?? 0 }} •
                            Ustadz: {{ $pesantren->ustadz_profiles_count ?? 0 }} •
                            Admin: {{ $pesantren->users_count ?? 0 }} •
                            Dibuat: {{ $pesantren->created_at->format('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('superadmin.pesantrens') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Update Pesantren
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-format code to uppercase
        document.querySelector('input[name="code"]')?.addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z0-9-]/g, '');
        });

        // Auto-format phone numbers
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^0-9()\s-]/g, '');
            });
        });
    </script>
@endpush
