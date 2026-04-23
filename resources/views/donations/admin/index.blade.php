@extends('layouts.app-enhanced')

@section('title', 'Pencairan Dana')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold">Pencairan Dana Ustadz</h1>
                <p class="text-gray-600">Kelola permintaan pencairan dana apresiasi</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-4 gap-4">
            <div class="bg-red-50 rounded-xl p-6 border-2 border-red-200">
                <p class="text-red-600 text-sm mb-1">Perlu Verifikasi</p>
                <p class="text-3xl font-bold text-red-700">{{ $stats['pending_count'] }}</p>
            </div>
            <div class="bg-yellow-50 rounded-xl p-6 border-2 border-yellow-200">
                <p class="text-yellow-600 text-sm mb-1">Menunggu Pencairan</p>
                <p class="text-3xl font-bold text-yellow-700">{{ $stats['withdrawal_count'] }}</p>
            </div>
            <div class="bg-green-50 rounded-xl p-6 border-2 border-green-200">
                <p class="text-green-600 text-sm mb-1">Total Dicairkan</p>
                <p class="text-2xl font-bold text-green-700">
                    Rp {{ number_format($stats['total_disbursed'], 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-purple-50 rounded-xl p-6 border-2 border-purple-200">
                <p class="text-purple-600 text-sm mb-1">Saldo Pesantren</p>
                <p class="text-2xl font-bold text-purple-700">
                    Rp {{ number_format($stats['pesantren_balance'], 0, ',', '.') }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">💰 Permintaan Pencairan Dana</h2>

            @forelse($pendingRequests as $donation)
                <div class="border-2 border-gray-200 rounded-xl p-5 mb-4 hover:border-blue-300 transition">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-3">
                                <h3 class="text-lg font-bold">{{ $donation->ustadz->user->name }}</h3>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">
                                    Menunggu Approval
                                </span>
                            </div>

                            <div class="grid grid-cols-3 gap-4 mb-3">
                                <div>
                                    <p class="text-xs text-gray-500">Kode Donasi</p>
                                    <p class="font-semibold">{{ $donation->donation_code }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Dari Wali</p>
                                    <p class="font-semibold">{{ $donation->wali->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500">Tanggal Request</p>
                                    <p class="font-semibold">{{ $donation->requested_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-6">
                                <div>
                                    <p class="text-sm text-gray-600">Jumlah Pencairan</p>
                                    <p class="text-3xl font-bold text-green-600">
                                        Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>

                            @if ($donation->request_notes)
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-900">
                                        <strong>Catatan:</strong> {{ $donation->request_notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <div class="ml-6">
                            <a href="{{ route('admin.donations.show', $donation->id) }}"
                                class="block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg text-center mb-2">
                                <i class="fas fa-check mr-2"></i>Approve
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <i class="fas fa-clipboard-check text-6xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">Tidak ada permintaan pencairan</p>
                </div>
            @endforelse
        </div>

        <!-- Recent Disbursed -->
        @if ($recentDisbursed->count() > 0)
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Riwayat Pencairan</h2>

                @foreach ($recentDisbursed as $donation)
                    <div class="flex justify-between items-center py-3 border-b">
                        <div>
                            <p class="font-semibold">{{ $donation->ustadz->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $donation->donation_code }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-green-600">
                                Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $donation->disbursed_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
