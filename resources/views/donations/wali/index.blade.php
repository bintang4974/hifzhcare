@extends('layouts.app-enhanced')

@section('title', 'Riwayat Donasi')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Riwayat Donasi</h1>
                <p class="text-gray-600 mt-1">Lihat semua donasi yang pernah Anda berikan</p>
            </div>
            <a href="{{ route('donations.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition">
                <i class="fas fa-plus mr-2"></i>Donasi Baru
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @forelse($donations as $donation)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-3">
                            <h3 class="text-xl font-bold text-gray-900">{{ $donation->donation_code }}</h3>
                            @if ($donation->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-clock mr-1"></i>Menunggu Verifikasi
                                </span>
                            @elseif($donation->status === 'verified')
                                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check mr-1"></i>Terverifikasi
                                </span>
                            @elseif(in_array($donation->status, ['transferred', 'available', 'requested']))
                                <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-check-double mr-1"></i>Diterima Ustadz
                                </span>
                            @elseif($donation->status === 'disbursed')
                                <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-money-bill-wave mr-1"></i>Sudah Dicairkan
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-xs font-semibold">
                                    <i class="fas fa-times mr-1"></i>Ditolak
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                            <div>
                                <p class="text-xs text-gray-500">Untuk Ustadz</p>
                                <p class="font-semibold text-gray-900">{{ $donation->ustadz->user->name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Total Donasi</p>
                                <p class="text-lg font-bold text-pink-600">
                                    Rp {{ number_format($donation->amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Diterima Ustadz</p>
                                <p class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">Tanggal</p>
                                <p class="font-semibold text-gray-700">
                                    {{ $donation->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>

                        @if ($donation->notes)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-sm text-gray-700">
                                    <i class="fas fa-comment text-gray-400 mr-2"></i>
                                    {{ $donation->notes }}
                                </p>
                            </div>
                        @endif
                    </div>

                    <div class="ml-6">
                        <a href="{{ route('donations.show', $donation->id) }}"
                            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold transition">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                <i class="fas fa-heart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Belum Ada Donasi</h3>
                <p class="text-gray-600 mb-6">Anda belum pernah memberikan donasi kepada ustadz</p>
                <a href="{{ route('donations.create') }}"
                    class="inline-block px-6 py-3 bg-gradient-to-r from-pink-600 to-rose-600 text-white font-semibold rounded-xl">
                    <i class="fas fa-plus mr-2"></i>Donasi Sekarang
                </a>
            </div>
        @endforelse

        <div class="mt-6">
            {{ $donations->links() }}
        </div>
    </div>
@endsection
