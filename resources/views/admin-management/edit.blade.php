@extends('layouts.app-enhanced')

@section('title', 'Edit Admin Pesantren')
@section('breadcrumb', 'Super Admin / Admin / Edit')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Admin Pesantren</h1>
                <p class="text-gray-600 mt-1">Update data admin {{ $admin->name }}</p>
            </div>
            <a href="{{ route('superadmin.admins.index') }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl shadow-md transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Current Status Info -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                    <div>
                        <p class="text-sm text-blue-800">
                            <strong>Status Saat Ini:</strong>
                            @if ($admin->status === 'active')
                                <span
                                    class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded">Aktif</span>
                            @elseif($admin->status === 'pending')
                                <span
                                    class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded">Pending</span>
                            @else
                                <span
                                    class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded">Inactive</span>
                            @endif
                        </p>
                        <p class="text-xs text-blue-700 mt-1">
                            Terdaftar: {{ $admin->created_at->format('d M Y') }} •
                            Last Login:
                            {{ $admin->last_login_at ? $admin->last_login_at->diffForHumans() : 'Belum pernah' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form action="{{ route('superadmin.admins.update', $admin->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

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
                        <input type="text" name="name" value="{{ old('name', $admin->name) }}" required
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
                        <input type="email" name="email" value="{{ old('email', $admin->email) }}" required
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
                        <input type="tel" name="phone" value="{{ old('phone', $admin->phone) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 @error('phone') border-red-500 @enderror"
                            placeholder="08123456789">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Optional) -->
                    <div class="md:col-span-2">
                        <label class="flex items-center cursor-pointer mb-4">
                            <input type="checkbox" id="changePassword"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 mr-3">
                            <span class="font-medium text-gray-900">Ganti Password</span>
                        </label>

                        <div id="passwordFields" class="hidden grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- New Password -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-lock mr-1 text-indigo-600"></i>
                                    Password Baru
                                </label>
                                <input type="password" name="password" minlength="8"
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
                                    Konfirmasi Password
                                </label>
                                <input type="password" name="password_confirmation" minlength="8"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200"
                                    placeholder="Ketik ulang password">
                            </div>
                        </div>
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
                    <!-- Current Pesantren -->
                    @if ($admin->pesantren)
                        <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                            <p class="text-sm font-semibold text-green-900 mb-2">Pesantren Saat Ini:</p>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-bold text-gray-900">{{ $admin->pesantren->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $admin->pesantren->code }} •
                                        {{ $admin->pesantren->address }}</p>
                                </div>
                                <button type="button"
                                    onclick="document.getElementById('changePesantren').classList.toggle('hidden')"
                                    class="px-3 py-1 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                    <i class="fas fa-sync-alt mr-1"></i>Ganti
                                </button>
                            </div>
                        </div>
                    @endif

                    <!-- Change Pesantren -->
                    <div id="changePesantren" class="{{ $admin->pesantren ? 'hidden' : '' }}">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-mosque mr-1 text-purple-600"></i>
                            Pilih Pesantren <span class="text-red-500">*</span>
                        </label>
                        <select name="pesantren_id" id="pesantrenSelect"
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('pesantren_id') border-red-500 @enderror">
                            <option value="">Pilih Pesantren</option>
                            @foreach ($pesantrens as $pesantren)
                                <option value="{{ $pesantren->id }}"
                                    {{ old('pesantren_id', $admin->pesantren_id) == $pesantren->id ? 'selected' : '' }}
                                    data-code="{{ $pesantren->code }}" data-address="{{ $pesantren->address }}"
                                    data-santri="{{ $pesantren->santri_profiles_count ?? 0 }}">
                                    {{ $pesantren->name }} ({{ $pesantren->code }})
                                </option>
                            @endforeach
                        </select>
                        @error('pesantren_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror

                        <!-- Pesantren Info Preview -->
                        <div id="pesantrenInfo" class="hidden mt-3 p-4 bg-purple-50 border border-purple-200 rounded-lg">
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
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-info-circle mr-1 text-purple-600"></i>
                            Status Akun <span class="text-red-500">*</span>
                        </label>
                        <select name="status" required
                            class="w-full rounded-lg border-gray-300 focus:border-purple-500 focus:ring focus:ring-purple-200 @error('status') border-red-500 @enderror">
                            <option value="pending" {{ old('status', $admin->status) == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="active" {{ old('status', $admin->status) == 'active' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="inactive" {{ old('status', $admin->status) == 'inactive' ? 'selected' : '' }}>
                                Inactive</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Warning Box -->
            @if ($admin->pesantren)
                <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-lg">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-800">
                                <strong>Perhatian:</strong> Mengganti pesantren akan memindahkan akses admin ini dari
                                <strong>{{ $admin->pesantren->name }}</strong> ke pesantren baru yang dipilih.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('superadmin.admins.index') }}"
                    class="flex-1 text-center px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition shadow-md">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit"
                    class="flex-1 px-6 py-4 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-save mr-2"></i>Update Admin
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password fields
        document.getElementById('changePassword')?.addEventListener('change', function() {
            const passwordFields = document.getElementById('passwordFields');
            const inputs = passwordFields.querySelectorAll('input');

            if (this.checked) {
                passwordFields.classList.remove('hidden');
                inputs.forEach(input => input.required = true);
            } else {
                passwordFields.classList.add('hidden');
                inputs.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
            }
        });

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

        // Trigger on page load if value exists
        document.addEventListener('DOMContentLoaded', function() {
            const select = document.getElementById('pesantrenSelect');
            if (select && select.value) {
                select.dispatchEvent(new Event('change'));
            }
        });
    </script>
@endpush
