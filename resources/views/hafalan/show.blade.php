@extends('layouts.app')

@section('title', 'Detail Hafalan')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Detail Hafalan</h1>
                <p class="text-gray-600">Informasi lengkap hafalan</p>
            </div>
            <a href="{{ route('hafalan.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Status Header -->
            <div
                class="px-6 py-4 border-b border-gray-200 {{ $hafalan->status === 'verified' ? 'bg-green-50' : ($hafalan->status === 'rejected' ? 'bg-red-50' : 'bg-yellow-50') }}">
                <div class="flex justify-between items-center">
                    <div>
                        <span class="text-sm font-medium text-gray-600">Status:</span>
                        @if ($hafalan->status === 'verified')
                            <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Verified
                            </span>
                        @elseif($hafalan->status === 'rejected')
                            <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>Rejected
                            </span>
                        @else
                            <span class="ml-2 px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-2">
                        @if ($hafalan->status === 'pending' && auth()->user()->can('verify_hafalan'))
                            <button onclick="verifyHafalan()"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                                <i class="fas fa-check mr-2"></i>Verifikasi
                            </button>
                            <button onclick="rejectHafalan()"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                                <i class="fas fa-times mr-2"></i>Tolak
                            </button>
                        @endif

                        @if (
                            $hafalan->status === 'pending' &&
                                (auth()->id() === $hafalan->created_by_user_id || auth()->user()->can('edit_hafalan')))
                            <a href="{{ route('hafalan.edit', $hafalan->id) }}"
                                class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Santri Info -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Santri</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Santri</label>
                            <p class="text-base text-gray-900">{{ $hafalan->user->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Kelas</label>
                            <p class="text-base text-gray-900">{{ $hafalan->class?->name ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Hafalan Detail -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Detail Hafalan</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Surah</label>
                            <p class="text-base text-gray-900">{{ $hafalan->surah_name }} ({{ $hafalan->surah_number }})</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Juz</label>
                            <p class="text-base text-gray-900">Juz {{ $hafalan->juz_number }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Ayat</label>
                            <p class="text-base text-gray-900">{{ $hafalan->ayat_start }} - {{ $hafalan->ayat_end }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jumlah Ayat</label>
                            <p class="text-base text-gray-900">{{ $hafalan->ayat_count }} ayat</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Jenis</label>
                            <p class="text-base text-gray-900">
                                @if ($hafalan->type === 'setoran')
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Setoran</span>
                                @else
                                    <span
                                        class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Muraja'ah</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal</label>
                            <p class="text-base text-gray-900">{{ $hafalan->hafalan_date->format('d F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Audio -->
                @if ($hafalan->has_audio)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-volume-up text-blue-500 mr-2"></i>Rekaman Audio
                        </h2>
                        @foreach ($hafalan->audios as $audio)
                            <div class="bg-gray-50 rounded-lg p-4 mb-3">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm text-gray-600">{{ $audio->original_filename }}</span>
                                    <span class="text-xs text-gray-500">{{ $audio->file_size_human }}</span>
                                </div>
                                @if ($audio->is_ready)
                                    <audio controls class="w-full">
                                        <source src="{{ $audio->url }}" type="{{ $audio->mime_type }}">
                                        Browser Anda tidak mendukung audio player.
                                    </audio>
                                @elseif($audio->is_processing)
                                    <div class="text-center py-3 text-yellow-600">
                                        <i class="fas fa-spinner fa-spin mr-2"></i>Sedang diproses...
                                    </div>
                                @else
                                    <div class="text-center py-3 text-red-600">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>Audio gagal diproses
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Notes -->
                @if ($hafalan->notes)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Catatan</h2>
                        <p class="text-base text-gray-700 whitespace-pre-line">{{ $hafalan->notes }}</p>
                    </div>
                @endif

                <!-- Verification Info -->
                @if ($hafalan->verified_at)
                    <div class="mb-6 pb-6 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800 mb-4">Informasi Verifikasi</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Diverifikasi oleh</label>
                                <p class="text-base text-gray-900">{{ $hafalan->verifiedBy?->name ?? '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-1">Tanggal Verifikasi</label>
                                <p class="text-base text-gray-900">{{ $hafalan->verified_at->format('d F Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Metadata -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat oleh:</span>
                            <span class="text-gray-900 font-medium">{{ $hafalan->createdBy->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal dibuat:</span>
                            <span class="text-gray-900 font-medium">{{ $hafalan->created_at->format('d M Y H:i') }}</span>
                        </div>
                        @if ($hafalan->updated_at != $hafalan->created_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Terakhir diupdate:</span>
                                <span
                                    class="text-gray-900 font-medium">{{ $hafalan->updated_at->format('d M Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verify Modal -->
    <div id="verify-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Verifikasi Hafalan</h3>
            <form id="verify-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea id="verify-notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="Tambahkan catatan verifikasi..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeVerifyModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg">
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tolak Hafalan</h3>
            <form id="reject-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                    <textarea id="reject-reason" rows="3" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                        placeholder="Masukkan alasan penolakan..."></textarea>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg">
                        Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        function verifyHafalan() {
            $('#verify-modal').removeClass('hidden');
        }

        function closeVerifyModal() {
            $('#verify-modal').addClass('hidden');
            $('#verify-notes').val('');
        }

        $('#verify-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('hafalan.verify', $hafalan->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notes: $('#verify-notes').val()
                },
                success: function(response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        function rejectHafalan() {
            $('#reject-modal').removeClass('hidden');
        }

        function closeRejectModal() {
            $('#reject-modal').addClass('hidden');
            $('#reject-reason').val('');
        }

        $('#reject-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: '{{ route('hafalan.reject', $hafalan->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: $('#reject-reason').val()
                },
                success: function(response) {
                    alert(response.message);
                    window.location.reload();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });
    </script>
@endpush
