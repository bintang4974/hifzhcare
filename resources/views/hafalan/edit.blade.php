@extends('layouts.app-enhanced')

@section('title', 'Edit Hafalan')
@section('breadcrumb', 'Hafalan / Edit')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Hafalan</h1>
                <p class="text-gray-600 mt-1">Update data setoran hafalan</p>
            </div>
            <a href="{{ route('hafalan.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('hafalan.update', $hafalan->id) }}" method="POST" enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Current Status Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                        <div>
                            <p class="text-sm text-blue-800">
                                <strong>Status Saat Ini:</strong>
                                @if ($hafalan->status === 'verified')
                                    <span
                                        class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Verified</span>
                                @elseif($hafalan->status === 'rejected')
                                    <span
                                        class="px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded">Rejected</span>
                                @else
                                    <span
                                        class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Pending</span>
                                @endif
                            </p>
                            <p class="text-xs text-blue-700 mt-1">
                                Dibuat: {{ $hafalan->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>
                    @if ($hafalan->verified_by_ustadz_id)
                        <div class="text-right">
                            <p class="text-xs text-blue-700">Diverifikasi oleh:</p>
                            <p class="text-sm font-semibold text-blue-900">
                                {{ $hafalan->verifiedByUstadz->user->name ?? '-' }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Hafalan Data Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-book-quran text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Data Hafalan</h3>
                        <p class="text-sm text-gray-600">Informasi setoran hafalan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Surah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-book mr-1 text-blue-600"></i>
                            Nama Surah <span class="text-red-500">*</span>
                        </label>
                        <select name="surah_name" id="surah-name" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('surah_name') border-red-500 @enderror">
                            <option value="">Pilih Surah</option>
                            @foreach ($surahs as $surah)
                                <option value="{{ $surah['name_latin'] }}" data-juz="{{ $surah['juz_start'] }}"
                                    {{ old('surah_name', $hafalan->surah_name) == $surah['name_latin'] ? 'selected' : '' }}>
                                    {{ $surah['number'] }}. {{ $surah['name_latin'] }} ({{ $surah['total_ayat'] }} ayat)
                                </option>
                            @endforeach
                        </select>
                        @error('surah_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Juz Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-bookmark mr-1 text-blue-600"></i>
                            Juz <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="juz_number" id="juz-number"
                            value="{{ old('juz_number', $hafalan->juz_number) }}" required min="1" max="30"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('juz_number') border-red-500 @enderror"
                            placeholder="1-30">
                        @error('juz_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ayat Start -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-play mr-1 text-blue-600"></i>
                            Ayat Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="ayat_start" value="{{ old('ayat_start', $hafalan->ayat_start) }}"
                            required min="1"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('ayat_start') border-red-500 @enderror"
                            placeholder="1">
                        @error('ayat_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ayat End -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-stop mr-1 text-blue-600"></i>
                            Ayat Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="ayat_end" value="{{ old('ayat_end', $hafalan->ayat_end) }}" required
                            min="1"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('ayat_end') border-red-500 @enderror"
                            placeholder="10">
                        @error('ayat_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-1 text-blue-600"></i>
                            Jenis Setoran <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('type') border-red-500 @enderror">
                            <option value="">Pilih Jenis</option>
                            <option value="setoran" {{ old('type', $hafalan->type) == 'setoran' ? 'selected' : '' }}>
                                Setoran (Hafalan Baru)</option>
                            <option value="muraja'ah" {{ old('type', $hafalan->type) == "muraja'ah" ? 'selected' : '' }}>
                                Muraja'ah (Mengulang)</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Method -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-microphone mr-1 text-blue-600"></i>
                            Metode Setoran <span class="text-red-500">*</span>
                        </label>
                        <select name="method" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('method') border-red-500 @enderror">
                            <option value="">Pilih Metode</option>
                            <option value="direct" {{ old('method', $hafalan->method) == 'direct' ? 'selected' : '' }}>
                                Langsung (Tatap Muka)</option>
                            <option value="recording"
                                {{ old('method', $hafalan->method) == 'recording' ? 'selected' : '' }}>Rekaman Audio
                            </option>
                        </select>
                        @error('method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Notes -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-comment mr-1 text-blue-600"></i>
                            Catatan (Opsional)
                        </label>
                        <textarea name="notes" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('notes') border-red-500 @enderror"
                            placeholder="Catatan tambahan tentang setoran ini">{{ old('notes', $hafalan->notes) }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Audio Recording Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-microphone text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Rekaman Audio (Opsional)</h3>
                        <p class="text-sm text-gray-600">Unggah rekaman baru atau gunakan yang sudah ada</p>
                    </div>
                </div>

                @if ($hafalan->audio_file_path)
                    <!-- Current Audio -->
                    <div class="mb-4 p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-sm font-semibold text-purple-900">Rekaman Saat Ini:</p>
                            <a href="{{ Storage::url($hafalan->audio_file_path) }}" target="_blank"
                                class="text-purple-600 hover:text-purple-700 text-sm">
                                <i class="fas fa-download mr-1"></i>Download
                            </a>
                        </div>
                        <audio controls class="w-full">
                            <source src="{{ Storage::url($hafalan->audio_file_path) }}" type="audio/mpeg">
                            Browser Anda tidak support audio player.
                        </audio>
                    </div>
                @endif

                <!-- New Audio Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Upload Rekaman Baru (Opsional)
                    </label>
                    <input type="file" name="audio_file" accept="audio/*"
                        class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('audio_file') border-red-500 @enderror">
                    <p class="text-xs text-gray-500 mt-1">
                        Format: MP3, WAV, M4A. Maksimal 10MB. Kosongkan jika tidak ingin mengubah.
                    </p>
                    @error('audio_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Warning if Verified -->
            @if ($hafalan->status === 'verified')
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Perhatian:</strong> Hafalan ini sudah diverifikasi. Mengubah data akan mereset
                                status menjadi pending dan memerlukan verifikasi ulang.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('hafalan.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Update Hafalan
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-fill Juz when Surah selected
        document.getElementById('surah-name')?.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const juzNumber = selectedOption.getAttribute('data-juz');
            if (juzNumber) {
                document.getElementById('juz-number').value = juzNumber;
            }
        });

        // Validate ayat range
        const ayatStart = document.querySelector('input[name="ayat_start"]');
        const ayatEnd = document.querySelector('input[name="ayat_end"]');

        ayatEnd?.addEventListener('change', function() {
            if (parseInt(this.value) < parseInt(ayatStart.value)) {
                alert('Ayat akhir tidak boleh lebih kecil dari ayat mulai!');
                this.value = ayatStart.value;
            }
        });
    </script>
@endpush
