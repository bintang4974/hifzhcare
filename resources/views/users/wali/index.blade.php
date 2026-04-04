@extends('layouts.app-enhanced')

@section('title', 'Manajemen Wali')
@section('breadcrumb', 'Pengguna / Wali')

@section('content')
    <div class="space-y-4 md:space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manajemen Wali</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola data wali santri dan monitor kontribusi</p>
            </div>
            @can('create_users')
                <a href="{{ route('users.wali.create') }}"
                    class="inline-flex items-center justify-center sm:justify-start px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all flex-shrink-0 whitespace-nowrap">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Tambah Wali
                </a>
            @endcan
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-purple-100 text-xs sm:text-sm mb-1">Total Wali</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-wali">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-users text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-green-100 text-xs sm:text-sm mb-1">Wali Aktif</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="active-wali">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-check-circle text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-blue-100 text-xs sm:text-sm mb-1">Total Santri</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-santri">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-chart-pie text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-orange-100 text-xs sm:text-sm mb-1">Total Donasi</p>
                        <h3 class="text-lg sm:text-2xl font-bold truncate" id="total-donations">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-donate text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md p-3 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>
                    Filter & Pencarian
                </h3>
                <button onclick="toggleFilters()" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <i class="fas fa-chevron-down transition-transform" id="filter-toggle-icon"></i>
                </button>
            </div>

            <div id="filter-section" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        <i class="fas fa-info-circle mr-1 text-gray-400"></i>
                        Status
                    </label>
                    <select id="filter-status"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Status</option>
                        <option value="active">Aktif</option>
                        <option value="pending">Pending</option>
                        <option value="inactive">Tidak Aktif</option>
                    </select>
                </div>

                <!-- Relation Filter -->
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                        <i class="fas fa-sitemap mr-1 text-gray-400"></i>
                        Hubungan
                    </label>
                    <select id="filter-relation"
                        class="w-full text-sm rounded-lg border border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 py-1.5 sm:py-2 px-2 sm:px-3">
                        <option value="">Semua Hubungan</option>
                        <option value="ayah">Ayah</option>
                        <option value="ibu">Ibu</option>
                        <option value="wali">Wali</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="col-span-1 sm:col-span-2 lg:col-span-2 flex flex-col sm:flex-row items-stretch gap-2">
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
            <!-- Table Header -->
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 bg-gray-50">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4">
                    <h3 class="text-base sm:text-lg font-bold text-gray-900">Daftar Wali</h3>
                    <button onclick="refreshTable()"
                        class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition self-start sm:self-auto"
                        title="Refresh">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table id="wali-table" class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Wali</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Email</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden md:table-cell">No HP</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Hubungan</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Santri</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden lg:table-cell">Daftar Santri</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-right text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Donasi</th>
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

    <!-- Delete Modal -->
    <div id="delete-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mb-4 mx-auto">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 text-center mb-2">Hapus Wali</h3>
                <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus wali ini? Data tidak dapat dikembalikan.</p>

                <form id="delete-form" method="POST">
                    @csrf
                    @method('DELETE')
                    
                    <div class="flex gap-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition">
                            Batal
                        </button>
                        <button type="submit"
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition">
                            Hapus
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
        #filter-section {
            max-height: 500px;
            transition: max-height 0.3s ease;
        }

        #filter-section.collapsed {
            max-height: 0;
            overflow: hidden;
        }

        /* Table Styling */
        #wali-table thead th {
            background-color: #f9fafb;
            padding: 8px 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #e5e7eb;
        }

        #wali-table tbody td {
            padding: 8px 10px;
            vertical-align: middle;
            line-height: 1.5;
        }

        #wali-table tbody tr {
            transition: background-color 0.2s ease;
        }

        #wali-table tbody tr:hover {
            background-color: #f3f4f6;
        }

        /* Responsive padding */
        @media (min-width: 640px) {
            #wali-table thead th {
                padding: 12px 16px;
            }

            #wali-table tbody td {
                padding: 12px 16px;
            }
        }

        /* Mobile responsive */
        @media (max-width: 640px) {
            #wali-table {
                font-size: 0.85rem;
            }

            #wali-table thead th {
                padding: 6px 8px;
                font-size: 0.7rem;
            }

            #wali-table tbody td {
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
        let currentWaliId;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#wali-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('users.wali.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.relation = $('#filter-relation').val();
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
                        data: 'email',
                        name: 'user.email'
                    },
                    {
                        data: 'phone',
                        name: 'user.phone'
                    },
                    {
                        data: 'relation_label',
                        name: 'relation',
                        orderable: false
                    },
                    {
                        data: 'children_count',
                        name: 'children_count',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'children',
                        name: 'children',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_donations',
                        name: 'total_donations',
                        className: 'text-right',
                        orderable: false
                    },
                    {
                        data: 'status_badge',
                        name: 'status',
                        className: 'text-center',
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
                    [1, 'asc']
                ],
                pageLength: 25,
                language: {
                    processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-3xl text-blue-600 mr-3"></i><span class="text-gray-700">Memuat data...</span></div>',
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ wali",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 wali",
                    infoFiltered: "(disaring dari _MAX_ total wali)",
                    zeroRecords: '<div class="text-center py-12"><i class="fas fa-search text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Tidak ada data yang ditemukan</p></div>',
                    emptyTable: '<div class="text-center py-12"><i class="fas fa-users text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Belum ada data wali</p></div>',
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
            $('#filter-relation').val('');
            table.ajax.reload();
        }

        function refreshTable() {
            table.ajax.reload(null, false);
        }

        function deleteWali(id) {
            currentWaliId = id;
            document.getElementById('delete-modal').classList.remove('hidden');
            document.getElementById('delete-form').action = "{{ url('users/wali') }}/" + id;
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').classList.add('hidden');
        }

        function updateStats() {
            // Get all data from the table to calculate stats
            let allData = table.data();
            let totalWali = table.page.info().recordsTotal;
            let activeWali = 0;
            let totalSantri = 0;
            let totalDonations = 0;

            // Parse the visible HTML data (since we can't access raw data from the table)
            $('#wali-table tbody tr').each(function() {
                // Count active status from badge
                if ($(this).find('[class*="bg-green-100"]').length) {
                    activeWali++;
                }
            });

            // For now, show total wali count
            document.getElementById('total-wali').innerText = totalWali;
            document.getElementById('active-wali').innerText = activeWali;
            
            // These would require a separate API call or data attribute to get accurate counts
            // For demo purposes, you can fetch this data from controller
            fetch("{{ route('users.wali.stats') }}", {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-wali').innerText = data.total_wali || totalWali;
                document.getElementById('active-wali').innerText = data.active_wali || activeWali;
                document.getElementById('total-santri').innerText = data.total_santri || 0;
                document.getElementById('total-donations').innerText = data.total_donations || 'Rp 0';
            })
            .catch(e => {
                console.log('Stats API not available');
                // Fallback: keep showing counts from table
                document.getElementById('total-wali').innerText = totalWali;
                document.getElementById('active-wali').innerText = activeWali;
            });
        }

        function toggleFilters() {
            const filterSection = document.getElementById('filter-section');
            const toggleIcon = document.getElementById('filter-toggle-icon');
            
            if (filterSection.classList.contains('collapsed')) {
                filterSection.classList.remove('collapsed');
                toggleIcon.style.transform = 'rotate(0deg)';
            } else {
                filterSection.classList.add('collapsed');
                toggleIcon.style.transform = 'rotate(-180deg)';
            }
        }
    </script>
@endpush
