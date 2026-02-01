@extends('layouts.app-enhanced')
@section('title', 'Kelola Anggota Kelas')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold">{{ $class->name }}</h1>
                <p class="text-gray-600">Kelola ustadz dan santri</p>
            </div>
            <a href="{{ route('classes.show', $class->id) }}"
                class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Manage Ustadz -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-6 flex items-center">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-chalkboard-teacher text-blue-600"></i>
                    </div>
                    Ustadz Pengajar
                </h3>

                @can('assign_ustadz')
                    <form action="{{ route('classes.assign-ustadz', $class->id) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-3">
                            <select name="ustadz_profile_id" class="flex-1 rounded-lg border-gray-300 focus:border-blue-500">
                                @forelse($availableUstadz as $ustadz)
                                    <option value="{{ $ustadz->id }}">{{ $ustadz->user->name }} ({{ $ustadz->nip }})</option>
                                @empty
                                    <option value="">Tidak ada ustadz tersedia</option>
                                @endforelse
                            </select>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg"
                                {{ $availableUstadz->isEmpty() ? 'disabled' : '' }}>
                                <i class="fas fa-plus mr-2"></i>Assign
                            </button>
                        </div>
                    </form>
                @endcan

                <div class="space-y-2">
                    @forelse($class->activeUstadz as $ustadz)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $ustadz->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $ustadz->nip }}</p>
                            </div>
                            @can('assign_ustadz')
                                <button onclick="removeUstadz({{ $ustadz->id }})" class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            @endcan
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada ustadz</p>
                    @endforelse
                </div>
            </div>

            <!-- Manage Santri -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-xl font-bold mb-6 flex items-center">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-users text-green-600"></i>
                    </div>
                    Santri Terdaftar ({{ $class->current_student_count }}/{{ $class->max_capacity }})
                </h3>

                @can('enroll_santri')
                    <form action="{{ route('classes.enroll-santri', $class->id) }}" method="POST" class="mb-6">
                        @csrf
                        <div class="flex gap-3">
                            <select name="santri_profile_id" class="flex-1 rounded-lg border-gray-300 focus:border-green-500">
                                @forelse($availableSantri as $santri)
                                    <option value="{{ $santri->id }}">{{ $santri->user->name }} ({{ $santri->nis }})
                                    </option>
                                @empty
                                    <option value="">Tidak ada santri tersedia</option>
                                @endforelse
                            </select>
                            <button type="submit"
                                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg"
                                {{ $availableSantri->isEmpty() || $class->is_full ? 'disabled' : '' }}>
                                <i class="fas fa-plus mr-2"></i>Daftarkan
                            </button>
                        </div>
                    </form>
                @endcan

                <div class="space-y-2 max-h-96 overflow-y-auto">
                    @forelse($class->activeSantri as $santri)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900">{{ $santri->user->name }}</p>
                                <p class="text-xs text-gray-600">{{ $santri->nis }}</p>
                            </div>
                            @can('enroll_santri')
                                <div class="flex gap-2">
                                    <button onclick="graduateSantri({{ $santri->id }})"
                                        class="text-blue-600 hover:text-blue-700" title="Luluskan">
                                        <i class="fas fa-graduation-cap"></i>
                                    </button>
                                    <button onclick="removeSantri({{ $santri->id }})" class="text-red-600 hover:text-red-700"
                                        title="Keluarkan">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            @endcan
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-8">Belum ada santri</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function removeUstadz(id) {
                if (confirm('Keluarkan ustadz dari kelas ini?')) {
                    $.post(`/classes/{{ $class->id }}/ustadz/${id}`, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    }).done(r => {
                        alert(r.message);
                        location.reload();
                    });
                }
            }

            function removeSantri(id) {
                if (confirm('Keluarkan santri dari kelas ini?')) {
                    $.post(`/classes/{{ $class->id }}/santri/${id}`, {
                        _method: 'DELETE',
                        _token: '{{ csrf_token() }}'
                    }).done(r => {
                        alert(r.message);
                        location.reload();
                    });
                }
            }

            function graduateSantri(id) {
                if (confirm('Luluskan santri ini?')) {
                    $.post(`/classes/{{ $class->id }}/graduate-santri/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }).done(r => {
                        alert(r.message);
                        location.reload();
                    });
                }
            }
        </script>
    @endpush
@endsection
