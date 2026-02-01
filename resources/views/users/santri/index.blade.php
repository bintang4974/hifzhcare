@extends('layouts.app-enhanced')

@section('title', 'Manajemen Santri')
@section('breadcrumb', 'Pengguna / Santri')

@section('content')
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Santri</h1>
                <p class="text-gray-600 mt-1">Kelola data santri dan monitor perkembangan hafalan</p>
            </div>
            <a href="{{ route('users.santri.create') }}"
                class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                <i class="fas fa-plus-circle mr-2"></i>
                Tambah Santri
            </a>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-3xl font-bold" id="total-santri">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Aktif</p>
                        <h3 class="text-3xl font-bold" id="active-santri">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm mb-1">Pending</p>
                        <h3 class="text-3xl font-bold" id="pending-santri">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Lulus</p>
                        <h3 class="text-3xl font-bold" id="graduated-santri">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Card -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                    <i class="fas fa-filter mr-2 text-blue-600"></i>
                    Filter & Pencarian
                </h3>
                <button onclick="toggleFilters()" class="lg:hidden text-gray-600 hover:text-gray-900">
                    <i class="fas fa-chevron-down transition-transform" id="filter-toggle-icon"></i>
                </button>
            </div>

            <div id="filter-section" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-info-circle mr-1 text-gray-400"></i>
                        Status
                    </label>
                    <select id="filter-status"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="active">Aktif</option>
                        <option value="inactive">Tidak Aktif</option>
                        <option value="graduated">Lulus</option>
                    </select>
                </div>

                <!-- Class Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-chalkboard mr-1 text-gray-400"></i>
                        Kelas
                    </label>
                    <select id="filter-class"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        <option value="">Semua Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Gender Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-venus-mars mr-1 text-gray-400"></i>
                        Jenis Kelamin
                    </label>
                    <select id="filter-gender"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 transition">
                        <option value="">Semua</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-end gap-2">
                    <button onclick="applyFilters()"
                        class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-search mr-2"></i>Filter
                    </button>
                    <button onclick="resetFilters()"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-4 py-2.5 rounded-lg shadow-md hover:shadow-lg transition-all transform hover:-translate-y-0.5">
                        <i class="fas fa-redo mr-2"></i>Reset
                    </button>
                </div>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold text-gray-900">Daftar Santri</h3>
                    <div class="flex items-center space-x-2">
                        <button onclick="refreshTable()"
                            class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition"
                            title="Refresh">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <button onclick="exportData()"
                            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Table Container -->
            <div class="overflow-x-auto">
                <table id="santri-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Santri
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">NIS
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Gender</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Usia
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Wali
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase tracking-wider">Kelas
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Progress</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase tracking-wider">Aksi
                            </th>
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
    <div id="activate-modal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full transform transition-all" x-data="{ show: false }"
            x-show="show" x-init="setTimeout(() => show = true, 100)">
            <div class="p-6">
                <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-full mb-4">
                    <i class="fas fa-user-check text-2xl text-green-600"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Aktivasi Akun Santri</h3>
                <p class="text-gray-600 mb-6">Akun santri akan diaktifkan dan dapat login ke sistem.</p>

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
                            class="flex-1 px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
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
        #filter-section {
            max-height: 500px;
            transition: max-height 0.3s ease;
        }

        #filter-section.collapsed {
            max-height: 0;
            overflow: hidden;
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
            // Initialize DataTable
            table = $('#santri-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
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
                    processing: '<div class="flex items-center justify-center"><i class="fas fa-spinner fa-spin text-3xl text-blue-600 mr-3"></i><span class="text-gray-700">Memuat data...</span></div>',
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ santri",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 santri",
                    infoFiltered: "(disaring dari _MAX_ total santri)",
                    zeroRecords: '<div class="text-center py-12"><i class="fas fa-search text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Tidak ada data yang ditemukan</p></div>',
                    emptyTable: '<div class="text-center py-12"><i class="fas fa-users-slash text-6xl text-gray-300 mb-4"></i><p class="text-gray-500 text-lg">Belum ada data santri</p></div>',
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

        function toggleFilters() {
            const section = document.getElementById('filter-section');
            const icon = document.getElementById('filter-toggle-icon');
            section.classList.toggle('collapsed');
            icon.classList.toggle('rotate-180');
        }

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
