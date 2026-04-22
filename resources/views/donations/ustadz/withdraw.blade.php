@extends('layouts.app-enhanced')

@section('title', 'Cairkan Dana')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Cairkan Dana Apresiasi</h1>
                <p class="text-gray-600 mt-1">Ajukan pencairan dana ke admin pondok</p>
            </div>
            <a href="{{ route('ustadz.donations.balance') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Available Balance Card -->
        <div class="bg-gradient-to-br from-green-500 via-emerald-600 to-teal-600 rounded-3xl p-10 text-white shadow-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-2">Saldo yang Dapat Dicairkan</p>
                    <h2 class="text-6xl font-bold mb-2">
                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                    </h2>
                    <p class="text-green-100 flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        Siap untuk ditarik
                    </p>
                </div>
                <div class="w-28 h-28 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-6xl"></i>
                </div>
            </div>
        </div>

        <!-- Withdrawal Form -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form method="POST" action="{{ route('ustadz.donations.request-withdrawal') }}" id="withdrawalForm">
                @csrf

                <div class="space-y-6">
                    <!-- Info Box -->
                    <div class="p-5 bg-blue-50 border-l-4 border-blue-500 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-600 text-2xl mr-4"></i>
                            <div>
                                <h3 class="font-bold text-blue-900 mb-2">Informasi Pencairan</h3>
                                <ul class="text-sm text-blue-800 space-y-1">
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Minimal pencairan: <strong>Rp
                                                {{ number_format($minimumWithdrawal, 0, ',', '.') }}</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Dana akan dicairkan melalui admin pondok</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Proses verifikasi 1-3 hari kerja</span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-check text-blue-600 mr-2 mt-0.5"></i>
                                        <span>Setelah disetujui, ambil dana ke kantor admin pondok</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Withdrawal Amount Display -->
                    <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl">
                        <h3 class="font-bold text-gray-900 mb-4 text-lg">Detail Pencairan</h3>

                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 bg-white rounded-xl">
                                <span class="text-gray-700 font-medium">Jumlah yang Akan Ditarik</span>
                                <span class="text-3xl font-bold text-green-600">
                                    Rp {{ number_format($availableBalance, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 bg-white rounded-lg text-center">
                                    <p class="text-xs text-gray-500 mb-1">Minimal Pencairan</p>
                                    <p class="font-bold text-gray-900">
                                        Rp {{ number_format($minimumWithdrawal, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="p-3 bg-white rounded-lg text-center">
                                    <p class="text-xs text-gray-500 mb-1">Status</p>
                                    @if ($availableBalance >= $minimumWithdrawal)
                                        <p class="font-bold text-green-600">
                                            <i class="fas fa-check-circle mr-1"></i>Memenuhi
                                        </p>
                                    @else
                                        <p class="font-bold text-red-600">
                                            <i class="fas fa-times-circle mr-1"></i>Kurang
                                        </p>
                                    @endif
                                </div>
                            </div>

                            @if ($availableBalance < $minimumWithdrawal)
                                <div class="p-3 bg-red-50 border border-red-200 rounded-lg">
                                    <p class="text-sm text-red-800 text-center">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>
                                        Saldo Anda kurang <strong>Rp
                                            {{ number_format($minimumWithdrawal - $availableBalance, 0, ',', '.') }}</strong>
                                        untuk mencapai minimal pencairan
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Request Notes -->
                    <div>
                        <label for="request_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan untuk Admin Pondok (Opsional)
                        </label>
                        <textarea name="request_notes" id="request_notes" rows="4"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                            placeholder="Contoh: Mohon pencairan untuk keperluan... atau Dapat dihubungi di nomor..."></textarea>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-lightbulb mr-1"></i>
                            Tambahkan catatan jika ada keperluan mendesak atau info kontak
                        </p>
                    </div>

                    <!-- Confirmation Checkbox -->
                    <div class="p-5 bg-yellow-50 border-2 border-yellow-300 rounded-xl">
                        <label class="flex items-start cursor-pointer">
                            <input type="checkbox" id="confirmCheck" required
                                class="w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500 mt-0.5">
                            <div class="ml-3">
                                <span class="text-sm font-semibold text-gray-900">Konfirmasi Pencairan</span>
                                <p class="text-xs text-gray-700 mt-1">
                                    Saya memahami bahwa dana akan dicairkan melalui admin pondok dan
                                    saya bersedia mengambil dana secara langsung setelah permintaan disetujui.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    @if ($availableBalance >= $minimumWithdrawal)
                        <div class="flex gap-4 pt-4">
                            <a href="{{ route('ustadz.donations.balance') }}"
                                class="flex-1 px-6 py-4 bg-gray-200 text-gray-700 font-semibold rounded-xl text-center hover:bg-gray-300 transition">
                                <i class="fas fa-times mr-2"></i>Batal
                            </a>
                            <button type="submit"
                                class="flex-1 px-6 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1"
                                id="submitBtn" disabled>
                                <i class="fas fa-paper-plane mr-2"></i>Ajukan Pencairan
                            </button>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <button type="button" disabled
                                class="px-8 py-4 bg-gray-300 text-gray-500 font-bold rounded-xl cursor-not-allowed">
                                <i class="fas fa-lock mr-2"></i>Saldo Belum Mencukupi
                            </button>
                            <p class="text-sm text-gray-600 mt-3">
                                Minimal pencairan adalah Rp {{ number_format($minimumWithdrawal, 0, ',', '.') }}
                            </p>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Donation Source Breakdown -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-list text-blue-600 mr-2"></i>
                Rincian Sumber Dana
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Kode Donasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Dari Wali</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @php
                            $availableDonations = App\Models\Donation::where(
                                'ustadz_id',
                                auth()->user()->ustadzProfile->id,
                            )
                                ->where('status', 'available')
                                ->with('wali.user')
                                ->get();
                        @endphp

                        @forelse($availableDonations as $donation)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <span class="font-mono text-sm font-semibold text-blue-600">
                                        {{ $donation->donation_code }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center text-white text-xs font-bold mr-2">
                                            {{ strtoupper(substr($donation->wali->user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900">
                                            {{ $donation->wali->user->name }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">
                                    {{ $donation->transferred_at->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-lg font-bold text-green-600">
                                        Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-inbox text-4xl mb-2"></i>
                                    <p>Tidak ada dana available</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if ($availableDonations->count() > 0)
                        <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                            <tr>
                                <td colspan="3" class="px-4 py-4 text-right font-bold text-gray-900">
                                    Total yang Akan Dicairkan:
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="text-2xl font-bold text-green-600">
                                        Rp {{ number_format($availableBalance, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                Pertanyaan Umum
            </h3>

            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-chevron-right text-purple-500 mr-2"></i>
                        Berapa lama proses pencairan?
                    </h4>
                    <p class="text-sm text-gray-700 ml-6">
                        Proses verifikasi dan persetujuan biasanya memakan waktu 1-3 hari kerja.
                        Setelah disetujui, Anda dapat mengambil dana langsung ke kantor admin pondok.
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-chevron-right text-purple-500 mr-2"></i>
                        Bagaimana cara mengambil dana?
                    </h4>
                    <p class="text-sm text-gray-700 ml-6">
                        Setelah permintaan disetujui, silahkan datang ke kantor admin pondok dengan membawa
                        identitas (KTP/Kartu Ustadz). Dana akan diberikan secara tunai atau transfer sesuai kesepakatan.
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-chevron-right text-purple-500 mr-2"></i>
                        Bisakah membatalkan permintaan?
                    </h4>
                    <p class="text-sm text-gray-700 ml-6">
                        Permintaan yang masih dalam status "Menunggu Approval" dapat dibatalkan dengan
                        menghubungi admin pondok secara langsung.
                    </p>
                </div>

                <div class="p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-2">
                        <i class="fas fa-chevron-right text-purple-500 mr-2"></i>
                        Apakah ada biaya admin untuk pencairan?
                    </h4>
                    <p class="text-sm text-gray-700 ml-6">
                        Tidak ada biaya admin. Jumlah yang akan Anda terima adalah jumlah yang tertera
                        di atas (sudah dipotong 3% platform dan 10% pesantren dari donasi asli).
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Enable submit button when checkbox is checked
            document.getElementById('confirmCheck')?.addEventListener('change', function() {
                document.getElementById('submitBtn').disabled = !this.checked;
            });

            // Form confirmation
            document.getElementById('withdrawalForm')?.addEventListener('submit', function(e) {
                if (!confirm(
                        'Ajukan pencairan dana sebesar Rp {{ number_format($availableBalance, 0, ',', '.') }}?\n\nSetelah diajukan, silahkan tunggu approval dari admin pondok.'
                        )) {
                    e.preventDefault();
                    return false;
                }
            });

            // Show success animation on submit
            @if (session('success'))
                setTimeout(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#10B981'
                    });
                }, 100);
            @endif

            // Show error if balance insufficient
            @if ($availableBalance < $minimumWithdrawal)
                console.log('Saldo tidak mencukupi untuk pencairan');
            @endif
        </script>
    @endpush

    @push('styles')
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .space-y-6>* {
                animation: fadeInUp 0.5s ease-out;
            }

            .space-y-6>*:nth-child(1) {
                animation-delay: 0.1s;
            }

            .space-y-6>*:nth-child(2) {
                animation-delay: 0.2s;
            }

            .space-y-6>*:nth-child(3) {
                animation-delay: 0.3s;
            }

            .space-y-6>*:nth-child(4) {
                animation-delay: 0.4s;
            }

            .space-y-6>*:nth-child(5) {
                animation-delay: 0.5s;
            }

            #submitBtn:not(:disabled):hover {
                transform: translateY(-2px);
                box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3);
            }
        </style>
    @endpush
@endsection
