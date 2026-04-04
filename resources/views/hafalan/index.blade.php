@extends('layouts.app-enhanced')

@section('title', 'Daftar Hafalan')

@section('content')
    <div class="container mx-auto px-3 sm:px-4 py-4 sm:py-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Daftar Hafalan</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola dan monitor hafalan santri</p>
            </div>

            @can('create_hafalan')
                <a href="{{ route('hafalan.create') }}"
                    class="inline-flex items-center justify-center px-4 sm:px-6 py-2.5 sm:py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm sm:text-base font-semibold rounded-lg shadow-md hover:shadow-lg transition flex-shrink-0 whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i>Tambah Hafalan
                </a>
            @endcan
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg sm:rounded-lg shadow-md p-3 sm:p-6 mb-4 sm:mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Status</label>
                    <select id="filter-status"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="verified">Verified</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jenis</label>
                    <select id="filter-type"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Jenis</option>
                        <option value="setoran">Setoran</option>
                        <option value="murajah">Muraja'ah</option>
                    </select>
                </div>

                <!-- Class Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Kelas</label>
                    <select id="filter-class"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Juz Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Juz</label>
                    <select id="filter-juz"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Juz</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}">Juz {{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Dari Tanggal</label>
                    <input type="date" id="filter-date-from"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Sampai Tanggal</label>
                    <input type="date" id="filter-date-to"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                </div>

                <!-- Filter Actions -->
                <div class="col-span-1 sm:col-span-2 lg:col-span-4 flex flex-col sm:flex-row items-stretch gap-2">
                    <button onclick="applyFilters()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2 rounded-lg transition">
                        <i class="fas fa-filter mr-2"></i>Filter
                    </button>
                    <button onclick="resetFilters()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold text-sm py-2 rounded-lg transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- DataTable Container - Card Based -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Daftar Hafalan</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="hafalan-table" class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Santri</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Kelas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Surah</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Ayat</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden lg:table-cell">Juz</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Jml Ayat</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Jenis</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden lg:table-cell">Audio</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Tanggal</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Verifikasi</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
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
    <div id="verify-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4 flex items-center sm:items-start sm:justify-center sm:pt-20">
        <div class="relative mx-auto p-4 sm:p-5 border w-full sm:w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-base sm:text-lg font-medium leading-6 text-gray-900 mb-4">Verifikasi Hafalan</h3>
                <form id="verify-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
                        <textarea id="verify-notes" rows="3"
                            class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2"
                            placeholder="Tambahkan catatan..."></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeVerifyModal()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold text-sm px-4 py-2 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm px-4 py-2 rounded-lg">
                            Verifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div id="reject-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 p-4 flex items-center sm:items-start sm:justify-center sm:pt-20">
        <div class="relative mx-auto p-4 sm:p-5 border w-full sm:w-96 shadow-lg rounded-lg bg-white">
            <div class="mt-3">
                <h3 class="text-base sm:text-lg font-medium leading-6 text-gray-900 mb-4">Tolak Hafalan</h3>
                <form id="reject-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
                        <textarea id="reject-reason" rows="3" required
                            class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 p-2"
                            placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex gap-2">
                        <button type="button" onclick="closeRejectModal()"
                            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold text-sm px-4 py-2 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold text-sm px-4 py-2 rounded-lg">
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
    
    <style>
        /* Table Styling */
        #hafalan-table {
            font-size: 0.95rem;
        }

        #hafalan-table thead th {
            background-color: #f9fafb;
            padding: 10px 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        #hafalan-table tbody td {
            padding: 10px 12px;
            vertical-align: middle;
            line-height: 1.5;
        }

        #hafalan-table tbody tr {
            transition: background-color 0.2s ease;
        }

        #hafalan-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Responsive padding */
        @media (min-width: 640px) {
            #hafalan-table thead th {
                padding: 12px 16px;
            }

            #hafalan-table tbody td {
                padding: 12px 16px;
            }
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            #hafalan-table {
                font-size: 0.85rem;
            }

            #hafalan-table thead th {
                padding: 8px 10px;
                font-size: 0.7rem;
            }

            #hafalan-table tbody td {
                padding: 8px 10px;
            }
        }

        /* Scrollable container styling */
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        /* Better centering for action columns */
        .text-center {
            text-align: center;
        }

        /* DataTable controls styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin-left: 2px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_filter {
            padding: 12px 16px;
        }

        .dataTables_wrapper .dataTables_filter input {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin-left: 8px;
        }

        .dataTables_wrapper .dataTables_length select {
            padding: 6px 12px;
            border: 1px solid #d1d5db;
            border-radius: 4px;
            margin-left: 8px;
        }

        /* Processing indicator */
        .dataTables_processing {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            font-size: 0.85rem;
        }
    </style>
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
                columnDefs: [
                    { responsivePriority: 1, targets: 0 }, // No
                    { responsivePriority: 2, targets: 1 }, // Santri
                    { responsivePriority: 7, targets: 2 }, // Kelas
                    { responsivePriority: 3, targets: 3 }, // Surah
                    { responsivePriority: 8, targets: 4 }, // Ayat
                    { responsivePriority: 9, targets: 5 }, // Juz
                    { responsivePriority: 10, targets: 6 }, // Jml Ayat
                    { responsivePriority: 4, targets: 7 }, // Jenis
                    { responsivePriority: 5, targets: 8 }, // Status
                    { responsivePriority: 11, targets: 9 }, // Audio
                    { responsivePriority: 12, targets: 10 }, // Tanggal
                    { responsivePriority: 13, targets: 11 }, // Verifikasi
                    { responsivePriority: 6, targets: 12 } // Aksi
                ],
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
                ],
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
        });;

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
