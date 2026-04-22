@extends('layouts.app-enhanced')

@section('title', 'Dana Apresiasi')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <div class="flex items-center mb-6">
                <div
                    class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-2xl flex items-center justify-center mr-4">
                    <i class="fas fa-hand-holding-heart text-white text-3xl"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Dana Apresiasi</h1>
                    <p class="text-gray-600">Berikan apresiasi kepada ustadz yang membimbing putra/putri Anda</p>
                </div>
            </div>

            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
                    <p class="text-red-800">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data" id="donationForm">
                @csrf

                <!-- Select Ustadz -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih Ustadz <span class="text-red-500">*</span>
                    </label>
                    <select name="ustadz_id" id="ustadzSelect" required
                        class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200">
                        <option value="">-- Pilih Ustadz --</option>
                        @foreach ($ustadzList as $ustadz)
                            <option value="{{ $ustadz->id }}">
                                {{ $ustadz->user->name }} - {{ $ustadz->activeClassesRelation->first()?->name ?? 'Kelas tidak ada' }}
                            </option>
                        @endforeach
                    </select>
                    @error('ustadz_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    @if ($ustadzList->isEmpty())
                        <div class="mt-3 p-4 bg-yellow-50 border-l-4 border-yellow-500 rounded-lg">
                            <p class="text-yellow-800 text-sm">
                                <i class="fas fa-info-circle mr-2"></i>
                                Belum ada ustadz yang mengajar putra/putri Anda.
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Amount -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nominal Donasi <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">Rp</span>
                        <input type="number" name="amount" id="amountInput" required min="10000" step="1000"
                            class="w-full pl-14 pr-4 py-3 rounded-lg border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200 text-lg font-semibold"
                            placeholder="100000" onkeyup="calculateFees()" onchange="calculateFees()">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Minimal donasi Rp 10.000</p>
                    @error('amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <!-- Fee Breakdown -->
                    <div id="feeBreakdown"
                        class="mt-4 p-5 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2 border-pink-200 hidden">
                        <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-calculator text-pink-600 mr-2"></i>
                            Rincian Donasi
                        </h4>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-700">Total Donasi</span>
                                <span class="text-2xl font-bold text-gray-900" id="totalAmount">Rp 0</span>
                            </div>
                            <hr class="border-pink-200">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Biaya Platform (3%)</span>
                                <span class="font-semibold text-gray-700" id="platformFee">Rp 0</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-600">Biaya Pesantren (10%)</span>
                                <span class="font-semibold text-gray-700" id="pesantrenFee">Rp 0</span>
                            </div>
                            <hr class="border-pink-200">
                            <div class="flex justify-between items-center p-3 bg-green-100 rounded-lg">
                                <span class="font-semibold text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Diterima Ustadz
                                </span>
                                <span class="text-xl font-bold text-green-700" id="ustadzNet">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Method -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Metode Pembayaran
                    </label>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Bank Transfer -->
                        <div class="p-5 border-2 border-blue-200 bg-blue-50 rounded-xl">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-university text-blue-600 text-2xl mr-3"></i>
                                <h4 class="font-bold text-gray-900">Transfer Bank</h4>
                            </div>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Bank:</span>
                                    <strong class="text-gray-900">{{ $settings->bank_name ?? 'BCA' }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">No. Rekening:</span>
                                    <strong class="text-gray-900">{{ $settings->account_number ?? '1234567890' }}</strong>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Atas Nama:</span>
                                    <strong class="text-gray-900">{{ $settings->account_name ?? 'HIFZHCARE' }}</strong>
                                </div>
                            </div>
                            <button type="button" onclick="copyAccountNumber()"
                                class="mt-3 w-full px-4 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">
                                <i class="fas fa-copy mr-2"></i>Salin Nomor Rekening
                            </button>
                        </div>

                        <!-- QRIS -->
                        @if ($settings->qris_image)
                            <div class="p-5 border-2 border-purple-200 bg-purple-50 rounded-xl">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-qrcode text-purple-600 text-2xl mr-3"></i>
                                    <h4 class="font-bold text-gray-900">Scan QRIS</h4>
                                </div>
                                <div class="bg-white p-3 rounded-lg">
                                    <img src="{{ Storage::url($settings->qris_image) }}" alt="QRIS"
                                        class="w-full h-auto object-contain">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Upload Proof -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bukti Transfer <span class="text-red-500">*</span>
                    </label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-pink-500 transition">
                        <input type="file" name="payment_proof" id="proofInput" required accept="image/*"
                            class="hidden" onchange="previewProof(this)">
                        <label for="proofInput" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-gray-600 mb-1">Klik untuk upload bukti transfer</p>
                            <p class="text-xs text-gray-500">JPG, PNG (Max 2MB)</p>
                        </label>
                        <div id="proofPreview" class="mt-4 hidden">
                            <img id="proofImage" class="max-w-xs mx-auto rounded-lg shadow-lg">
                        </div>
                    </div>
                    @error('payment_proof')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan untuk Ustadz (Opsional)
                    </label>
                    <textarea name="notes" rows="4"
                        class="w-full rounded-lg border-gray-300 focus:border-pink-500 focus:ring focus:ring-pink-200"
                        placeholder="Tulis pesan atau doa untuk ustadz..."></textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Box -->
                <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Informasi Penting:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Transfer ke rekening yang tertera di atas</li>
                                <li>Upload bukti transfer yang jelas dan valid</li>
                                <li>Donasi akan diverifikasi oleh admin (1-2 hari kerja)</li>
                                <li>Dana akan diteruskan ke ustadz melalui pondok</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <a href="{{ route('donations.index') }}"
                        class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 font-semibold rounded-xl text-center hover:bg-gray-300 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                        class="flex-1 px-6 py-4 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                        <i class="fas fa-heart mr-2"></i>Kirim Dana Apresiasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function calculateFees() {
                const amount = parseFloat(document.getElementById('amountInput').value) || 0;

                if (amount >= 10000) {
                    const platformFee = amount * 0.03;
                    const pesantrenFee = amount * 0.10;
                    const ustadzNet = amount - platformFee - pesantrenFee;

                    document.getElementById('totalAmount').textContent = formatRupiah(amount);
                    document.getElementById('platformFee').textContent = formatRupiah(platformFee);
                    document.getElementById('pesantrenFee').textContent = formatRupiah(pesantrenFee);
                    document.getElementById('ustadzNet').textContent = formatRupiah(ustadzNet);
                    document.getElementById('feeBreakdown').classList.remove('hidden');
                } else {
                    document.getElementById('feeBreakdown').classList.add('hidden');
                }
            }

            function formatRupiah(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.floor(amount));
            }

            function copyAccountNumber() {
                const accountNumber = '{{ $settings->account_number ?? '1234567890' }}';
                navigator.clipboard.writeText(accountNumber);
                alert('Nomor rekening berhasil disalin!');
            }

            function previewProof(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('proofImage').src = e.target.result;
                        document.getElementById('proofPreview').classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }

            document.getElementById('donationForm').addEventListener('submit', function(e) {
                const amount = parseFloat(document.getElementById('amountInput').value);
                if (amount < 10000) {
                    e.preventDefault();
                    alert('Minimal donasi adalah Rp 10.000');
                }
            });
        </script>
    @endpush
@endsection
