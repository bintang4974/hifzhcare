@extends('layouts.app-enhanced')

@section('title', 'Edit Wali')
@section('breadcrumb', 'Pengguna / Wali / Edit')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Data Wali</h1>
                <p class="text-gray-600 mt-1">Update data wali santri</p>
            </div>
            <a href="{{ route('users.wali.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('users.wali.update', $wali->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Data Wali Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Data Wali</h3>
                        <p class="text-sm text-gray-600">Informasi orang tua/wali santri</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-purple-600"></i>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $wali->user->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap wali">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-purple-600"></i>
                            NIK (Opsional)
                        </label>
                        <input type="text" name="nik" value="{{ old('nik', $wali->nik) }}" maxlength="16"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('nik') border-red-500 @enderror"
                            placeholder="16 digit NIK">
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hubungan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heart mr-1 text-purple-600"></i>
                            Hubungan dengan Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="relation" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('relation') border-red-500 @enderror">
                            <option value="">Pilih Hubungan</option>
                            <option value="ayah" {{ old('relation', $wali->relation) == 'ayah' ? 'selected' : '' }}>Ayah
                            </option>
                            <option value="ibu" {{ old('relation', $wali->relation) == 'ibu' ? 'selected' : '' }}>Ibu
                            </option>
                            <option value="wali" {{ old('relation', $wali->relation) == 'wali' ? 'selected' : '' }}>Wali
                            </option>
                        </select>
                        @error('relation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-purple-600"></i>
                            Email (Opsional)
                        </label>
                        <input type="email" name="email" value="{{ old('email', $wali->user->email) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('email') border-red-500 @enderror"
                            placeholder="wali@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-purple-600"></i>
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $wali->user->phone) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-briefcase mr-1 text-purple-600"></i>
                            Pekerjaan (Opsional)
                        </label>
                        <input type="text" name="occupation" value="{{ old('occupation', $wali->occupation) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('occupation') border-red-500 @enderror"
                            placeholder="Contoh: Guru, Wiraswasta">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-purple-600"></i>
                            Alamat Lengkap (Opsional)
                        </label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address', $wali->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Current Info -->
            <div class="bg-purple-50 border-l-4 border-purple-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-purple-800">
                            <strong>Informasi:</strong>
                        </p>
                        <p class="text-xs text-purple-700 mt-1">
                            Jumlah Anak: {{ $wali->santris->count() }} santri •
                            Total Donasi: Rp
                            {{ number_format($wali->donations()->where('status', 'verified')->sum('amount'), 0, ',', '.') }}
                        </p>
                        @if ($wali->santris->count() > 0)
                            <div class="mt-2">
                                <p class="text-xs font-semibold text-purple-800">Anak-anak:</p>
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($wali->santris as $santri)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs rounded">
                                            {{ $santri->user->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('users.wali.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Update Data Wali
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-format NIK (16 digits)
        document.querySelector('input[name="nik"]')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '').substring(0, 16);
        });

        // Auto-format phone numbers
        document.querySelectorAll('input[type="tel"]').forEach(input => {
            input.addEventListener('input', function(e) {
                this.value = this.value.replace(/\D/g, '');
            });
        });
    </script>
@endpush
