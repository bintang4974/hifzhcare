@extends('layouts.app-enhanced')

@section('title', 'Detail Donasi')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Detail Donasi</h1>
            <a href="{{ route('donations.index') }}"
                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg font-semibold">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Donation Card -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="p-6 bg-gradient-to-r from-pink-500 to-rose-600 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-pink-100 text-sm mb-1">Kode Donasi</p>
                        <h2 class="text-3xl font-bold">{{ $donation->donation_code }}</h2>
                    </div>
                    @if ($donation->status === 'pending')
                        <span class="px-4 py-2 bg-yellow-500 text-white rounded-lg font-semibold">
                            Menunggu Verifikasi
                        </span>
                    @elseif($donation->status === 'verified')
                        <span class="px-4 py-2 bg-blue-500 text-white rounded-lg font-semibold">
                            Terverifikasi
                        </span>
                    @elseif(in_array($donation->status, ['transferred', 'available']))
                        <span class="px-4 py-2 bg-green-500 text-white rounded-lg font-semibold">
                            Diterima Ustadz
                        </span>
                    @elseif($donation->status === 'requested')
                        <span class="px-4 py-2 bg-purple-500 text-white rounded-lg font-semibold">
                            Dalam Proses Pencairan
                        </span>
                    @elseif($donation->status === 'disbursed')
                        <span class="px-4 py-2 bg-indigo-500 text-white rounded-lg font-semibold">
                            Sudah Dicairkan
                        </span>
                    @else
                        <span class="px-4 py-2 bg-red-500 text-white rounded-lg font-semibold">
                            Ditolak
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Main Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Informasi Donasi</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Untuk Ustadz</span>
                                <span class="font-semibold">{{ $donation->ustadz->user->name }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Tanggal Donasi</span>
                                <span class="font-semibold">{{ $donation->created_at->format('d F Y, H:i') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Metode Pembayaran</span>
                                <span class="font-semibold">{{ ucfirst($donation->payment_method) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-bold text-gray-900 mb-4">Rincian Nominal</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between py-2 border-b">
                                <span class="text-gray-600">Total Donasi</span>
                                <span class="text-xl font-bold text-pink-600">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between py-2 border-b text-sm">
                                <span class="text-gray-600">Biaya Platform (3%)</span>
                                <span class="text-gray-700">Rp
                                    {{ number_format($donation->platform_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-2 border-b text-sm">
                                <span class="text-gray-600">Biaya Pesantren (10%)</span>
                                <span class="text-gray-700">Rp
                                    {{ number_format($donation->pesantren_fee, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between py-3 bg-green-50 px-3 rounded-lg">
                                <span class="font-semibold text-green-800">Diterima Ustadz</span>
                                <span class="text-xl font-bold text-green-600">
                                    Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Proof -->
                <div>
                    <h3 class="font-bold text-gray-900 mb-3">Bukti Transfer</h3>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <img src="{{ Storage::url($donation->payment_proof) }}" alt="Bukti Transfer"
                            class="max-w-md mx-auto rounded-lg shadow-lg">
                    </div>
                </div>

                <!-- Notes -->
                @if ($donation->notes)
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Catatan</h3>
                        <div class="p-4 bg-gray-50 rounded-lg">
                            <p class="text-gray-700">{{ $donation->notes }}</p>
                        </div>
                    </div>
                @endif

                <!-- Verification Info -->
                @if ($donation->isVerified() || $donation->isRejected())
                    <div>
                        <h3 class="font-bold text-gray-900 mb-3">Informasi Verifikasi</h3>
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-start">
                                <i
                                    class="fas fa-{{ $donation->isVerified() ? 'check-circle' : 'times-circle' }} text-{{ $donation->isVerified() ? 'blue' : 'red' }}-600 text-2xl mr-3"></i>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 mb-1">
                                        {{ $donation->isVerified() ? 'Diverifikasi' : 'Ditolak' }} oleh
                                        {{ $donation->verifiedBy->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ $donation->verified_at->format('d F Y, H:i') }}
                                    </p>
                                    @if ($donation->verification_notes)
                                        <p class="text-sm text-gray-700 mt-2">
                                            <strong>Catatan:</strong> {{ $donation->verification_notes }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
