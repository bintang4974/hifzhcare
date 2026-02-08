@extends('layouts.app-enhanced')

@section('title', 'Tambah Admin Pesantren')
@section('breadcrumb', 'Super Admin / Admin / Tambah')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tambah Admin Pesantren</h1>
                <p class="text-gray-600 mt-1">Buat akun admin baru untuk pesantren</p>
            </div>
            <a href="{{ route('superadmin.admins.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <form action="{{ route('superadmin.admins.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Data Admin -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-user-shield text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Data Admin</h3>
                        <p class="text-sm text-gray-600">Informasi akun admin</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user mr-1 text-indigo-600"></i>
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 @error('name') border-red-500 @enderror"
                            placeholder="Masukkan nama lengkap admin">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-envelope mr-1 text-indigo-600"></i>
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 @error('email') border-red-500 @enderror"
                            placeholder="admin@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor HP -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-phone mr-1 text-indigo-600"></i>
                            Nomor HP <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-indigo-600"></i>
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 @error('password') border-red-500 @enderror"
                            placeholder="Minimal 8 karakter">
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock mr-1 text-indigo-600"></i>
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" name="password_confirmation" required minlength="8"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                            placeholder="Ketik ulang password">
                    </div>
                </div>
            </div>

            <!-- Assignment -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <div class="flex items-center mb-6">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center text-white mr-4">
                        <i class="fas fa-building text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Assignment Pesantren</h3>
                        <p class="text-sm text-gray-600">Tetapkan pesantren yang akan dikelola</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Pesantren -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mosque mr-1 text-purple-600"></i>
                            Pilih Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select name="pesantren_id" required id="pesantrenSelect"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('pesantren_id') border-red-500 @enderror">
                            <option value="">Pilih Pesantren</option>
                            @foreach ($pesantrens as $pesantren)
                                <option value="{{ $pesantren->id }}"
                                    {{ old('pesantren_id') == $pesantren->id ? 'selected' : '' }}
                                    data-code="{{ $pesantren->code }}" data-address="{{ $pesantren->address }}"
                                    data-santri="{{ $pesantren->santri_profiles_count ?? 0 }}">
                                    {{ $pesantren->name }} ({{ $pesantren->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('pesantren_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pesantren Info Preview -->
                    <div id="pesantrenInfo" class="hidden p-4 bg-purple-50 border border-purple-200 rounded-lg">
                        <p class="text-sm font-semibold text-purple-900 mb-2">Info Pesantren:</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-purple-800">
                            <div>
                                <i class="fas fa-code mr-1"></i>
                                <span>Kode: <strong id="infoCode"></strong></span>
                            </div>
                            <div>
                                <i class="fas fa-user-graduate mr-1"></i>
                                <span>Santri: <strong id="infoSantri"></strong></span>
                            </div>
                            <div class="md:col-span-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span>Alamat: <strong id="infoAddress"></strong></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-purple-600"></i>
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending (Perlu
                                Aktivasi)</option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif
                                (Langsung Bisa Login)</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Auto Send Email -->
                    <div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="send_credentials_email" value="1"
                                {{ old('send_credentials_email', true) ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                            <div>
                                <span class="font-medium text-gray-900">Kirim Email Kredensial</span>
                                <p class="text-xs text-gray-600">Email berisi username & password akan dikirim ke admin</p>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-800">
                            <strong>Catatan:</strong> Admin yang dibuat akan memiliki akses penuh ke data pesantren yang
                            dipilih.
                            Pastikan memberikan kredensial ke orang yang tepat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('superadmin.admins.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Simpan Admin
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Show pesantren info when selected
        document.getElementById('pesantrenSelect')?.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const infoDiv = document.getElementById('pesantrenInfo');

            if (selected.value) {
                document.getElementById('infoCode').textContent = selected.dataset.code;
                document.getElementById('infoAddress').textContent = selected.dataset.address;
                document.getElementById('infoSantri').textContent = selected.dataset.santri;
                infoDiv.classList.remove('hidden');
            } else {
                infoDiv.classList.add('hidden');
            }
        });

        // Auto-format phone numbers
        document.querySelector('input[name="phone"]')?.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
        });

        // Trigger on page load if old value exists
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('pesantrenSelect');
            if (select && select.value) {
                select.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
