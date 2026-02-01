@extends('layouts.app')

@section('title', 'Daftar Hafalan')

@section('content')
    <div class="container mx-auto px-4 py-6">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Daftar Hafalan</h1>
                <p class="text-gray-600">Kelola dan monitor hafalan santri</p>
            </div>

            @can('create_hafalan')
                <a href="{{ route('hafalan.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg shadow-md transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Hafalan
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select id="filter-status"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                    <select id="filter-type"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Jenis</option>
                        <option value="setoran">Setoran</option>
                        <option value="murajah">Muraja'ah</option>
                    </select>
                </div>

                <!-- Class Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select id="filter-class"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Juz Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Juz</label>
                    <select id="filter-juz"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                        <option value="">Semua Juz</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}">Juz {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dari Tanggal</label>
                    <input type="date" id="filter-date-from"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Sampai Tanggal</label>
                    <input type="date" id="filter-date-to"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200">
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end gap-2">
                    <button onclick="applyFilters()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button onclick="resetFilters()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2 rounded-lg transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table id="hafalan-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Santri</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Surah
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ayat
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Juz
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jml
                                Ayat</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Audio</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Verifikasi</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- DataTable will populate this -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Verify Modal -->
    <div id="verify-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Verifikasi Hafalan</h3>
                <form id="verify-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="verify-notes" rows="3"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200"
                            placeholder="Tambahkan catatan..."></textarea>
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
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Tolak Hafalan</h3>
                <form id="reject-form">
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
    </div>
@endsection

@push('styles')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endpush

@push('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        let table;
        let currentHafalanId;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#hafalan-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('hafalan.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.type = $('#filter-type').val();
                        d.class_id = $('#filter-class').val();
                        d.juz_number = $('#filter-juz').val();
                        d.date_from = $('#filter-date-from').val();
                        d.date_to = $('#filter-date-to').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user_name',
                        name: 'user.name'
                    },
                    {
                        data: 'class_name',
                        name: 'class.name'
                    },
                    {
                        data: 'surah_info',
                        name: 'surah_number'
                    },
                    {
                        data: 'ayat_range',
                        name: 'ayat_start',
                        orderable: false
                    },
                    {
                        data: 'juz_number',
                        name: 'juz_number',
                        className: 'text-center'
                    },
                    {
                        data: 'ayat_count',
                        name: 'ayat_count',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'type_badge',
                        name: 'type',
                        className: 'text-center'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'has_audio',
                        name: 'has_audio',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'hafalan_date',
                        name: 'hafalan_date'
                    },
                    {
                        data: 'verified_info',
                        name: 'verified_at',
                        orderable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                order: [
                    [10, 'desc']
                ], // Order by hafalan_date desc
                pageLength: 25,
                language: {
                    processing: "Memuat data...",
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
                    infoFiltered: "(disaring dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data tersedia",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                }
            });
        });

        // Apply Filters
        function applyFilters() {
            table.ajax.reload();
        }

        // Reset Filters
        function resetFilters() {
            $('#filter-status').val('');
            $('#filter-type').val('');
            $('#filter-class').val('');
            $('#filter-juz').val('');
            $('#filter-date-from').val('');
            $('#filter-date-to').val('');
            table.ajax.reload();
        }

        // Verify Hafalan
        function verifyHafalan(id) {
            currentHafalanId = id;
            $('#verify-modal').removeClass('hidden');
        }

        function closeVerifyModal() {
            $('#verify-modal').addClass('hidden');
            $('#verify-notes').val('');
        }

        $('#verify-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/hafalan/${currentHafalanId}/verify`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    notes: $('#verify-notes').val()
                },
                success: function(response) {
                    closeVerifyModal();
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Reject Hafalan
        function rejectHafalan(id) {
            currentHafalanId = id;
            $('#reject-modal').removeClass('hidden');
        }

        function closeRejectModal() {
            $('#reject-modal').addClass('hidden');
            $('#reject-reason').val('');
        }

        $('#reject-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/hafalan/${currentHafalanId}/reject`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    reason: $('#reject-reason').val()
                },
                success: function(response) {
                    closeRejectModal();
                    table.ajax.reload();
                    alert(response.message);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        // Delete Hafalan
        function deleteHafalan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus hafalan ini?')) {
                $.ajax({
                    url: `/hafalan/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        alert(response.message);
                    },
                    error: function(xhr) {
                        alert('Error: ' + xhr.responseJSON.message);
                    }
                });
            }
        }
    </script>
@endpush
