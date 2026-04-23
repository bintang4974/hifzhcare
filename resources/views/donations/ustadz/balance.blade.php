@extends('layouts.app-enhanced')

@section('title', 'Saldo Dana Apresiasi')

@section('content')
    <div class="space-y-6">
        <!-- Balance Card -->
        <div class="bg-gradient-to-br from-pink-500 via-rose-600 to-orange-500 rounded-3xl p-10 text-white shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-pink-100 text-sm mb-2">Saldo Available</p>
                    <h1 class="text-6xl font-bold mb-1">
                        Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}
                    </h1>
                    <p class="text-pink-100">Siap dicairkan</p>
                </div>
                <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-5xl"></i>
                </div>
            </div>

            @if ($stats['available_balance'] >= 50000)
                <a href="{{ route('ustadz.donations.withdraw') }}"
                    class="inline-block px-8 py-4 bg-white text-pink-600 font-bold rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
                    <i class="fas fa-money-bill-wave mr-2"></i>Cairkan Dana
                </a>
            @else
                <div class="p-4 bg-white bg-opacity-20 rounded-xl">
                    <p class="text-sm">
                        <i class="fas fa-info-circle mr-2"></i>
                        Minimal pencairan Rp 50.000
                    </p>
                </div>
            @endif
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <p class="text-gray-500 text-sm mb-2">Total Diterima</p>
                <p class="text-3xl font-bold text-blue-600">
                    Rp {{ number_format($stats['total_received'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Dari {{ $stats['donation_count'] }} donasi</p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <p class="text-gray-500 text-sm mb-2">Total Dicairkan</p>
                <p class="text-3xl font-bold text-green-600">
                    Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}
                </p>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6">
                <p class="text-gray-500 text-sm mb-2">Pending Withdrawal</p>
                <p class="text-3xl font-bold text-yellow-600">
                    Rp {{ number_format($stats['pending_withdrawal'], 0, ',', '.') }}
                </p>
                <p class="text-xs text-gray-400 mt-1">Menunggu approval</p>
            </div>
        </div>

        <!-- History -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-2xl font-bold mb-6">Riwayat Donasi</h2>

            @forelse($donations as $donation)
                <div class="flex items-center justify-between py-4 border-b hover:bg-gray-50 transition px-4 rounded-lg">
                    <div class="flex items-center gap-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-pink-500 to-rose-600 rounded-full flex items-center justify-center text-white font-bold">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $donation->donation_code }}</p>
                            <p class="text-sm text-gray-600">
                                Dari: {{ $donation->wali->user->name }}
                            </p>
                            <p class="text-xs text-gray-400">
                                {{ $donation->created_at->format('d M Y') }}
                            </p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-600">
                            Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                        </p>
                        <span
                            class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                    {{ $donation->status === 'disbursed'
                        ? 'bg-green-100 text-green-800'
                        : ($donation->status === 'available'
                            ? 'bg-blue-100 text-blue-800'
                            : ($donation->status === 'transferred'
                                ? 'bg-indigo-100 text-indigo-800'
                                : ($donation->status === 'verified'
                                    ? 'bg-purple-100 text-purple-800'
                                    : ($donation->status === 'requested'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-gray-100 text-gray-800')))) }}">
                            @if ($donation->status === 'disbursed')
                                <i class="fas fa-check-circle mr-1"></i>Sudah Dicairkan
                            @elseif($donation->status === 'available')
                                <i class="fas fa-wallet mr-1"></i>Available
                            @elseif($donation->status === 'transferred')
                                <i class="fas fa-exchange-alt mr-1"></i>Transfer Diterima
                            @elseif($donation->status === 'verified')
                                <i class="fas fa-check mr-1"></i>Terverifikasi
                            @elseif($donation->status === 'requested')
                                <i class="fas fa-clock mr-1"></i>Menunggu Approval
                            @else
                                {{ ucfirst($donation->status) }}
                            @endif
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-heart-broken text-6xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada donasi</p>
                </div>
            @endforelse
        </div>

        {{ $donations->links() }}
    </div>
@endsection
