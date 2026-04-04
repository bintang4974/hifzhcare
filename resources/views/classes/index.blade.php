@extends('layouts.app-enhanced')
@section('title', 'Manajemen Kelas')
@section('breadcrumb', 'Kelas')

@section('content')
    <div class="space-y-4 md:space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
            <div class="min-w-0">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Manajemen Kelas</h1>
                <p class="text-sm sm:text-base text-gray-600 mt-1">Kelola kelas dan anggota kelas</p>
            </div>
            @can('create_classes')
                <a href="{{ route('classes.create') }}"
                    class="inline-flex items-center justify-center sm:justify-start px-4 sm:px-6 py-2.5 sm:py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm sm:text-base font-semibold rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all flex-shrink-0">
                    <i class="fas fa-plus-circle mr-2 text-sm sm:text-base"></i>
                    <span class="whitespace-nowrap">Tambah Kelas</span>
                </a>
            @endcan
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 md:gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-blue-100 text-xs sm:text-sm mb-1">Total Kelas</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-classes">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-chalkboard text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-green-100 text-xs sm:text-sm mb-1">Kelas Aktif</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="active-classes">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-check-circle text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-purple-100 text-xs sm:text-sm mb-1">Total Santri</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-students">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-users text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg sm:rounded-xl p-3 sm:p-6 text-white shadow-lg">
                <div class="flex flex-col items-start justify-between h-full">
                    <div class="w-full">
                        <p class="text-orange-100 text-xs sm:text-sm mb-1">Total Ustadz</p>
                        <h3 class="text-xl sm:text-3xl font-bold" id="total-teachers">-</h3>
                    </div>
                    <div class="w-10 sm:w-12 h-10 sm:h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mt-2 sm:mt-0">
                        <i class="fas fa-chalkboard-teacher text-lg sm:text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-md overflow-hidden">
            <div class="px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
                <h3 class="text-base sm:text-lg font-bold text-gray-900">Daftar Kelas</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="classes-table" class="min-w-full divide-y divide-gray-200 text-sm sm:text-base">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Nama</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase hidden sm:table-cell">Kode</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-left text-xs font-bold text-gray-600 uppercase">Ustadz</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase hidden md:table-cell">Kapasitas</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                            <th class="px-2 sm:px-6 py-2 sm:py-4 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
        <script>
            let table = $('#classes-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('classes.index') }}",
                columnDefs: [
                    {
                        targets: 2, // Kode column
                        responsivePriority: 3,
                        visible: window.innerWidth > 640
                    },
                    {
                        targets: 4, // Kapasitas column
                        responsivePriority: 4,
                        visible: window.innerWidth > 768,
                        className: 'text-center hidden md:table-cell'
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'code'
                    },
                    {
                        data: 'ustadz',
                        orderable: false
                    },
                    {
                        data: 'capacity',
                        className: 'text-center',
                        orderable: false
                    },
                    {
                        data: 'status_badge',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }
                ],
                language: {
                    processing: 'Memproses...',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ entri',
                    info: 'Menampilkan _START_ hingga _END_ dari _TOTAL_ entri',
                    infoEmpty: 'Menampilkan 0 hingga 0 dari 0 entri',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                },
                drawCallback: function() {
                    // Optional: adjust table on draw
                }
            });

            // Handle window resize for responsive columns
            $(window).on('resize', function() {
                let newWidth = window.innerWidth;
                table.column(2).visible(newWidth > 640);
                table.column(4).visible(newWidth > 768);
            });

            function deleteClass(id) {
                if (confirm('Hapus kelas ini?')) {
                    $.post(`/classes/${id}`, {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        })
                        .done(r => {
                            table.ajax.reload();
                            alert(r.message);
                        });
                }
            }
        </script>
    @endpush
@endsection
