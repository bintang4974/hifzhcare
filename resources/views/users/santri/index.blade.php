@extends('layouts.app-enhanced')

@section('title', 'Manajemen Santri')
@section('breadcrumb', 'Pengguna / Santri')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6 border-b pb-4">
            <div class="mb-4 sm:mb-0 text-center sm:text-left">
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Santri</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data santri dan monitor perkembangan hafalan</p>
            </div>

            <div>
                <a href="{{ route('users.santri.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition duration-150 flex items-center mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Santri
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 mt-4 mr-4 bg-blue-50 text-blue-500 rounded-full p-3">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Total Santri</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900" id="total-santri">-</h3>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 mt-4 mr-4 bg-green-50 text-green-500 rounded-full p-3">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Aktif</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900" id="active-santri">-</h3>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 mt-4 mr-4 bg-yellow-50 text-yellow-500 rounded-full p-3">
                    <i class="fas fa-hourglass-half text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Pending</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900" id="pending-santri">-</h3>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 mt-4 mr-4 bg-purple-50 text-purple-500 rounded-full p-3">
                    <i class="fas fa-graduation-cap text-xl"></i>
                </div>
                <p class="text-gray-500 text-sm font-medium mb-1">Lulus</p>
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900" id="graduated-santri">-</h3>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="p-5 sm:p-6 border-b border-gray-100">
                <div class="flex items-center justify-between mb-4 sm:hidden">
                    <h3 class="text-sm font-semibold text-gray-700">Filter Data</h3>
                    <button onclick="toggleFilters()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-chevron-down transition-transform" id="filter-toggle-icon"></i>
                    </button>
                </div>

                <div id="filter-section" class="grid grid-cols-1 md:grid-cols-4 xl:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Status</label>
                        <select id="filter-status"
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-2 sm:py-2.5">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="active">Aktif</option>
                            <option value="inactive">Tidak Aktif</option>
                            <option value="graduated">Lulus</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Kelas</label>
                        <select id="filter-class"
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-2 sm:py-2.5">
                            <option value="">Semua Kelas</option>
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase tracking-wider mb-2">Jenis
                            Kelamin</label>
                        <select id="filter-gender"
                            class="w-full text-sm rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-2 sm:py-2.5">
                            <option value="">Semua</option>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <div class="flex items-end gap-2 md:col-span-1 xl:col-span-2">
                        <button onclick="applyFilters()"
                            class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-gray-50 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-100 transition-colors shadow-sm">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                        <button onclick="resetFilters()"
                            class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2.5 bg-white border border-gray-300 text-gray-600 text-sm font-medium rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            <i class="fas fa-undo mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4 gap-4">
                    <h2 class="text-lg font-semibold text-gray-800">Daftar Santri</h2>
                    <div class="flex gap-2">
                        <button onclick="refreshTable()"
                            class="p-2 border border-gray-200 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors shadow-sm"
                            title="Refresh Data">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button onclick="exportData()"
                            class="px-4 py-2 border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 text-sm font-medium rounded-lg transition-colors shadow-sm flex items-center">
                            <i class="fas fa-file-export mr-2 text-gray-400"></i> Export
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table id="santri-table" class="min-w-full w-full align-middle whitespace-nowrap text-sm text-left">
                        <thead class="bg-gray-50/50">
                            <tr>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">
                                    No</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Santri</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                    NIS</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center hidden md:table-cell">
                                    Gender</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center hidden lg:table-cell">
                                    Usia</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                    Wali</th>
                                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center hidden md:table-cell">
                                    Progress</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider text-center rounded-tr-lg">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Mobile Filter Collapse */
        @media (max-width: 640px) {
            #filter-section {
                display: none;
            }

            #filter-section.show {
                display: grid;
            }
        }

        /* --- TAILWIND OVERRIDES UNTUK DATATABLES --- */
        /* Membersihkan garis border bawaan DataTables */
        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        table.dataTable thead th,
        table.dataTable thead td {
            border-bottom: 1px solid #f3f4f6 !important;
        }

        table.dataTable tbody tr {
            background-color: transparent !important;
        }

        table.dataTable tbody tr:hover {
            background-color: #f9fafb !important;
        }

        table.dataTable tbody td {
            border-top: none !important;
            border-bottom: 1px solid #f3f4f6 !important;
            padding: 12px 16px !important;
        }

        /* Styling Input Pencarian & Dropdown DataTables */
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.3rem 2rem 0.3rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
        }

        .dataTables_wrapper .dataTables_filter input {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
            margin-left: 0.5rem;
        }

        .dataTables_wrapper .dataTables_filter input:focus,
        .dataTables_wrapper .dataTables_length select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px #3b82f6;
        }

        /* Perbaikan Responsive Mobile untuk "Tampilkan" dan "Cari" */
        @media (max-width: 640px) {

            .dataTables_wrapper .dataTables_length,
            .dataTables_wrapper .dataTables_filter {
                float: none;
                text-align: left;
                width: 100%;
                margin-bottom: 12px;
            }

            .dataTables_wrapper .dataTables_filter input {
                width: 100%;
                margin-left: 0;
                margin-top: 6px;
                display: block;
            }

            .dataTables_wrapper .dataTables_info,
            .dataTables_wrapper .dataTables_paginate {
                float: none;
                text-align: center;
                margin-top: 10px;
            }
        }

        /* Styling Pagination DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.3em 0.8em;
            margin-left: 4px;
            border-radius: 0.375rem;
            border: 1px solid #e5e7eb !important;
            background: white !important;
            color: #374151 !important;
            font-size: 0.875rem;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6 !important;
            border-color: #d1d5db !important;
            color: #111827 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #eef2ff !important;
            border-color: #6366f1 !important;
            color: #4f46e5 !important;
            font-weight: 600;
        }

        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
            padding-top: 1em;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        let table;
        let currentSantriId;

        $(document).ready(function() {
            table = $('#santri-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                // Hilangkan border default dari responsive DataTable
                autoWidth: false,
                ajax: {
                    url: "{{ route('users.santri.index') }}",
                    data: function(d) {
                        d.status = $('#filter-status').val();
                        d.class_id = $('#filter-class').val();
                        d.gender = $('#filter-gender').val();
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
                        data: 'nis',
                        name: 'nis'
                    },
                    {
                        data: 'gender_label',
                        name: 'gender',
                        className: 'text-center'
                    },
                    {
                        data: 'age',
                        name: 'birth_date',
                        className: 'text-center'
                    },
                    {
                        data: 'wali_name',
                        name: 'wali.user.name'
                    },
                    {
                        data: 'classes',
                        name: 'classes',
                        orderable: false
                    },
                    {
                        data: 'progress',
                        name: 'progress',
                        orderable: false,
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
                    search: "_INPUT_", // Menghilangkan teks "Cari:" agar digantikan placeholder
                    searchPlaceholder: "Cari santri, NIS...",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Menampilkan 0 data",
                    zeroRecords: '<div class="text-center py-8"><i class="fas fa-search text-4xl text-gray-300 mb-3 block"></i><span class="text-gray-500">Tidak ada data ditemukan</span></div>',
                    emptyTable: '<div class="text-center py-8"><i class="fas fa-users text-4xl text-gray-300 mb-3 block"></i><span class="text-gray-500">Belum ada data santri</span></div>',
                    paginate: {
                        next: '<i class="fas fa-chevron-right text-xs"></i>',
                        previous: '<i class="fas fa-chevron-left text-xs"></i>'
                    }
                },
                drawCallback: function() {
                    updateStats();
                }
            });
        });

        function toggleFilters() {
            const section = document.getElementById('filter-section');
            const icon = document.getElementById('filter-toggle-icon');
            section.classList.toggle('show');
            icon.classList.toggle('rotate-180');
        }

        // Fungsi applyFilters, resetFilters, dll biarkan sama persis dengan aslinya
        function applyFilters() {
            table.ajax.reload();
        }

        function resetFilters() {
            $('#filter-status').val('');
            $('#filter-class').val('');
            $('#filter-gender').val('');
            table.ajax.reload();
        }

        function refreshTable() {
            table.ajax.reload(null, false);
        }

        function updateStats() {
            $.ajax({
                url: "{{ route('users.santri.stats') }}",
                success: function(data) {
                    $('#total-santri').text(data.total);
                    $('#active-santri').text(data.active);
                    $('#pending-santri').text(data.pending);
                    $('#graduated-santri').text(data.graduated);
                }
            });
        }

        function activateSantri(id) {
            currentSantriId = id;
            $('#activate-modal').removeClass('hidden');
        }

        function closeActivateModal() {
            $('#activate-modal').addClass('hidden');
            $('#activate-password').val('');
        }

        $('#activate-form').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: `/users/santri/${currentSantriId}/activate`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    password: $('#activate-password').val()
                },
                success: function(response) {
                    closeActivateModal();
                    table.ajax.reload();
                    showNotification('success', response.message);
                },
                error: function(xhr) {
                    showNotification('error', xhr.responseJSON.message);
                }
            });
        });

        function deleteSantri(id) {
            if (confirm('Apakah Anda yakin ingin menghapus santri ini?')) {
                $.ajax({
                    url: `/users/santri/${id}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        table.ajax.reload();
                        showNotification('success', response.message);
                    },
                    error: function(xhr) {
                        showNotification('error', xhr.responseJSON.message);
                    }
                });
            }
        }

        function showNotification(type, message) {
            // Create notification element
            const colors = {
                success: 'bg-green-50 border-green-500 text-green-700',
                error: 'bg-red-50 border-red-500 text-red-700'
            };

            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 ${colors[type]} border-l-4 p-4 rounded-lg shadow-lg z-50 max-w-md animate-slide-in`;
            notification.innerHTML = `
        <div class="flex items-center justify-between">
            <p class="font-medium">${message}</p>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
            document.body.appendChild(notification);

            setTimeout(() => notification.remove(), 5000);
        }

        function exportData() {
            window.location.href = "{{ route('users.santri.export') }}";
        }

        // Initialize stats
        updateStats();
    </script>
@endpush
