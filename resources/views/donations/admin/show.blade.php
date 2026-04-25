@extends('layouts.app-enhanced')

@section('title', 'Detail Donasi')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Donasi</h1>
                <p class="text-gray-600 mt-1">{{ $donation->donation_code }}</p>
            </div>
            <a href="{{ route('admin.donations.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Status Badge and Timeline -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $donation->donation_code }}</h2>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-lg font-semibold 
                            {{ $donation->status === 'pending'
                                ? 'bg-red-100 text-red-800'
                                : ($donation->status === 'verified'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($donation->status === 'transferred'
                                        ? 'bg-indigo-100 text-indigo-800'
                                        : ($donation->status === 'requested'
                                            ? 'bg-yellow-100 text-yellow-800'
                                            : ($donation->status === 'disbursed'
                                                ? 'bg-green-100 text-green-800'
                                                : 'bg-gray-100 text-gray-800')))) }}">
                            <i class="mr-1 fas fa-circle-notch"></i>
                            {{ match($donation->status) {
                                'pending' => 'Menunggu Verifikasi (SuperAdmin)',
                                'verified' => 'Terverifikasi - Menunggu Transfer',
                                'transferred' => 'Sudah Ditransfer ke Pesantren',
                                'available' => 'Tersedia untuk Ustadz',
                                'requested' => 'Ustadz Request Pencairan',
                                'disbursed' => 'Sudah Dicairkan',
                                'rejected' => 'Ditolak',
                                default => ucfirst($donation->status)
                            } }}
                        </span>
                        <span class="text-sm text-gray-500">
                            Update: {{ $donation->updated_at->format('d M Y, H:i') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white text-sm">
                        <i class="fas fa-check"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Donasi Dibuat</p>
                        <p class="text-sm text-gray-600">{{ $donation->created_at->format('d F Y, H:i') }}</p>
                    </div>
                </div>

                @if ($donation->verified_at)
                    <div class="flex items-center gap-3 ml-4 border-l-2 border-blue-300 pl-3">
                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Terverifikasi</p>
                            <p class="text-sm text-gray-600">{{ $donation->verified_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif

                @if ($donation->transferred_at)
                    <div class="flex items-center gap-3 ml-4 border-l-2 border-blue-300 pl-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Transfer Diterima Pesantren</p>
                            <p class="text-sm text-gray-600">{{ $donation->transferred_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif

                @if ($donation->requested_at)
                    <div class="flex items-center gap-3 ml-4 border-l-2 border-blue-300 pl-3">
                        <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center text-white text-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Ustadz Request Pencairan</p>
                            <p class="text-sm text-gray-600">{{ $donation->requested_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif

                @if ($donation->disbursed_at)
                    <div class="flex items-center gap-3 ml-4 border-l-2 border-blue-300 pl-3">
                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Pencairan Disetujui & Diserahkan</p>
                            <p class="text-sm text-gray-600">{{ $donation->disbursed_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column - People Info -->
            <div class="space-y-6">
                <!-- Ustadz Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                        Ustadz Penerima
                    </h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($donation->ustadz->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $donation->ustadz->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $donation->ustadz->user->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 pt-4 border-t">
                        <div>
                            <p class="text-xs text-gray-600">Telepon</p>
                            <p class="font-semibold text-gray-900">{{ $donation->ustadz->user->phone ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">Kelas</p>
                            <p class="font-semibold text-gray-900">{{ $donation->ustadz->activeClassesRelation->first()?->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Donor Info -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-heart text-pink-600 mr-2"></i>
                        Wali Santri (Donor)
                    </h3>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                            {{ strtoupper(substr($donation->wali->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-gray-900">{{ $donation->wali->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $donation->wali->user->email }}</p>
                        </div>
                    </div>
                    <div class="space-y-2 pt-4 border-t">
                        <div>
                            <p class="text-xs text-gray-600">Telepon</p>
                            <p class="font-semibold text-gray-900">{{ $donation->wali->user->phone ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Financial Details & Actions -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Financial Details -->
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                    <!-- Header -->
                    <div class="p-6 bg-gradient-to-r from-green-600 to-emerald-600 text-white">
                        <h3 class="font-bold text-xl flex items-center">
                            <i class="fas fa-money-bill-wave mr-2"></i>
                            Detail Nominal
                        </h3>
                    </div>

                    <!-- Content -->
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-4 bg-blue-50 rounded-xl">
                                <p class="text-xs text-blue-600 font-semibold mb-1">DONASI AWAL</p>
                                <p class="text-2xl font-bold text-blue-900">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="p-4 bg-purple-50 rounded-xl">
                                <p class="text-xs text-purple-600 font-semibold mb-1">FEE PESANTREN ({{ $donation->pesantren->donationSettings->pesantren_fee_percentage ?? '10' }}%)</p>
                                <p class="text-2xl font-bold text-purple-900">
                                    Rp {{ number_format($donation->pesantren_fee, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="p-4 bg-indigo-50 rounded-xl">
                                <p class="text-xs text-indigo-600 font-semibold mb-1">FEE PLATFORM ({{ $donation->pesantren->donationSettings->platform_fee_percentage ?? '3' }}%)</p>
                                <p class="text-2xl font-bold text-indigo-900">
                                    Rp {{ number_format($donation->platform_fee, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>

                        <!-- Net Amount Box -->
                        <div class="p-6 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-300">
                            <p class="text-sm text-green-700 mb-1 font-semibold">NET AMOUNT (Untuk Ustadz)</p>
                            <p class="text-4xl font-bold text-green-900">
                                Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-green-700 mt-2">
                                {{ round(($donation->ustadz_net_amount / $donation->amount) * 100) }}% dari total donasi
                            </p>
                        </div>

                        <!-- Transfer Details -->
                        <div class="p-4 bg-amber-50 border-l-4 border-amber-500 rounded">
                            <p class="text-xs text-amber-700 font-semibold mb-1">Transfer Ke Pesantren</p>
                            <p class="text-lg font-bold text-amber-900">
                                Rp {{ number_format($donation->transfer_to_pesantren, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-amber-700 mt-1">(Fee pesantren + Net amount)</p>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-900 mb-4">
                        <i class="fas fa-credit-card text-orange-600 mr-2"></i>
                        Informasi Pembayaran
                    </h3>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Metode Pembayaran</p>
                            <p class="font-semibold text-gray-900">{{ ucfirst($donation->payment_method) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600 mb-1">Tanggal Donasi</p>
                            <p class="font-semibold text-gray-900">{{ $donation->created_at->format('d F Y') }}</p>
                        </div>
                    </div>

                    @if ($donation->payment_proof)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-xs text-gray-600 mb-2">Bukti Pembayaran</p>
                            <div class="rounded-lg overflow-hidden bg-gray-100">
                                <img src="{{ Storage::url($donation->payment_proof) }}" alt="Payment Proof" class="w-full h-48 object-cover">
                            </div>
                        </div>
                    @endif

                    @if ($donation->notes)
                        <div class="mt-4 pt-4 border-t">
                            <p class="text-xs text-gray-600 mb-1">Catatan Donatur</p>
                            <p class="text-gray-900">{{ $donation->notes }}</p>
                        </div>
                    @endif
                </div>

                <!-- Admin Notes -->
                @if ($donation->status === 'requested' && $donation->request_notes)
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-sticky-note text-yellow-600 mr-2"></i>
                            Catatan dari Ustadz
                        </h3>
                        <p class="text-gray-700">{{ $donation->request_notes }}</p>
                    </div>
                @endif

                @if ($donation->status === 'disbursed' && $donation->disbursement_notes)
                    <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
                        <h3 class="font-bold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-2"></i>
                            Catatan Pencairan
                        </h3>
                        <p class="text-gray-700">{{ $donation->disbursement_notes }}</p>
                        <div class="mt-4 pt-4 border-t text-sm text-gray-600">
                            Disetujui oleh: <strong>{{ $donation->disbursedBy->name ?? '-' }}</strong>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Buttons -->
        @if ($donation->status === 'requested')
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-900 mb-4">
                    <i class="fas fa-tasks text-blue-600 mr-2"></i>
                    Aksi Pencairan
                </h3>

                <form method="POST" action="{{ route('admin.donations.approve', $donation->id) }}" id="approvalForm">
                    @csrf

                    <div class="space-y-4">
                        <!-- Notes -->
                        <div>
                            <label for="disbursement_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Catatan Pencairan (Opsional)
                            </label>
                            <textarea name="disbursement_notes" id="disbursement_notes" rows="3"
                                class="w-full rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-3"
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
        @elseif ($donation->status === 'pending')
            <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6">
                <p class="text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    Donasi ini masih menunggu verifikasi dari SuperAdmin. Hubungi SuperAdmin untuk melakukan verifikasi.
                </p>
            </div>
        @elseif ($donation->status === 'verified' || $donation->status === 'transferred' || $donation->status === 'available')
            <div class="bg-indigo-50 border-l-4 border-indigo-500 rounded-lg p-6">
                <p class="text-indigo-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    Donasi ini sedang dalam proses transfer ke pesantren atau menunggu ustadz mengajukan permintaan pencairan.
                </p>
            </div>
        @elseif ($donation->status === 'disbursed')
            <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6">
                <p class="text-green-900">
                    <i class="fas fa-check-circle mr-2"></i>
                    Donasi ini telah disetujui dan dicairkan kepada ustadz pada {{ $donation->disbursed_at->format('d F Y, H:i') }}.
                </p>
            </div>
        @endif
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
                }
            });
        </script>
    @endpush
@endsection
