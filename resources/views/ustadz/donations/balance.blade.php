@extends('layouts.app-enhanced')

@section('title', 'Saldo Dana Apresiasi')

@section('content')
    <div class="space-y-6">
        <!-- Balance Card -->
        <div class="bg-gradient-to-r from-pink-500 to-rose-600 rounded-2xl p-8 text-white shadow-xl">
            <p class="text-pink-100 mb-2">Total Saldo Available</p>
            <h1 class="text-5xl font-bold mb-6">
                Rp {{ number_format($availableBalance, 0, ',', '.') }}
            </h1>

            <div class="flex gap-4">
                <a href="{{ route('ustadz.donations.withdraw') }}"
                    class="px-6 py-3 bg-white text-pink-600 font-bold rounded-xl hover:bg-pink-50">
                    <i class="fas fa-money-bill-wave mr-2"></i>Cairkan Dana
                </a>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl p-6">
                <p class="text-gray-500 mb-1">Total Diterima</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalReceived, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl p-6">
                <p class="text-gray-500 mb-1">Total Dicairkan</p>
                <p class="text-2xl font-bold">Rp {{ number_format($totalDisbursed, 0, ',', '.') }}</p>
            </div>
            <div class="bg-white rounded-xl p-6">
                <p class="text-gray-500 mb-1">Menunggu Approval</p>
                <p class="text-2xl font-bold">Rp {{ number_format($pendingWithdrawal, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- History -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-bold mb-4">Riwayat Donasi</h3>

            @foreach ($donations as $donation)
                <div class="flex justify-between items-center p-4 border-b">
                    <div>
                        <p class="font-semibold">{{ $donation->donation_code }}</p>
                        <p class="text-sm text-gray-500">
                            Dari: {{ $donation->wali->user->name }}
                        </p>
                        <p class="text-xs text-gray-400">{{ $donation->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xl font-bold text-green-600">
                            Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                        </p>
                        <span
                            class="text-xs px-2 py-1 rounded-full 
                    {{ $donation->status === 'disbursed'
                        ? 'bg-green-100 text-green-800'
                        : ($donation->status === 'available'
                            ? 'bg-blue-100 text-blue-800'
                            : 'bg-gray-100 text-gray-800') }}">
                            {{ ucfirst($donation->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
