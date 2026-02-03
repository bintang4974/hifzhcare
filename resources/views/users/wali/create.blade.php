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

        <form action="{{ route('users.wali.store') }}" method="POST" class="space-y-6">
            @csrf

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
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="Masukkan nama lengkap wali">
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">NIK (Opsional)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="16 digit NIK">
                    </div>

                    <!-- Hubungan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Hubungan dengan Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="relation" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200">
                            <option value="">Pilih Hubungan</option>
                            <option value="ayah">Ayah</option>
                            <option value="ibu">Ibu</option>
                            <option value="wali">Wali</option>
                        </select>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email (Opsional)</label>
                        <input type="email" name="email" value="{{ old('email') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="wali@example.com">
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="08123456789">
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan (Opsional)</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="Contoh: Guru, Wiraswasta">
                    </div>

                    <!-- Alamat -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Opsional)</label>
                        <textarea name="address" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200"
                            placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
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
