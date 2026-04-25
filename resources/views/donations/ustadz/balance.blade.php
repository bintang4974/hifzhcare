@extends('layouts.app-enhanced')

@section('title', 'Saldo Dana Apresiasi')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Dana Apresiasi Ustadz</h1>
            <p class="text-gray-600">Kelola saldo dan riwayat dana apresiasi Anda</p>
        </div>

        <!-- Balance Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Available Balance -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Saldo Tersedia</p>
                        <p class="text-4xl font-bold">
                            Rp {{ number_format($stats['available_balance'], 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-wallet text-4xl opacity-20"></i>
                </div>
                <p class="text-blue-100 text-xs">Siap untuk dicairkan</p>
            </div>

            <!-- Pending Withdrawal -->
            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Menunggu Approval</p>
                        <p class="text-4xl font-bold">
                            Rp {{ number_format($stats['pending_withdrawal'], 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-hourglass-end text-4xl opacity-20"></i>
                </div>
                <p class="text-yellow-100 text-xs">Sudah di-request ke admin</p>
            </div>

            <!-- Total Received -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Diterima</p>
                        <p class="text-4xl font-bold">
                            Rp {{ number_format($stats['total_received'], 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-inbox text-4xl opacity-20"></i>
                </div>
                <p class="text-purple-100 text-xs">Dari {{ $stats['donation_count'] }} donasi</p>
            </div>

            <!-- Total Disbursed -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Sudah Dicairkan</p>
                        <p class="text-4xl font-bold">
                            Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}
                        </p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-20"></i>
                </div>
                <p class="text-green-100 text-xs">Dana telah diterima</p>
            </div>
        </div>

        <!-- Action Button -->
        @if ($stats['available_balance'] >= 50000)
            <div class="flex gap-3">
                <a href="{{ route('ustadz.donations.withdraw') }}"
                    class="flex-1 px-8 py-4 bg-gradient-to-r from-pink-500 to-rose-600 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-center">
                    <i class="fas fa-money-bill-wave mr-2"></i>Cairkan Dana (Rp {{ number_format($stats['available_balance'], 0, ',', '.') }})
                </a>
            </div>
        @endif

        <!-- Donasi Siap Dicairkan -->
        @php
            $availableDonations = $donations->where('status', 'available')->values();
        @endphp
        @if ($availableDonations->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-coins text-2xl text-blue-500"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Donasi Siap Dicairkan</h2>
                    <span class="ml-auto bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $availableDonations->count() }} donasi
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach ($availableDonations as $donation)
                        <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-blue-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-gift text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $donation->donation_code }}</p>
                                    <p class="text-sm text-gray-600">Dari {{ $donation->wali->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $donation->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-blue-600">Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</p>
                                <span class="inline-block px-3 py-1 bg-blue-200 text-blue-800 rounded-full text-xs font-semibold mt-1">
                                    <i class="fas fa-check mr-1"></i>Ready
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Permintaan Pencairan Menunggu -->
        @php
            $requestedDonations = $donations->where('status', 'requested')->values();
        @endphp
        @if ($requestedDonations->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500">
                <div class="flex items-center gap-3 mb-6">
                    <i class="fas fa-clock text-2xl text-yellow-500"></i>
                    <h2 class="text-2xl font-bold text-gray-900">Menunggu Persetujuan Admin</h2>
                    <span class="ml-auto bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $requestedDonations->count() }} menunggu
                    </span>
                </div>

                <div class="space-y-3">
                    @foreach ($requestedDonations as $donation)
                        <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-yellow-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-history text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $donation->donation_code }}</p>
                                    <p class="text-sm text-gray-600">Dari {{ $donation->wali->user->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        Di-request {{ $donation->requested_at->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-yellow-600">Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</p>
                                <span class="inline-block px-3 py-1 bg-yellow-200 text-yellow-800 rounded-full text-xs font-semibold mt-1">
                                    <i class="fas fa-hourglass-end mr-1"></i>Pending
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Donasi Sudah Dicairkan -->
        @php
            $disbursedDonations = $donations->where('status', 'disbursed')->values();
        @endphp
        <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-check-circle text-2xl text-green-500"></i>
                <h2 class="text-2xl font-bold text-gray-900">Donasi Sudah Dicairkan</h2>
                <span class="ml-auto bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                    {{ $disbursedDonations->count() }} dicairkan
                </span>
            </div>

            @if ($disbursedDonations->count() > 0)
                <div class="space-y-3">
                    @foreach ($disbursedDonations as $donation)
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-green-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-money-bill text-green-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $donation->donation_code }}</p>
                                    <p class="text-sm text-gray-600">Dari {{ $donation->wali->user->name }}</p>
                                    <p class="text-xs text-gray-500">
                                        Dicairkan {{ $donation->disbursed_at->format('d M Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-green-600">Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</p>
                                <span class="inline-block px-3 py-1 bg-green-200 text-green-800 rounded-full text-xs font-semibold mt-1">
                                    <i class="fas fa-check-circle mr-1"></i>Selesai
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i class="fas fa-inbox text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Belum ada donasi yang dicairkan</p>
                </div>
            @endif
        </div>

        <!-- Other Status Donasi -->
        @php
            $otherDonations = $donations->whereIn('status', ['verified', 'transferred'])->values();
        @endphp
        @if ($otherDonations->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6 border-l-4 border-purple-500">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    <i class="fas fa-hourglass-half text-purple-500 mr-2"></i>Dalam Proses
                </h2>

                <div class="space-y-3">
                    @foreach ($otherDonations as $donation)
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="w-12 h-12 bg-purple-200 rounded-full flex items-center justify-center">
                                    <i class="fas fa-exchange-alt text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $donation->donation_code }}</p>
                                    <p class="text-sm text-gray-600">Dari {{ $donation->wali->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $donation->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-xl font-bold text-purple-600">Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}</p>
                                @if ($donation->status === 'verified')
                                    <span class="inline-block px-3 py-1 bg-purple-200 text-purple-800 rounded-full text-xs font-semibold mt-1">
                                        <i class="fas fa-check mr-1"></i>Verified
                                    </span>
                                @else
                                    <span class="inline-block px-3 py-1 bg-indigo-200 text-indigo-800 rounded-full text-xs font-semibold mt-1">
                                        <i class="fas fa-exchange-alt mr-1"></i>Transfer
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Empty State -->
        @if ($donations->count() === 0)
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-heart-broken text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Belum Ada Donasi</h3>
                <p class="text-gray-600">Anda belum menerima donasi apapun. Nantikan donasi dari wali santri.</p>
            </div>
        @endif
    </div>
@endsection
