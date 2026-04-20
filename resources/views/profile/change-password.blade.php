@extends('layouts.app-enhanced')

@section('title', 'Ubah Password')
@section('breadcrumb', 'Ubah Password')

@section('content')
    <div class="py-6 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900">Ubah Password</h1>
            <p class="text-gray-600 mt-2">Perbarui password akun Anda untuk keamanan lebih baik</p>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header Section -->
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-6 sm:px-8 py-6">
                <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                    <i class="fas fa-lock text-2xl"></i>Keamanan Akun
                </h2>
                <p class="text-blue-100 mt-2">Pastikan password Anda kuat dan aman</p>
            </div>

            <!-- Form Section -->
            <div class="p-6 sm:p-8">
                @if ($errors->any())
                    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                        <h3 class="font-semibold text-red-800 mb-2 flex items-center gap-2">
                            <i class="fas fa-exclamation-circle"></i>Terjadi kesalahan:
                        </h3>
                        <ul class="space-y-1">
                            @foreach ($errors->all() as $error)
                                <li class="text-sm text-red-700">• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800 flex items-center gap-2">
                            <i class="fas fa-check-circle"></i>{{ session('success') }}
                        </p>
                    </div>
                @endif

                <!-- Security Tips -->
                <div class="mb-8 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                        <i class="fas fa-shield-alt"></i>Tips Keamanan Password
                    </h3>
                    <ul class="space-y-2 text-sm text-blue-800">
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Gunakan minimal 8 karakter</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Campurkan huruf besar dan kecil</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Sertakan angka (0-9)</li>
                        <li><i class="fas fa-check text-green-600 mr-2"></i>Tambahkan karakter khusus (!@#$%^&*)</li>
                    </ul>
                </div>

                <!-- Change Password Form -->
                <form method="POST" action="{{ route('profile.update-password') }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-lock-open text-gray-400 mr-2"></i>Password Saat Ini
                        </label>
                        <div class="relative">
                            <input type="password" 
                                id="current_password" 
                                name="current_password"
                                placeholder="Masukkan password saat ini"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('current_password') ? 'border-red-500' : '' }}"
                                required>
                            <button type="button" 
                                onclick="togglePassword('current_password')"
                                class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700 transition">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                        @error('current_password')
                            <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-6"></div>

                    <!-- New Password -->
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-key text-gray-400 mr-2"></i>Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                id="new_password" 
                                name="new_password"
                                placeholder="Masukkan password baru"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('new_password') ? 'border-red-500' : '' }}"
                                required>
                            <button type="button" 
                                onclick="togglePassword('new_password')"
                                class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700 transition">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                        @error('new_password')
                            <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror

                        <!-- Password Strength Indicator -->
                        <div class="mt-3">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-medium text-gray-600">Kekuatan Password:</span>
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div id="strengthBar" class="h-full w-0 bg-red-500 transition-all duration-300"></div>
                                </div>
                                <span id="strengthText" class="text-xs font-medium text-red-600">Lemah</span>
                            </div>
                        </div>

                        <!-- Password Requirements Checklist -->
                        <div class="mt-4 space-y-2">
                            <div class="flex items-center gap-2">
                                <i id="check-length" class="fas fa-circle text-gray-300 text-xs"></i>
                                <span class="text-sm text-gray-600">Minimal 8 karakter</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i id="check-uppercase" class="fas fa-circle text-gray-300 text-xs"></i>
                                <span class="text-sm text-gray-600">Huruf besar (A-Z)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i id="check-lowercase" class="fas fa-circle text-gray-300 text-xs"></i>
                                <span class="text-sm text-gray-600">Huruf kecil (a-z)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i id="check-number" class="fas fa-circle text-gray-300 text-xs"></i>
                                <span class="text-sm text-gray-600">Angka (0-9)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i id="check-special" class="fas fa-circle text-gray-300 text-xs"></i>
                                <span class="text-sm text-gray-600">Karakter khusus (!@#$%^&*)</span>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-gray-900 mb-2">
                            <i class="fas fa-check-circle text-gray-400 mr-2"></i>Konfirmasi Password Baru
                        </label>
                        <div class="relative">
                            <input type="password" 
                                id="new_password_confirmation" 
                                name="new_password_confirmation"
                                placeholder="Ketik ulang password baru"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition {{ $errors->has('new_password_confirmation') ? 'border-red-500' : '' }}"
                                required>
                            <button type="button" 
                                onclick="togglePassword('new_password_confirmation')"
                                class="absolute right-3 top-3.5 text-gray-500 hover:text-gray-700 transition">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                        @error('new_password_confirmation')
                            <p class="text-sm text-red-600 mt-2 flex items-center gap-1">
                                <i class="fas fa-exclamation-circle"></i>{{ $message }}
                            </p>
                        @enderror

                        <!-- Match Indicator -->
                        <div class="mt-2">
                            <p id="matchIndicator" class="text-sm text-gray-500 flex items-center gap-2">
                                <i class="fas fa-circle-notch text-gray-300 animate-spin text-xs"></i>
                                <span>Ketik konfirmasi password untuk pencocokan</span>
                            </p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-6 border-t border-gray-200 mt-8">
                        <button type="submit" id="submitBtn"
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-6 rounded-lg transition-colors flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save"></i>
                            <span>Ubah Password</span>
                        </button>
                        <a href="{{ route('profile.index') }}"
                            class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-3 px-6 rounded-lg transition-colors">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Info -->
        <div class="mt-8 bg-gray-50 rounded-xl border border-gray-200 p-6">
            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <i class="fas fa-info-circle text-blue-600"></i>Informasi Penting
            </h3>
            <ul class="space-y-2 text-sm text-gray-700">
                <li><i class="fas fa-arrow-right text-gray-400 mr-2"></i>Jangan pernah bagikan password Anda kepada siapapun</li>
                <li><i class="fas fa-arrow-right text-gray-400 mr-2"></i>Ubah password secara berkala untuk keamanan lebih baik</li>
                <li><i class="fas fa-arrow-right text-gray-400 mr-2"></i>Gunakan password yang unik dan tidak mudah ditebak</li>
                <li><i class="fas fa-arrow-right text-gray-400 mr-2"></i>Jika akun Anda dicurigai, segera ubah password Anda</li>
            </ul>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Toggle password visibility
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            field.type = field.type === 'password' ? 'text' : 'password';
        }

        // Password strength checker
        const newPasswordField = document.getElementById('new_password');
        const confirmPasswordField = document.getElementById('new_password_confirmation');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');
        const matchIndicator = document.getElementById('matchIndicator');
        const submitBtn = document.getElementById('submitBtn');

        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            // Update checkmarks
            document.getElementById('check-length').className = checks.length ? 'fas fa-check-circle text-green-600 text-xs' : 'fas fa-circle text-gray-300 text-xs';
            document.getElementById('check-uppercase').className = checks.uppercase ? 'fas fa-check-circle text-green-600 text-xs' : 'fas fa-circle text-gray-300 text-xs';
            document.getElementById('check-lowercase').className = checks.lowercase ? 'fas fa-check-circle text-green-600 text-xs' : 'fas fa-circle text-gray-300 text-xs';
            document.getElementById('check-number').className = checks.number ? 'fas fa-check-circle text-green-600 text-xs' : 'fas fa-circle text-gray-300 text-xs';
            document.getElementById('check-special').className = checks.special ? 'fas fa-check-circle text-green-600 text-xs' : 'fas fa-circle text-gray-300 text-xs';

            // Calculate strength
            if (checks.length) strength += 20;
            if (checks.uppercase) strength += 20;
            if (checks.lowercase) strength += 20;
            if (checks.number) strength += 20;
            if (checks.special) strength += 20;

            // Update strength bar and text
            strengthBar.style.width = strength + '%';
            if (strength < 40) {
                strengthBar.className = 'h-full w-0 bg-red-500 transition-all duration-300';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs font-medium text-red-600';
            } else if (strength < 80) {
                strengthBar.className = 'h-full w-0 bg-yellow-500 transition-all duration-300';
                strengthText.textContent = 'Sedang';
                strengthText.className = 'text-xs font-medium text-yellow-600';
            } else {
                strengthBar.className = 'h-full w-0 bg-green-500 transition-all duration-300';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs font-medium text-green-600';
            }

            return checks;
        }

        function checkPasswordMatch() {
            if (confirmPasswordField.value === '') {
                matchIndicator.innerHTML = '<i class="fas fa-circle-notch text-gray-300 animate-spin text-xs"></i><span>Ketik konfirmasi password untuk pencocokan</span>';
                return false;
            }

            if (newPasswordField.value === confirmPasswordField.value) {
                matchIndicator.innerHTML = '<i class="fas fa-check-circle text-green-600 text-xs"></i><span class="text-green-600">Password cocok!</span>';
                return true;
            } else {
                matchIndicator.innerHTML = '<i class="fas fa-exclamation-circle text-red-600 text-xs"></i><span class="text-red-600">Password tidak cocok!</span>';
                return false;
            }
        }

        newPasswordField.addEventListener('input', () => {
            checkPasswordStrength(newPasswordField.value);
            checkPasswordMatch();
        });

        confirmPasswordField.addEventListener('input', checkPasswordMatch);

        // Disable submit button if passwords don't match or don't meet requirements
        submitBtn.addEventListener('click', (e) => {
            const password = newPasswordField.value;
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /\d/.test(password),
                special: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password)
            };

            const allRequirementsMet = Object.values(checks).every(v => v === true);
            const passwordsMatch = newPasswordField.value === confirmPasswordField.value;

            if (!allRequirementsMet || !passwordsMatch) {
                e.preventDefault();
            }
        });
    </script>
@endpush
