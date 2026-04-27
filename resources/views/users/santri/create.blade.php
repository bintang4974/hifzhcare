@extends('layouts.app-enhanced')

@section('title', 'Tambah Santri')
@section('breadcrumb', 'Pengguna / Santri / Tambah')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Santri Baru</h1>
                <p class="text-gray-600 mt-1">Lengkapi data santri dan wali</p>
            </div>
            <a href="{{ route('users.santri.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('users.santri.store') }}" method="POST" class="space-y-6" id="santri-form">
            @csrf

            <!-- Pesantren Selection (Super Admin Only) -->
            @if (auth()->user()->hasRole('Super Admin') && $pesantrens && $pesantrens->count() > 0)
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-6">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                            <i class="fas fa-mosque text-2xl"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">Pilih Pesantren</h3>
                            <p class="text-sm text-gray-600">Tentukan pesantren tempat santri akan didaftarkan</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-building mr-1 text-purple-600"></i>
                            Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select name="pesantren_id" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('pesantren_id') border-red-500 @enderror">
                            <option value="">-- Pilih Pesantren --</option>
                            @foreach ($pesantrens as $pesantren)
                                <option value="{{ $pesantren->id }}" {{ old('pesantren_id') == $pesantren->id ? 'selected' : '' }}>
                                    {{ $pesantren->name }} ({{ $pesantren->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('pesantren_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

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
                        <input type="text" name="name" value="{{ old('name') }}" required
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
                        <input type="text" name="nis" value="{{ old('nis') }}" required
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
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
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
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
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
                        <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required
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
                        <input type="email" name="email" value="{{ old('email') }}"
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
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
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
                            placeholder="Masukkan alamat lengkap">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Data Wali Section -->
            <div class="bg-white rounded-xl shadow-lg p-6" x-data="{ useExisting: false }">
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

                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" x-model="useExisting"
                            class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 mr-2">
                        <span class="text-sm text-gray-700">Gunakan wali yang sudah ada</span>
                    </label>
                </div>

                <!-- Use Existing Wali -->
                <div x-show="useExisting" x-cloak>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Wali</label>
                        <select id="wali-select" name="wali_id" :disabled="!useExisting"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200">
                            <option value="">Pilih Wali yang Sudah Ada</option>
                            @foreach ($walis as $wali)
                                <option value="{{ $wali->id }}">
                                    {{ $wali->user->name }} - {{ $wali->user->phone }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- New Wali Form -->
                <div x-show="!useExisting">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Nama Wali -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-1 text-green-600"></i>
                                Nama Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="wali_name" value="{{ old('wali_name') }}" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_name') border-red-500 @enderror"
                                placeholder="Masukkan nama wali">
                            @error('wali_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Hubungan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heart mr-1 text-green-600"></i>
                                Hubungan <span class="text-red-500">*</span>
                            </label>
                            <select name="wali_relation" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_relation') border-red-500 @enderror">
                                <option value="">Pilih Hubungan</option>
                                <option value="ayah" {{ old('wali_relation') == 'ayah' ? 'selected' : '' }}>Ayah
                                </option>
                                <option value="ibu" {{ old('wali_relation') == 'ibu' ? 'selected' : '' }}>Ibu</option>
                                <option value="wali" {{ old('wali_relation') == 'wali' ? 'selected' : '' }}>Wali
                                </option>
                            </select>
                            @error('wali_relation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK Wali -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-id-card mr-1 text-green-600"></i>
                                NIK (Opsional)
                            </label>
                            <input type="text" name="wali_nik" value="{{ old('wali_nik') }}" maxlength="16" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_nik') border-red-500 @enderror"
                                placeholder="16 digit NIK">
                            @error('wali_nik')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Wali -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-envelope mr-1 text-green-600"></i>
                                Email Wali (Opsional)
                            </label>
                            <input type="email" name="wali_email" value="{{ old('wali_email') }}" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_email') border-red-500 @enderror"
                                placeholder="wali@example.com">
                            @error('wali_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- HP Wali -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-phone mr-1 text-green-600"></i>
                                Nomor HP Wali <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="wali_phone" value="{{ old('wali_phone') }}" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_phone') border-red-500 @enderror"
                                placeholder="08123456789">
                            @error('wali_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Pekerjaan Wali -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-briefcase mr-1 text-green-600"></i>
                                Pekerjaan (Opsional)
                            </label>
                            <input type="text" name="wali_occupation" value="{{ old('wali_occupation') }}" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_occupation') border-red-500 @enderror"
                                placeholder="Contoh: Guru, Wiraswasta">
                            @error('wali_occupation')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Wali -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-1 text-green-600"></i>
                                Alamat Wali (Opsional)
                            </label>
                            <textarea name="wali_address" rows="2" :disabled="useExisting"
                                class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200 @error('wali_address') border-red-500 @enderror"
                                placeholder="Sama dengan alamat santri atau berbeda">{{ old('wali_address') }}</textarea>
                            @error('wali_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
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
                    <i class="fas fa-save mr-2"></i>Simpan Santri
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Select2 Tailwind Integration */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            height: 42px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #374151;
            line-height: 42px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 42px;
            right: 12px;
        }

        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #22c55e;
            box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
        }

        .select2-dropdown {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .select2-results__option {
            padding: 10px 12px;
            color: #374151;
        }

        .select2-results__option--highlighted {
            background-color: #22c55e !important;
            color: white;
        }

        .select2-results__option--selected {
            background-color: #f0fdf4;
            color: #166534;
        }

        .select2-search__field {
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        // Initialize Select2 on wali dropdown
        $(document).ready(function() {
            $('#wali-select').select2({
                placeholder: 'Cari nama atau nomor HP wali...',
                allowClear: true,
                width: '100%'
            });
        });

        // Auto-format NIK (16 digits)
        document.addEventListener('input', function(e) {
            if (e.target.name === 'wali_nik') {
                e.target.value = e.target.value.replace(/\D/g, '').substring(0, 16);
            }
        });

        // Auto-format phone numbers
        document.addEventListener('input', function(e) {
            if (e.target.type === 'tel') {
                e.target.value = e.target.value.replace(/\D/g, '');
            }
        });
    </script>
@endpush
