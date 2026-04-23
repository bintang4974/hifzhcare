@extends('layouts.app-enhanced')

@section('title', 'Generate Certificate')
@section('breadcrumb', 'Generate Certificate')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Generate Certificate</h1>
                <p class="text-gray-600 mt-1">Buat sertifikat manual untuk santri yang menyelesaikan hafalan</p>
            </div>
            <a href="{{ route('certificates.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
        </div>

        <!-- Info Box -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-800">
                        <strong>Catatan:</strong> Sertifikat biasanya dibuat otomatis saat santri menyelesaikan hafalan per
                        Juz.
                        Gunakan form ini hanya untuk generate manual jika diperlukan (misal: data lama, sertifikat hilang,
                        dll).
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('certificates.storeManual') }}" id="certificateForm">
                @csrf

                <!-- Step 1: Select Santri -->
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            1
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Pilih Santri</h3>
                            <p class="text-sm text-gray-600">Cari dan pilih santri yang akan diterbitkan sertifikat</p>
                        </div>
                    </div>

                    <div class="ml-14">
                        <label for="santri_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Santri <span class="text-red-500">*</span>
                        </label>
                        <select name="santri_id" id="santri_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"
                            onchange="loadSantriInfo()">
                            <option value="">-- Pilih Santri --</option>
                            @foreach ($santris as $santri)
                                <option value="{{ $santri->id }}" data-nis="{{ $santri->nis }}"
                                    data-class="{{ $santri->firstActiveClass()?->name ?? '-' }}"
                                    data-progress="{{ $santri->progress_percentage }}"
                                    data-certificates="{{ $santri->certificates->count() }}">
                                    {{ $santri->user->name }} ({{ $santri->nis }})
                                </option>
                            @endforeach
                        </select>

                        @error('santri_id')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror

                        <!-- Santri Info Preview -->
                        <div id="santriInfo"
                            class="hidden mt-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg border border-blue-200">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <p class="text-xs text-gray-600">NIS</p>
                                    <p class="font-bold text-gray-900" id="info-nis">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Kelas</p>
                                    <p class="font-bold text-gray-900" id="info-class">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Progress</p>
                                    <p class="font-bold text-gray-900" id="info-progress">-</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-600">Sertifikat</p>
                                    <p class="font-bold text-gray-900" id="info-certificates">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-8">

                <!-- Step 2: Certificate Type -->
                <div class="mb-8">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            2
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Tipe Sertifikat</h3>
                            <p class="text-sm text-gray-600">Pilih jenis sertifikat yang akan diterbitkan</p>
                        </div>
                    </div>

                    <div class="ml-14">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Per Juz Certificate -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="certificate_type" value="per_juz" required class="peer sr-only"
                                    onchange="toggleJuzSelector()">
                                <div
                                    class="p-6 bg-white border-2 border-gray-200 rounded-xl transition-all 
                                        peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-lg hover:shadow-md">
                                    <div class="flex items-start">
                                        <div
                                            class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-bookmark text-purple-600 text-2xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-1">Sertifikat Per Juz</h4>
                                            <p class="text-sm text-gray-600">
                                                Untuk santri yang menyelesaikan 1 Juz tertentu
                                            </p>
                                            <div class="mt-3 text-xs text-gray-500">
                                                <i class="fas fa-check-circle text-purple-500 mr-1"></i>
                                                Pilih juz yang sudah diselesaikan
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <!-- Khatam Certificate -->
                            <label class="relative cursor-pointer">
                                <input type="radio" name="certificate_type" value="khatam" required class="peer sr-only"
                                    onchange="toggleJuzSelector()">
                                <div
                                    class="p-6 bg-white border-2 border-gray-200 rounded-xl transition-all 
                                        peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-lg hover:shadow-md">
                                    <div class="flex items-start">
                                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                            <i class="fas fa-book-quran text-blue-600 text-2xl"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900 mb-1">Sertifikat Khatam</h4>
                                            <p class="text-sm text-gray-600">
                                                Untuk santri yang menyelesaikan 30 Juz Al-Quran
                                            </p>
                                            <div class="mt-3 text-xs text-gray-500">
                                                <i class="fas fa-star text-blue-500 mr-1"></i>
                                                Pencapaian tertinggi
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        @error('certificate_type')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-8">

                <!-- Step 3: Juz Selection (Only for Per Juz) -->
                <div class="mb-8" id="juzSelectorSection" style="display: none;">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            3
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Pilih Juz</h3>
                            <p class="text-sm text-gray-600">Pilih juz yang telah diselesaikan santri</p>
                        </div>
                    </div>

                    <div class="ml-14">
                        <label for="juz_number" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Juz <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-6 md:grid-cols-10 gap-2">
                            @for ($i = 1; $i <= 30; $i++)
                                <label class="relative cursor-pointer">
                                    <input type="radio" name="juz_number" value="{{ $i }}"
                                        class="peer sr-only">
                                    <div
                                        class="w-full aspect-square bg-white border-2 border-gray-300 rounded-lg 
                                        flex items-center justify-center font-bold text-gray-700 transition-all
                                        peer-checked:border-yellow-500 peer-checked:bg-yellow-500 peer-checked:text-white
                                        hover:border-yellow-400 hover:shadow-md">
                                        {{ $i }}
                                    </div>
                                </label>
                            @endfor
                        </div>

                        @error('juz_number')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-8" id="detailsSeparator" style="display: none;">

                <!-- Step 4: Certificate Details -->
                <div class="mb-8" id="detailsSection" style="display: none;">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-yellow-500 to-orange-600 rounded-full flex items-center justify-center text-white font-bold mr-4">
                            <span id="stepNumber">4</span>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Detail Sertifikat</h3>
                            <p class="text-sm text-gray-600">Isi informasi tambahan sertifikat</p>
                        </div>
                    </div>

                    <div class="ml-14 space-y-6">
                        <!-- Issue Date -->
                        <div>
                            <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Terbit <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="issue_date" id="issue_date" value="{{ date('Y-m-d') }}"
                                required
                                class="w-full md:w-1/2 rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200">
                            @error('issue_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div>
                            <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan (Opsional)
                            </label>
                            <textarea name="notes" id="notes" rows="3" placeholder="Tambahkan catatan khusus (jika ada)"
                                class="w-full rounded-lg border-gray-300 focus:border-yellow-500 focus:ring focus:ring-yellow-200"></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                Contoh: Generated manually, Data lama, Sertifikat pengganti, dll.
                            </p>
                            @error('notes')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Peringatan:</strong> Pastikan data yang diisi sudah benar.
                                Sertifikat yang sudah dibuat akan tercatat secara permanen di sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-4">
                    <button type="button" onclick="window.history.back()"
                        class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 font-semibold rounded-xl hover:bg-gray-300 transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </button>
                    <button type="submit"
                        class="flex-1 px-6 py-4 bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <i class="fas fa-certificate mr-2"></i>Generate Sertifikat
                    </button>
                </div>
            </form>
        </div>

        <!-- Preview Section (Hidden until form filled) -->
        <div id="previewSection"
            class="hidden bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl shadow-lg p-8 border-2 border-yellow-200">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-eye text-yellow-600 mr-2"></i>Preview Sertifikat
            </h3>
            <div class="bg-white rounded-xl p-6 border-2 border-yellow-300">
                <div class="text-center">
                    <div class="text-sm text-gray-500 mb-2">Nomor Sertifikat (Preview)</div>
                    <div class="text-lg font-bold text-gray-900 mb-4" id="preview-number">
                        [Akan dibuat otomatis]
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-left">
                        <div>
                            <p class="text-xs text-gray-500">Santri</p>
                            <p class="font-bold" id="preview-santri">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tipe</p>
                            <p class="font-bold" id="preview-type">-</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500">Tanggal</p>
                            <p class="font-bold" id="preview-date">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function loadSantriInfo() {
            const select = document.getElementById('santri_id');
            const option = select.options[select.selectedIndex];

            if (option.value) {
                document.getElementById('santriInfo').classList.remove('hidden');
                document.getElementById('info-nis').textContent = option.dataset.nis;
                document.getElementById('info-class').textContent = option.dataset.class;
                document.getElementById('info-progress').textContent = option.dataset.progress + '%';
                document.getElementById('info-certificates').textContent = option.dataset.certificates + ' sertifikat';

                updatePreview();
            } else {
                document.getElementById('santriInfo').classList.add('hidden');
            }
        }

        function toggleJuzSelector() {
            const type = document.querySelector('input[name="certificate_type"]:checked').value;
            const juzSection = document.getElementById('juzSelectorSection');
            const detailsSection = document.getElementById('detailsSection');
            const detailsSeparator = document.getElementById('detailsSeparator');
            const stepNumber = document.getElementById('stepNumber');

            if (type === 'per_juz') {
                juzSection.style.display = 'block';
                detailsSection.style.display = 'block';
                detailsSeparator.style.display = 'block';
                stepNumber.textContent = '4';
            } else {
                juzSection.style.display = 'none';
                detailsSection.style.display = 'block';
                detailsSeparator.style.display = 'block';
                stepNumber.textContent = '3';
            }

            updatePreview();
        }

        function updatePreview() {
            const santriSelect = document.getElementById('santri_id');
            const typeRadio = document.querySelector('input[name="certificate_type"]:checked');
            const juzRadio = document.querySelector('input[name="juz_number"]:checked');
            const dateInput = document.getElementById('issue_date');

            if (santriSelect.value && typeRadio) {
                document.getElementById('previewSection').classList.remove('hidden');

                // Update preview
                const santriName = santriSelect.options[santriSelect.selectedIndex].text;
                document.getElementById('preview-santri').textContent = santriName;

                if (typeRadio.value === 'khatam') {
                    document.getElementById('preview-type').textContent = 'Khatam 30 Juz';
                } else if (juzRadio) {
                    document.getElementById('preview-type').textContent = 'Juz ' + juzRadio.value;
                } else {
                    document.getElementById('preview-type').textContent = '-';
                }

                if (dateInput.value) {
                    const date = new Date(dateInput.value);
                    document.getElementById('preview-date').textContent = date.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'long',
                        year: 'numeric'
                    });
                }
            }
        }

        // Update preview on date change
        document.getElementById('issue_date')?.addEventListener('change', updatePreview);

        // Update preview on juz selection
        document.querySelectorAll('input[name="juz_number"]').forEach(radio => {
            radio.addEventListener('change', updatePreview);
        });

        // Form validation
        document.getElementById('certificateForm').addEventListener('submit', function(e) {
            const type = document.querySelector('input[name="certificate_type"]:checked');

            if (!type) {
                e.preventDefault();
                alert('Pilih tipe sertifikat!');
                return false;
            }

            if (type.value === 'per_juz') {
                const juz = document.querySelector('input[name="juz_number"]:checked');
                if (!juz) {
                    e.preventDefault();
                    alert('Pilih nomor juz!');
                    return false;
                }
            }

            return confirm('Generate sertifikat untuk santri ini?');
        });
    </script>
@endpush
