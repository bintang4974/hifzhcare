@extends('layouts.app-enhanced')
@section('title', 'Tambah Kelas')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold">Tambah Kelas Baru</h1>
            <a href="{{ route('classes.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('classes.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-6 flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard text-blue-600"></i>
                    </div>
                    Informasi Kelas
                </h3>

                <div class="space-y-4">
                    <!-- Nama Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('name') border-red-500 @enderror"
                            placeholder="Contoh: Kelas A">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Kode Kelas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kode Kelas (Opsional)
                        </label>
                        <input type="text" name="code" value="{{ old('code') }}"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            placeholder="Contoh: KLS-A (kosongkan untuk auto-generate)">
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            placeholder="Deskripsi kelas (opsional)">{{ old('description') }}</textarea>
                    </div>

                    <!-- Kapasitas -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kapasitas Maksimal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="max_capacity" value="{{ old('max_capacity', 30) }}" required
                            min="1" max="100"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            placeholder="Contoh: 30">
                        @error('max_capacity')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status
                        </label>
                        <select name="status"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('classes.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition">
                    <i class="fas fa-save mr-2"></i>Simpan Kelas
                </button>
            </div>
        </form>
    </div>
@endsection
