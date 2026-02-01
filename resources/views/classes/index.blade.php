@extends('layouts.app-enhanced')
@section('title', 'Manajemen Kelas')
@section('breadcrumb', 'Kelas')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Manajemen Kelas</h1>
                <p class="text-gray-600 mt-1">Kelola kelas dan anggota kelas</p>
            </div>
            @can('create_classes')
                <a href="{{ route('classes.create') }}"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all">
                    <i class="fas fa-plus-circle mr-2"></i>Tambah Kelas
                </a>
            @endcan
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm mb-1">Total Kelas</p>
                        <h3 class="text-3xl font-bold" id="total-classes">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm mb-1">Kelas Aktif</p>
                        <h3 class="text-3xl font-bold" id="active-classes">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm mb-1">Total Santri</p>
                        <h3 class="text-3xl font-bold" id="total-students">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-2xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-orange-100 text-sm mb-1">Total Ustadz</p>
                        <h3 class="text-3xl font-bold" id="total-teachers">-</h3>
                    </div>
                    <div class="w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chalkboard-teacher text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-bold text-gray-900">Daftar Kelas</h3>
            </div>

            <div class="overflow-x-auto">
                <table id="classes-table" class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Nama Kelas</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Kode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Ustadz</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">Kapasitas</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">Status</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-600 uppercase">Aksi</th>
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
                        searchable: false
                    }
                ]
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
