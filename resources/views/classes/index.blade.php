@extends('layouts.app-enhanced')
@section('title', 'Manajemen Kelas')
@section('breadcrumb', 'Kelas')

@push('styles')
    <style>
        .stat-card {
            position: relative;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
        }

        .stat-card::after {
            content: '';
            position: absolute;
            bottom: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.08);
        }

        /* DataTable Custom Styling */
        #classes-table_wrapper .dataTables_length,
        #classes-table_wrapper .dataTables_filter {
            padding: 0 0 1rem 0;
        }

        #classes-table_wrapper .dataTables_filter input {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.4rem 0.75rem 0.4rem 2.25rem;
            font-size: 0.875rem;
            outline: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 0.5rem center;
            background-size: 1rem;
            width: 220px;
            transition: border-color 0.15s;
        }

        #classes-table_wrapper .dataTables_filter input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        #classes-table_wrapper .dataTables_filter label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        #classes-table_wrapper .dataTables_length select {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.4rem 1.75rem 0.4rem 0.75rem;
            font-size: 0.875rem;
            outline: none;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: calc(100% - 0.5rem) center;
            background-size: 1rem;
            cursor: pointer;
        }

        #classes-table_wrapper .dataTables_length label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            color: #6b7280;
        }

        #classes-table_wrapper .dataTables_info {
            font-size: 0.8125rem;
            color: #6b7280;
            padding-top: 0.75rem;
        }

        #classes-table_wrapper .dataTables_paginate {
            padding-top: 0.75rem;
        }

        #classes-table_wrapper .dataTables_paginate .paginate_button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2rem;
            height: 2rem;
            padding: 0 0.5rem;
            border-radius: 0.375rem;
            font-size: 0.8125rem;
            color: #374151;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s;
        }

        #classes-table_wrapper .dataTables_paginate .paginate_button:hover {
            background: #f3f4f6;
            border-color: #e5e7eb;
        }

        #classes-table_wrapper .dataTables_paginate .paginate_button.current {
            background: #2563eb;
            color: white !important;
            border-color: #2563eb;
        }

        #classes-table_wrapper .dataTables_paginate .paginate_button.disabled {
            color: #d1d5db !important;
            cursor: default;
        }

        #classes-table_wrapper .dataTables_paginate .paginate_button.disabled:hover {
            background: transparent;
            border-color: transparent;
        }

        /* Table rows */
        #classes-table tbody tr {
            transition: background 0.1s;
        }

        #classes-table tbody tr:hover {
            background-color: #f8faff;
        }

        /* Action buttons */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 30px;
            height: 30px;
            border-radius: 0.375rem;
            transition: all 0.15s;
            font-size: 0.8125rem;
        }

        .btn-action:hover {
            transform: translateY(-1px);
        }

        .btn-view {
            background: #eff6ff;
            color: #2563eb;
        }

        .btn-view:hover {
            background: #dbeafe;
        }

        .btn-member {
            background: #f0fdf4;
            color: #16a34a;
        }

        .btn-member:hover {
            background: #dcfce7;
        }

        .btn-edit {
            background: #fefce8;
            color: #ca8a04;
        }

        .btn-edit:hover {
            background: #fef9c3;
        }

        .btn-delete {
            background: #fef2f2;
            color: #dc2626;
        }

        .btn-delete:hover {
            background: #fee2e2;
        }

        /* Status badge */
        .badge-active {
            background: #dcfce7;
            color: #15803d;
            font-size: 0.75rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        .badge-inactive {
            background: #f3f4f6;
            color: #6b7280;
            font-size: 0.75rem;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
            font-weight: 600;
        }

        /* Capacity pill */
        .capacity-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            background: #f1f5f9;
            color: #475569;
            border-radius: 9999px;
            padding: 0.15rem 0.6rem;
            font-size: 0.8125rem;
        }

        /* Ustadz chip */
        .ustadz-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.8125rem;
            color: #374151;
            margin-bottom: 2px;
        }

        .ustadz-avatar {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            background: #dbeafe;
            color: #1d4ed8;
            font-size: 0.6rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        @media (max-width: 640px) {
            #classes-table_wrapper .dataTables_filter {
                margin-top: 0.5rem;
            }

            #classes-table_wrapper .dataTables_filter input {
                width: 160px;
            }

            .top-controls {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="space-y-4 md:space-y-6">

        {{-- ── Header ── --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manajemen Kelas</h1>
                <p class="text-sm text-gray-500 mt-0.5">Kelola kelas dan anggota kelas</p>
            </div>
            @can('create_classes')
                <a href="{{ route('classes.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-2.5
                           bg-blue-600 hover:bg-blue-700 active:scale-95
                           text-white text-sm font-semibold rounded-xl shadow-sm
                           transition-all duration-150 flex-shrink-0">
                    <i class="fas fa-plus-circle text-sm"></i>
                    <span>Tambah Kelas</span>
                </a>
            @endcan
        </div>

        {{-- ── Stat Cards ── --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">

            {{-- Total Kelas --}}
            <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 sm:p-5 text-white shadow">
                <p class="text-blue-100 text-xs font-medium mb-1">Total Kelas</p>
                <h3 class="text-2xl sm:text-3xl font-bold" id="total-classes">–</h3>
                <div class="mt-3 w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard text-lg"></i>
                </div>
            </div>

            {{-- Kelas Aktif --}}
            <div
                class="stat-card bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl p-4 sm:p-5 text-white shadow">
                <p class="text-emerald-100 text-xs font-medium mb-1">Kelas Aktif</p>
                <h3 class="text-2xl sm:text-3xl font-bold" id="active-classes">–</h3>
                <div class="mt-3 w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>

            {{-- Total Santri --}}
            <div class="stat-card bg-gradient-to-br from-violet-500 to-violet-600 rounded-xl p-4 sm:p-5 text-white shadow">
                <p class="text-violet-100 text-xs font-medium mb-1">Total Santri</p>
                <h3 class="text-2xl sm:text-3xl font-bold" id="total-students">–</h3>
                <div class="mt-3 w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-lg"></i>
                </div>
            </div>

            {{-- Total Ustadz --}}
            <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 sm:p-5 text-white shadow">
                <p class="text-orange-100 text-xs font-medium mb-1">Total Ustadz</p>
                <h3 class="text-2xl sm:text-3xl font-bold" id="total-teachers">–</h3>
                <div class="mt-3 w-9 h-9 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chalkboard-teacher text-lg"></i>
                </div>
            </div>
        </div>

        {{-- ── Daftar Kelas Table Card ── --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

            {{-- Card Header --}}
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2
                        px-4 sm:px-6 py-4 border-b border-gray-100">
                <div>
                    <h3 class="text-base font-bold text-gray-900">Daftar Kelas</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Semua kelas yang terdaftar</p>
                </div>
                {{-- Search & Length controls will be injected by DataTables, we position via wrapper --}}
            </div>

            {{-- DataTable --}}
            <div class="px-4 sm:px-6 py-4">
                <div class="top-controls flex flex-wrap items-center justify-between gap-2 mb-4" id="dt-controls-slot">
                </div>

                <div class="overflow-x-auto -mx-4 sm:-mx-6">
                    <table id="classes-table" class="min-w-full divide-y divide-gray-100 text-sm">
                        <thead class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wide">
                            <tr>
                                <th class="px-4 sm:px-6 py-3 text-left w-10">No</th>
                                <th class="px-4 sm:px-6 py-3 text-left">Nama Kelas</th>
                                <th class="px-4 sm:px-6 py-3 text-left hidden sm:table-cell">Kode</th>
                                <th class="px-4 sm:px-6 py-3 text-left">Ustadz</th>
                                <th class="px-4 sm:px-6 py-3 text-center hidden md:table-cell">Kapasitas</th>
                                <th class="px-4 sm:px-6 py-3 text-center">Status</th>
                                <th class="px-4 sm:px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-50 text-gray-700">
                            {{-- DataTables will populate --}}
                        </tbody>
                    </table>
                </div>

                {{-- Footer: info + pagination --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mt-4" id="dt-footer-slot">
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script>
            $(function() {

                let table = $('#classes-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('classes.index') }}",
                    dom: '<"#dt-controls-slot"lf>t<"#dt-footer-slot"ip>',
                    columns: [{
                            data: 'DT_RowIndex',
                            orderable: false,
                            searchable: false,
                            className: 'px-4 sm:px-6 py-3.5 text-gray-400 font-medium'
                        },
                        {
                            data: 'name',
                            className: 'px-4 sm:px-6 py-3.5 font-semibold text-gray-800'
                        },
                        {
                            data: 'code',
                            className: 'px-4 sm:px-6 py-3.5 hidden sm:table-cell',
                            render: function(data) {
                                return `<span class="font-mono text-xs bg-gray-100 text-gray-600
                                       px-2 py-0.5 rounded-md">${data}</span>`;
                            }
                        },
                        {
                            data: 'ustadz',
                            orderable: false,
                            className: 'px-4 sm:px-6 py-3.5'
                        },
                        {
                            data: 'capacity',
                            orderable: false,
                            className: 'px-4 sm:px-6 py-3.5 text-center hidden md:table-cell',
                            render: function(data) {
                                if (!data) return '<span class="text-gray-300">—</span>';
                                return `<span class="capacity-pill">
                                    <i class="fas fa-user text-xs opacity-60"></i>${data}
                                </span>`;
                            }
                        },
                        {
                            data: 'status_badge',
                            className: 'px-4 sm:px-6 py-3.5 text-center'
                        },
                        {
                            data: null,
                            orderable: false,
                            searchable: false,
                            className: 'px-4 sm:px-6 py-3.5 text-center',
                            render: function(data, type, row) {
                                return `
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="/classes/${row.id}"
                               class="btn-action btn-view" title="Lihat detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="/classes/${row.id}/members"
                               class="btn-action btn-member" title="Anggota kelas">
                                <i class="fas fa-users"></i>
                            </a>
                            <a href="/classes/${row.id}/edit"
                               class="btn-action btn-edit" title="Edit kelas">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            <button onclick="deleteClass(${row.id})"
                                    class="btn-action btn-delete" title="Hapus kelas">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>`;
                            }
                        }
                    ],
                    columnDefs: [{
                            targets: 2,
                            visible: window.innerWidth > 640
                        },
                        {
                            targets: 4,
                            visible: window.innerWidth > 768
                        }
                    ],
                    language: {
                        processing: '<span class="text-blue-500">Memuat data…</span>',
                        search: '',
                        searchPlaceholder: 'Cari santri, surah…',
                        lengthMenu: 'Tampilkan _MENU_ entri',
                        info: 'Menampilkan _START_–_END_ dari _TOTAL_ kelas',
                        infoEmpty: 'Tidak ada kelas',
                        infoFiltered: '(filter dari _MAX_ total)',
                        paginate: {
                            first: '«',
                            last: '»',
                            next: '›',
                            previous: '‹'
                        },
                        emptyTable: `<div class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14
                                            bg-gray-100 rounded-full mb-3">
                                    <i class="fas fa-chalkboard text-gray-300 text-2xl"></i>
                                </div>
                                <p class="text-gray-400 text-sm">Belum ada kelas</p>
                             </div>`,
                        zeroRecords: `<div class="py-12 text-center">
                                <i class="fas fa-search text-gray-300 text-2xl mb-2 block"></i>
                                <p class="text-gray-400 text-sm">Tidak ada hasil pencarian</p>
                              </div>`
                    },
                    drawCallback: function() {
                        // Move DT controls into our styled slots (already done via dom option)
                    }
                });

                // Responsive column toggle
                $(window).on('resize', function() {
                    table.column(2).visible(window.innerWidth > 640);
                    table.column(4).visible(window.innerWidth > 768);
                });

                // Fetch & populate stat cards
                $.getJSON("{{ route('classes.index') }}", {
                    stats: 1
                }, function(data) {
                    if (data.stats) {
                        $('#total-classes').text(data.stats.total ?? '–');
                        $('#active-classes').text(data.stats.active ?? '–');
                        $('#total-students').text(data.stats.students ?? '–');
                        $('#total-teachers').text(data.stats.teachers ?? '–');
                    }
                });
            });

            function deleteClass(id) {
                if (!confirm('Yakin ingin menghapus kelas ini?')) return;
                $.post(`/classes/${id}`, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    })
                    .done(function(r) {
                        $('#classes-table').DataTable().ajax.reload(null, false);
                        // Optionally show a toast here instead of alert
                        alert(r.message ?? 'Kelas berhasil dihapus.');
                    })
                    .fail(function() {
                        alert('Terjadi kesalahan. Silakan coba lagi.');
                    });
            }
        </script>
    @endpush
@endsection
