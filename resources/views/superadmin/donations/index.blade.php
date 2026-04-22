@extends('layouts.app-enhanced')

@section('title', 'Verifikasi Donasi')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold">Verifikasi Donasi</h1>

        <!-- Pending Donations -->
        @foreach ($pendingDonations as $donation)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h3 class="text-xl font-bold">{{ $donation->donation_code }}</h3>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">
                                Pending
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Dari Wali</p>
                                <p class="font-semibold">{{ $donation->wali->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Untuk Ustadz</p>
                                <p class="font-semibold">{{ $donation->ustadz->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Donasi</p>
                                <p class="font-bold text-2xl text-pink-600">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Tanggal</p>
                                <p class="font-semibold">{{ $donation->created_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Fee Breakdown -->
                        <div class="p-4 bg-gray-50 rounded-lg mb-4">
                            <div class="grid grid-cols-3 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500">Platform (3%)</p>
                                    <p class="font-bold text-blue-600">Rp
                                        {{ number_format($donation->platform_fee, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Pesantren (10%)</p>
                                    <p class="font-bold text-purple-600">Rp
                                        {{ number_format($donation->pesantren_fee, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500">Ustadz Net (87%)</p>
                                    <p class="font-bold text-green-600">Rp
                                        {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof -->
                        <div class="mb-4">
                            <p class="text-sm text-gray-500 mb-2">Bukti Transfer:</p>
                            <img src="{{ Storage::url($donation->payment_proof) }}" alt="Proof"
                                class="w-64 h-auto rounded-lg border">
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="ml-6 flex flex-col gap-2">
                        <form method="POST" action="{{ route('superadmin.donations.verify', $donation->id) }}">
                            @csrf
                            <button type="submit" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-check mr-2"></i>Approve
                            </button>
                        </form>

                        <button onclick="rejectDonation({{ $donation->id }})"
                            class="px-6 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700">
                            <i class="fas fa-times mr-2"></i>Reject
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
