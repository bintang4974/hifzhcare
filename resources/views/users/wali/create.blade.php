@extends('layouts.app-enhanced')
@section('title', 'Tambah Wali')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">Tambah Wali Baru</h1>
                <p class="text-gray-600 mt-1">Lengkapi data wali santri</p>
            </div>
            <a href="{{ route('users.wali.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Error Alert -->
        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Validasi gagal!</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Session Error Alert -->
        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('users.wali.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pesantren Selection (Super Admin Only) -->
            @if (auth()->user()->isSuperAdmin() && $pesantrens && $pesantrens->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Pilih Pesantren</h3>
                            <p class="text-sm text-gray-600">Tentukan pesantren tempat wali terdaftar</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mosque mr-1 text-blue-600"></i>
                            Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select name="pesantren_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('pesantren_id') border-red-500 @enderror">
                            <option value="">Pilih Pesantren</option>
                            @forelse ($pesantrens as $pesantren)
                                <option value="{{ $pesantren->id }}" {{ old('pesantren_id') == $pesantren->id ? 'selected' : '' }}>
                                    {{ $pesantren->name }} ({{ $pesantren->code }})
                                </option>
                            @empty
                                <option disabled>Tidak ada pesantren aktif</option>
                            @endforelse
                        </select>
                        @error('pesantren_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            <!-- Data Wali Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-user-friends text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">Data Wali</h3>
                        <p class="text-sm text-gray-600">Informasi orang tua/wali santri</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap wali">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK (Opsional)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('nik') border-red-500 @enderror"
                            placeholder="16 digit NIK">
                        @error('nik')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Hubungan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hubungan dengan Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="relation" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('relation') border-red-500 @enderror">
                            <option value="">Pilih Hubungan</option>
                            <option value="ayah" {{ old('relation') === 'ayah' ? 'selected' : '' }}>Ayah</option>
                            <option value="ibu" {{ old('relation') === 'ibu' ? 'selected' : '' }}>Ibu</option>
                            <option value="wali" {{ old('relation') === 'wali' ? 'selected' : '' }}>Wali</option>
                        </select>
                        @error('relation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('email') border-red-500 @enderror"
                            placeholder="wali@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan (Opsional)</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('occupation') border-red-500 @enderror"
                            placeholder="Contoh: Guru, Wiraswasta">
                        @error('occupation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Opsional)</label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('address') border-red-500 @enderror"
                            placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <a href="{{ route('users.wali.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">Batal</a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-semibold rounded-xl shadow-lg">
                    <i class="fas fa-save mr-2"></i>Simpan Wali
                </button>
            </div>
        </form>
    </div>
@endsection
