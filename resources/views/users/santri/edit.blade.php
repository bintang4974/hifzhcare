@extends('layouts.app-enhanced')

@section('title', 'Edit Santri')
@section('breadcrumb', 'Pengguna / Santri / Edit')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Data Santri</h1>
                <p class="text-gray-600 mt-1">Update data santri dan wali</p>
            </div>
            <a href="{{ route('users.santri.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('users.santri.update', $santri->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Data Santri Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Data Santri</h3>
                        <p class="text-sm text-gray-600">Informasi pribadi santri</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-blue-600"></i>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name', $santri->user->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap santri">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIS -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-id-card mr-1 text-blue-600"></i>
                            NIS (Nomor Induk Santri) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nis" value="{{ old('nis', $santri->nis) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('nis') border-red-500 @enderror"
                            placeholder="Contoh: SNT001">
                        @error('nis')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-venus-mars mr-1 text-blue-600"></i>
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select name="gender" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('gender') border-red-500 @enderror">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="L" {{ old('gender', $santri->gender) == 'L' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="P" {{ old('gender', $santri->gender) == 'P' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt mr-1 text-blue-600"></i>
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="birth_date"
                            value="{{ old('birth_date', $santri->birth_date->format('Y-m-d')) }}" required
                            max="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('birth_date') border-red-500 @enderror">
                        @error('birth_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Masuk -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-door-open mr-1 text-blue-600"></i>
                            Tanggal Masuk <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="entry_date"
                            value="{{ old('entry_date', $santri->entry_date->format('Y-m-d')) }}" required
                            max="{{ date('Y-m-d') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('entry_date') border-red-500 @enderror">
                        @error('entry_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-blue-600"></i>
                            Email (Opsional)
                        </label>
                        <input type="email" name="email" value="{{ old('email', $santri->user->email) }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('email') border-red-500 @enderror"
                            placeholder="santri@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-blue-600"></i>
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone', $santri->user->phone) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt mr-1 text-blue-600"></i>
                            Alamat Lengkap <span class="text-red-500">*</span>
                        </label>
                        <textarea name="address" rows="3" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address', $santri->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Wali Section -->
            <div class="bg-white rounded-xl shadow-lg p-6" x-data="{ changeWali: false }">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center text-white mr-4">
                            <i class="fas fa-user-friends text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Data Wali</h3>
                            <p class="text-sm text-gray-600">Informasi orang tua/wali santri</p>
                        </div>
                    </div>
                </div>

                <!-- Current Wali Info -->
                @if ($santri->wali)
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600">Wali Saat Ini:</p>
                                <p class="font-bold text-gray-900">{{ $santri->wali->user->name }}</p>
                                <p class="text-sm text-gray-600">{{ $santri->wali->user->phone }} •
                                    {{ ucfirst($santri->wali->relation) }}</p>
                            </div>
                            <button type="button" @click="changeWali = !changeWali"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                <i class="fas fa-exchange-alt mr-2"></i><span
                                    x-text="changeWali ? 'Batal' : 'Ganti Wali'"></span>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Change Wali -->
                <div x-show="changeWali || {{ $santri->wali ? 'false' : 'true' }}" x-cloak>
                    <div class="mb-4">
                        <label class="flex items-center cursor-pointer mb-4">
                            <input type="checkbox" x-model="useExisting"
                                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                            <span class="text-sm text-gray-700">Gunakan wali yang sudah ada</span>
                        </label>
                    </div>

                    <!-- Use Existing Wali -->
                    <div x-show="useExisting" x-cloak>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Wali</label>
                            <select name="wali_id"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200">
                                <option value="">Pilih Wali yang Sudah Ada</option>
                                @foreach ($walis as $wali)
                                    <option value="{{ $wali->id }}"
                                        {{ $santri->wali_id == $wali->id ? 'selected' : '' }}>
                                        {{ $wali->user->name }} - {{ $wali->user->phone }}
                                        ({{ ucfirst($wali->relation) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- New Wali Form -->
                    <div x-show="!useExisting && (changeWali || {{ $santri->wali ? 'false' : 'true' }})">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Wali -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-user mr-1 text-green-600"></i>
                                    Nama Wali
                                </label>
                                <input type="text" name="wali_name" value="{{ old('wali_name') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="Masukkan nama wali">
                            </div>

                            <!-- Hubungan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heart mr-1 text-green-600"></i>
                                    Hubungan
                                </label>
                                <select name="wali_relation"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200">
                                    <option value="">Pilih Hubungan</option>
                                    <option value="ayah">Ayah</option>
                                    <option value="ibu">Ibu</option>
                                    <option value="wali">Wali</option>
                                </select>
                            </div>

                            <!-- NIK Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-id-card mr-1 text-green-600"></i>
                                    NIK (Opsional)
                                </label>
                                <input type="text" name="wali_nik" value="{{ old('wali_nik') }}" maxlength="16"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="16 digit NIK">
                            </div>

                            <!-- Email Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-envelope mr-1 text-green-600"></i>
                                    Email Wali (Opsional)
                                </label>
                                <input type="email" name="wali_email" value="{{ old('wali_email') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="wali@example.com">
                            </div>

                            <!-- HP Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-phone mr-1 text-green-600"></i>
                                    Nomor HP Wali
                                </label>
                                <input type="tel" name="wali_phone" value="{{ old('wali_phone') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="08123456789">
                            </div>

                            <!-- Pekerjaan Wali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-briefcase mr-1 text-green-600"></i>
                                    Pekerjaan (Opsional)
                                </label>
                                <input type="text" name="wali_occupation" value="{{ old('wali_occupation') }}"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="Contoh: Guru, Wiraswasta">
                            </div>

                            <!-- Alamat Wali -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-map-marker-alt mr-1 text-green-600"></i>
                                    Alamat Wali (Opsional)
                                </label>
                                <textarea name="wali_address" rows="2"
                                    class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                                    placeholder="Sama dengan alamat santri atau berbeda">{{ old('wali_address') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('users.santri.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Update Data Santri
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-format NIK (16 digits)
        document.querySelector('input[name="wali_nik"]')?.addEventListener('input', function(e) {
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
