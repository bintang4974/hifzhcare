@extends('layouts.app-enhanced')

@section('title', 'Approve Pencairan')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Approve Pencairan Dana</h1>
                <p class="text-gray-600 mt-1">Verifikasi dan setujui permintaan pencairan</p>
            </div>
            <a href="{{ route('admin.donations.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Request Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header Section -->
            <div class="p-6 bg-gradient-to-r from-blue-600 to-indigo-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Permintaan Pencairan</p>
                        <h2 class="text-3xl font-bold">{{ $donation->donation_code }}</h2>
                    </div>
                    <div class="text-right">
                        <p class="text-blue-100 text-sm mb-1">Status</p>
                        <span class="px-4 py-2 bg-yellow-500 text-white rounded-lg font-semibold">
                            <i class="fas fa-clock mr-1"></i>Menunggu Approval
                        </span>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="p-8 space-y-6">

                <!-- Ustadz Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-6 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl border-2 border-purple-200">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-tie text-purple-600 mr-2"></i>
                            Informasi Ustadz
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    {{ strtoupper(substr($donation->ustadz->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-lg text-gray-900">{{ $donation->ustadz->user->name }}</p>
                                    <p class="text-sm text-gray-600">{{ $donation->ustadz->user->email }}</p>
                                    <p class="text-sm text-gray-600">{{ $donation->ustadz->user->phone ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-purple-200">
                                <p class="text-xs text-gray-500">Kelas</p>
                                <p class="font-semibold text-gray-900">{{ $donation->ustadz->classModel->name ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-gradient-to-br from-pink-50 to-rose-50 rounded-xl border-2 border-pink-200">
                        <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-donate text-pink-600 mr-2"></i>
                            Sumber Donasi
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div
                                    class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white text-2xl font-bold mr-4">
                                    {{ strtoupper(substr($donation->wali->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-lg text-gray-900">{{ $donation->wali->user->name }}</p>
                                    <p class="text-sm text-gray-600">Wali Santri</p>
                                </div>
                            </div>
                            <div class="pt-3 border-t border-pink-200">
                                <p class="text-xs text-gray-500">Tanggal Donasi</p>
                                <p class="font-semibold text-gray-900">{{ $donation->created_at->format('d F Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount Details -->
                <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border-2 border-green-300">
                    <h3 class="font-bold text-gray-900 mb-4 text-lg flex items-center">
                        <i class="fas fa-money-bill-wave text-green-600 mr-2"></i>
                        Detail Nominal
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="p-4 bg-white rounded-xl text-center">
                            <p class="text-xs text-gray-500 mb-1">Total Donasi Awal</p>
                            <p class="text-2xl font-bold text-gray-900">
                                Rp {{ number_format($donation->amount, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-4 bg-white rounded-xl text-center">
                            <p class="text-xs text-gray-500 mb-1">Fee Pesantren (10%)</p>
                            <p class="text-2xl font-bold text-purple-600">
                                Rp {{ number_format($donation->pesantren_fee, 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="p-4 bg-white rounded-xl text-center">
                            <p class="text-xs text-gray-500 mb-1">Fee Platform (3%)</p>
                            <p class="text-2xl font-bold text-blue-600">
                                Rp {{ number_format($donation->platform_fee, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="p-6 bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl text-white text-center">
                        <p class="text-green-100 text-sm mb-2">Jumlah yang Akan Diserahkan ke Ustadz</p>
                        <p class="text-5xl font-bold">
                            Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                        </p>
                        <p class="text-green-100 text-xs mt-2">(87% dari total donasi)</p>
                    </div>
                </div>

                <!-- Request Info -->
                <div class="p-5 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl mr-3 mt-1"></i>
                        <div class="flex-1">
                            <h4 class="font-bold text-blue-900 mb-2">Informasi Permintaan</h4>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-blue-700">Tanggal Request:</p>
                                    <p class="font-semibold text-blue-900">
                                        {{ $donation->requested_at->format('d F Y, H:i') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-blue-700">Lama Menunggu:</p>
                                    <p class="font-semibold text-blue-900">
                                        {{ $donation->requested_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>

                            @if ($donation->request_notes)
                                <div class="mt-3 pt-3 border-t border-blue-200">
                                    <p class="text-blue-700 text-sm mb-1">Catatan dari Ustadz:</p>
                                    <p class="text-blue-900 font-medium">{{ $donation->request_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Approval Form -->
                <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}" id="approvalForm">
                    @csrf

                    <div class="space-y-4">
                        <!-- Notes -->
                        <div>
                            <label for="disbursement_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Pencairan (Opsional)
                            </label>
                            <textarea name="disbursement_notes" id="disbursement_notes" rows="3"
                                class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                                placeholder="Contoh: Dana diserahkan secara tunai pada tanggal... atau Nomor transaksi transfer..."></textarea>
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tambahkan informasi terkait penyerahan dana (metode, waktu, dll)
                            </p>
                        </div>

                        <!-- Confirmation Checklist -->
                        <div class="p-5 bg-yellow-50 border-2 border-yellow-300 rounded-xl">
                            <h4 class="font-bold text-gray-900 mb-3">
                                <i class="fas fa-clipboard-check text-yellow-600 mr-2"></i>
                                Checklist Sebelum Approval
                            </h4>

                            <div class="space-y-2">
                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" required
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                    <span class="ml-3 text-sm text-gray-700">
                                        Saya telah memverifikasi identitas ustadz yang bersangkutan
                                    </span>
                                </label>

                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" required
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                    <span class="ml-3 text-sm text-gray-700">
                                        Dana sebesar <strong>Rp
                                            {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</strong> siap
                                        diserahkan
                                    </span>
                                </label>

                                <label class="flex items-start cursor-pointer">
                                    <input type="checkbox" id="finalConfirm" required
                                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-0.5">
                                    <span class="ml-3 text-sm text-gray-700">
                                        Saya bertanggung jawab atas pencairan dana ini dan akan membuat bukti penyerahan
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('admin.donations.index') }}"
                                class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 font-semibold rounded-xl text-center hover:bg-gray-300 transition">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                class="flex-1 px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                                <i class="fas fa-check-circle mr-2"></i>Approve & Cairkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Instructions -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-book-open text-indigo-600 mr-2"></i>
                Panduan Pencairan
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                            1
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Verifikasi Identitas</h4>
                            <p class="text-sm text-gray-700">Pastikan ustadz yang datang sesuai dengan data di sistem</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                            2
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Hitung Dana</h4>
                            <p class="text-sm text-gray-700">Siapkan dana sesuai nominal yang tertera (net amount)</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                            3
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Serahkan Dana</h4>
                            <p class="text-sm text-gray-700">Serahkan dana secara tunai atau transfer dengan bukti</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-indigo-50 rounded-lg">
                    <div class="flex items-start">
                        <div
                            class="w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white font-bold mr-3 flex-shrink-0">
                            4
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-1">Approve di Sistem</h4>
                            <p class="text-sm text-gray-700">Klik approve setelah dana diserahkan dan dicatat</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Form confirmation
            document.getElementById('approvalForm')?.addEventListener('submit', function(e) {
                const amount = 'Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}';
                const ustadz = '{{ $donation->ustadz->user->name }}';

                if (!confirm(
                        `Approve pencairan dana sebesar ${amount} untuk ${ustadz}?\n\nPastikan dana sudah/akan diserahkan kepada ustadz yang bersangkutan.`
                        )) {
                    e.preventDefault();
                    return false;
                }
            });

            // Enable submit only when all checkboxes checked
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            const submitBtn = document.querySelector('button[type="submit"]');

            function checkAllChecked() {
                const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                submitBtn.disabled = !allChecked;

                if (allChecked) {
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                } else {
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            }

            checkboxes.forEach(cb => {
                cb.addEventListener('change', checkAllChecked);
            });

            // Initial check
            checkAllChecked();
        </script>
    @endpush
@endsection
