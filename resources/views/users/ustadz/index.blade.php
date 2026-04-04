@extends('layouts.app-enhanced')

@section('title', 'Manajemen Ustadz')
@section('breadcrumb', 'Pengguna / Ustadz')

@section('content')
    <div class="space-y-4 md:space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manajemen Ustadz</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola data ustadz dan monitor aktivitas mengajar</p>
            </div>
            @can('create_users')
                <a href="{{ route('users.ustadz.create') }}"
                    class="inline-flex items-center justify-center sm:justify-start px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-green-600 to-green-700 text-white text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all flex-shrink-0 whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Ustadz
                </a>
            @endcan
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-green-100 text-xs sm:text-sm mb-1">Total Ustadz</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-ustadz">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-chalkboard-teacher text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-blue-100 text-xs sm:text-sm mb-1">Ustadz Aktif</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="active-ustadz">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-check-circle text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-orange-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-yellow-100 text-xs sm:text-sm mb-1">Pending</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="pending-ustadz">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-hourglass-half text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-purple-100 text-xs sm:text-sm mb-1">Total Kelas</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-classes">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-chalkboard text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-3 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>
                    Filter & Pencarian
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        <i class="fas fa-info-circle mr-1 text-gray-400"></i>
                        Status
                    </label>
                    <select id="filter-status"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="col-span-1 sm:col-span-2 flex flex-col sm:flex-row items-stretch gap-2">
                    <button onclick="applyFilters()"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm py-2 rounded-lg transition">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <button onclick="resetFilters()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold text-sm py-2 rounded-lg transition">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Daftar Ustadz</h3>
                    <button onclick="refreshTable()"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition self-start sm:self-auto"
                        title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table id="ustadz-table" class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Ustadz</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">NIP</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Email</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden lg:table-cell">No HP</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Kelas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Verified</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
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

    <!-- Activate Modal -->
    <div id="activate-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4 mx-auto">
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Aktivasi Akun Ustadz</h3>
                <p class="text-gray-600 text-center mb-6">Akun ustadz akan diaktifkan dan dapat login ke sistem.</p>

                <form id="activate-form">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password (Opsional)</label>
                        <input type="password" id="activate-password"
                            class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring focus:ring-green-200"
                            placeholder="Kosongkan untuk generate otomatis">
                        <p class="text-xs text-gray-500 mt-1">Jika dikosongkan, password akan di-generate otomatis</p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="closeActivateModal()"
                            class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition">
                            Aktivasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Table Styling */
        #ustadz-table thead th {
            background-color: #f9fafb;
            padding: 8px 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        #ustadz-table tbody td {
            padding: 8px 10px;
            vertical-align: middle;
            line-height: 1.5;
        }

        #ustadz-table tbody tr {
            transition: background-color 0.2s ease;
        }

        #ustadz-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Responsive padding */
        @media (min-width: 640px) {
            #ustadz-table thead th {
                padding: 12px 16px;
            }

            #ustadz-table tbody td {
                padding: 12px 16px;
            }
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            #ustadz-table {
                font-size: 0.85rem;
            }

            #ustadz-table thead th {
                padding: 6px 8px;
                font-size: 0.7rem;
            }

            #ustadz-table tbody td {
                padding: 6px 8px;
            }
        }

        /* DataTable controls styling */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 6px 12px;
            margin-left: 2px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background: white;
            color: #374151;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            color: white;
            border-color: #3b82f6;
        }

        .dataTables_wrapper .dataTables_info {
            padding: 12px 16px;
            font-size: 0.85rem;
            color: #6b7280;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        let table;
        let currentUstadzId;

        $(document).ready(function() {
            table = $('#ustadz-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('users.ustadz.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    },
                    {
                        data: 'name',
                        name: 'user.name'
                    },
                    {
                        data: 'nip',
                        name: 'nip'
                    },
                    {
                        data: 'email',
                        name: 'user.email'
                    },
                    {
                        data: 'phone',
                        name: 'user.phone'
                    },
                    {
                        data: 'classes',
                        name: 'classes',
                        orderable: false
                    },
                    {
                        data: 'verified_today',
                        name: 'verified_today',
                        className: 'text-center'
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center'
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
                    [1, 'asc']
                ],
                pageLength: 25,
                language: {
                    processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-3xl text-blue-600 mr-3"></i><span class="text-gray-700">Memuat data...</span></div>',
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ ustadz",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 ustadz",
                    infoFiltered: "(disaring dari _MAX_ total ustadz)",
                    zeroRecords: '<div class="text-center py-12"><i class="fas fa-search text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Tidak ada data yang ditemukan</p></div>',
                    emptyTable: '<div class="text-center py-12"><i class="fas fa-chalkboard-teacher text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Belum ada data ustadz</p></div>',
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya"
                    }
                },
                drawCallback: function() {
                    updateStats();
                }
            });
        });

        function applyFilters() {
            table.ajax.reload();
        }

        function resetFilters() {
            $('#filter-status').val('');
            table.ajax.reload();
        }

        function refreshTable() {
            table.ajax.reload(null, false);
        }

        function updateStats() {
            $.ajax({
                url: "{{ route('users.ustadz.stats') }}",
                success: function(data) {
                    $('#total-ustadz').text(data.total);
                    $('#active-ustadz').text(data.active);
                    $('#pending-ustadz').text(data.pending);
                    $('#total-classes').text(data.total_classes);
                }
            });
        }

        function activateUstadz(id) {
            currentUstadzId = id;
            $('#activate-modal').removeClass('hidden');
        }

        function closeActivateModal() {
            $('#activate-modal').addClass('hidden');
            $('#activate-password').val('');
        }

        $('#activate-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/users/ustadz/${currentUstadzId}/activate`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    password: $('#activate-password').val()
                },
                success: function(response) {
                    closeActivateModal();
                    table.ajax.reload();
                    alert(response.message + '\nPassword: ' + response.password);
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.message);
                }
            });
        });

        function deleteUstadz(id) {
            if (confirm('Apakah Anda yakin ingin menghapus ustadz ini?')) {
                $.ajax({
                    url: `/users/ustadz/${id}`,
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

        // Initialize stats
        updateStats();
    </script>
@endpush
