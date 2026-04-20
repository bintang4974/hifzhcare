@extends('layouts.app-enhanced')

@section('title', 'Edit Profil')
@section('breadcrumb', 'Edit Profil')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Profil</h1>
                <p class="text-gray-600 mt-1">Perbarui informasi profil Anda</p>
            </div>
            <a href="{{ route('profile.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <div class="flex">
                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                    <p class="text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Edit Form -->
        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="bg-white rounded-2xl shadow-lg p-8 space-y-6">

                <!-- Avatar Upload -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-4">Foto Profil</label>
                    <div class="flex items-center gap-6">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div>
                            <button type="button" onclick="document.getElementById('avatarUpload').click()"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition">
                                <i class="fas fa-upload mr-2"></i>Upload Foto
                            </button>
                            <input type="file" id="avatarUpload" name="avatar" class="hidden" accept="image/*">
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG atau GIF (Max. 2MB)</p>
                        </div>
                    </div>
                </div>

                <hr class="my-6">

                <!-- Full Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="Masukkan nama lengkap">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                        required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="email@example.com">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if (!auth()->user()->email_verified_at)
                        <div class="mt-2 flex items-center gap-2">
                            <span class="text-sm text-amber-600">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Email belum diverifikasi
                            </span>
                            <button type="button" class="text-sm text-blue-600 hover:text-blue-700 font-semibold">
                                Kirim Verifikasi
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon
                    </label>
                    <input type="tel" name="phone" id="phone" value="{{ old('phone', auth()->user()->phone) }}"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="+62 812 3456 7890">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Read-only fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Pengguna
                        </label>
                        <input type="text" value="{{ ucfirst(auth()->user()->user_type) }}" disabled
                            class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                    </div>

                    @if (auth()->user()->pesantren)
                        <!-- Pesantren -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pesantren
                            </label>
                            <input type="text" value="{{ auth()->user()->pesantren->name }}" disabled
                                class="w-full rounded-lg border-gray-300 bg-gray-100 text-gray-600 cursor-not-allowed">
                        </div>
                    @endif
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Catatan Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Tipe pengguna dan pesantren tidak dapat diubah sendiri</li>
                                <li>Hubungi administrator jika perlu mengubah informasi tersebut</li>
                                <li>Pastikan email yang digunakan aktif untuk notifikasi sistem</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-4 pt-4">
                    <button type="submit"
                        class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                    <a href="{{ route('profile.index') }}"
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                </div>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="bg-white rounded-2xl shadow-lg p-8 border-2 border-red-200">
            <h3 class="text-xl font-bold text-red-600 mb-4">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                Danger Zone
            </h3>

            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-semibold text-gray-900">Hapus Akun</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.
                    </p>
                </div>
                <button type="button" onclick="confirmDelete()"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition">
                    <i class="fas fa-trash-alt mr-2"></i>Hapus Akun
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmDelete() {
            if (confirm('Apakah Anda yakin ingin menghapus akun? Tindakan ini tidak dapat dibatalkan!')) {
                if (confirm('Konfirmasi sekali lagi. Semua data Anda akan hilang permanen!')) {
                    // Implement delete account logic
                    alert('Hubungi administrator untuk menghapus akun Anda.');
                }
            }
        }

        // Preview uploaded image
        document.getElementById('avatarUpload')?.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Show preview (implement as needed)
                    console.log('Image uploaded:', file.name);
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
