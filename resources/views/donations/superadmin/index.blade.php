@extends('layouts.app-enhanced')

@section('title', 'Verifikasi Donasi')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Verifikasi Donasi</h1>

        <!-- Tabs -->
        <div class="flex gap-2 border-b">
            <button onclick="showTab('pending')" class="tab-btn active px-6 py-3 font-semibold border-b-2 border-blue-600">
                Pending ({{ $pendingDonations->count() }})
            </button>
            <button onclick="showTab('verified')" class="tab-btn px-6 py-3 font-semibold text-gray-600 hover:text-gray-900">
                Verified ({{ $verifiedDonations->count() }})
            </button>
        </div>

        <!-- Pending Tab -->
        <div id="pending-tab" class="tab-content">
            @forelse($pendingDonations as $donation)
                <div class="bg-white rounded-2xl shadow-lg p-6 mb-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Left: Info -->
                        <div class="lg:col-span-2">
                            <div class="flex items-center gap-3 mb-4">
                                <h3 class="text-xl font-bold">{{ $donation->donation_code }}</h3>
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs">Pending</span>
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
                                    <p class="text-sm text-gray-500">Pesantren</p>
                                    <p class="font-semibold">{{ $donation->pesantren->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal</p>
                                    <p class="font-semibold">{{ $donation->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            <!-- Amounts -->
                            <div class="p-4 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl">
                                <div class="grid grid-cols-4 gap-4 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Total</p>
                                        <p class="text-lg font-bold text-gray-900">
                                            {{ number_format($donation->amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Platform 3%</p>
                                        <p class="text-lg font-bold text-blue-600">
                                            {{ number_format($donation->platform_fee, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Pesantren 10%</p>
                                        <p class="text-lg font-bold text-purple-600">
                                            {{ number_format($donation->pesantren_fee, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 mb-1">Ustadz Net</p>
                                        <p class="text-lg font-bold text-green-600">
                                            {{ number_format($donation->ustadz_net_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            @if ($donation->notes)
                                <div class="mt-4 p-3 bg-gray-50 rounded-lg">
                                    <p class="text-sm text-gray-700">
                                        <strong>Catatan:</strong> {{ $donation->notes }}
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Right: Proof & Actions -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-700 mb-2">Bukti Transfer:</p>
                                <img src="{{ Storage::url($donation->payment_proof) }}" alt="Proof"
                                    class="w-full rounded-lg border-2 border-gray-200">
                            </div>

                            <div class="flex flex-col gap-2">
                                <form method="POST" action="{{ route('superadmin.donations.verify', $donation->id) }}">
                                    @csrf
                                    <button type="submit"
                                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition">
                                        <i class="fas fa-check mr-2"></i>Approve
                                    </button>
                                </form>

                                <button onclick="showRejectModal({{ $donation->id }})"
                                    class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white font-bold rounded-lg transition">
                                    <i class="fas fa-times mr-2"></i>Reject
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl p-12 text-center">
                    <i class="fas fa-check-circle text-6xl text-green-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-900">Tidak Ada Donasi Pending</h3>
                    <p class="text-gray-600">Semua donasi sudah diverifikasi</p>
                </div>
            @endforelse
        </div>

        <!-- Verified Tab -->
        <div id="verified-tab" class="tab-content hidden">
            @foreach ($verifiedDonations as $donation)
                <div class="bg-white rounded-xl shadow p-4 mb-3 flex justify-between items-center">
                    <div>
                        <p class="font-bold">{{ $donation->donation_code }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $donation->wali->user->name }} → {{ $donation->ustadz->user->name }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-green-600">Rp {{ number_format($donation->amount, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-500">{{ $donation->verified_at->diffForHumans() }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4">
            <h3 class="text-xl font-bold mb-4">Tolak Donasi</h3>
            <form id="rejectForm" method="POST">
                @csrf
                <textarea name="rejection_notes" required rows="4" class="w-full rounded-lg border-gray-300 mb-4"
                    placeholder="Alasan penolakan..."></textarea>
                <div class="flex gap-2">
                    <button type="button" onclick="hideRejectModal()"
                        class="flex-1 px-4 py-2 bg-gray-200 rounded-lg">Batal</button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg">Tolak</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function showTab(tab) {
                document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
                document.querySelectorAll('.tab-btn').forEach(el => {
                    el.classList.remove('active', 'border-blue-600', 'text-blue-600');
                    el.classList.add('text-gray-600');
                });
                document.getElementById(tab + '-tab').classList.remove('hidden');
                event.target.classList.add('active', 'border-blue-600', 'text-blue-600');
            }

            function showRejectModal(id) {
                document.getElementById('rejectForm').action = `/superadmin/donations/${id}/reject`;
                document.getElementById('rejectModal').classList.remove('hidden');
            }

            function hideRejectModal() {
                document.getElementById('rejectModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection
