@extends('layouts.app-enhanced')

@section('title', 'Tambah Hafalan')

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-4xl">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tambah Hafalan Baru</h1>
            <p class="text-gray-600">Input data hafalan santri</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('hafalan.store') }}" method="POST" enctype="multipart/form-data" id="hafalan-form">
                @csrf

                <!-- Class Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                    <select name="class_id" id="class-select"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('class_id') border-red-500 @enderror">
                        <option value="">Pilih Kelas</option>
                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}" 
                                @if(old('class_id') == $class->id) selected 
                                @elseif(request('class_id') == $class->id) selected 
                                @endif>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Santri Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Santri <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user-select" required
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('user_id') border-red-500 @enderror"
                        @if(auth()->user()->isUstadz() && !request('class_id')) disabled @endif>
                        @if(auth()->user()->isUstadz())
                            @if(request('class_id'))
                                <option value="">Memuat santri...</option>
                            @else
                                <option value="">Pilih Kelas Terlebih Dahulu</option>
                            @endif
                        @else
                            <option value="">Pilih Santri</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        @endif
                    </select>
                    @error('user_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Surah & Ayat -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <!-- Surah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Surah <span class="text-red-500">*</span>
                        </label>
                        <select name="surah_number" id="surah-select" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('surah_number') border-red-500 @enderror">
                            <option value="">Pilih Surah</option>
                            @foreach ($surahs as $surah)
                                <option value="{{ $surah['number'] }}" data-max-ayat="{{ $surah['total_ayat'] }}"
                                    {{ old('surah_number') == $surah['number'] ? 'selected' : '' }}>
                                    {{ $surah['number'] }}. {{ $surah['name_latin'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('surah_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ayat Start -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ayat Awal <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="ayat_start" id="ayat-start" min="1"
                            value="{{ old('ayat_start', 1) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('ayat_start') border-red-500 @enderror">
                        @error('ayat_start')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Ayat End -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ayat Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="ayat_end" id="ayat-end" min="1"
                            value="{{ old('ayat_end', 1) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('ayat_end') border-red-500 @enderror">
                        @error('ayat_end')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500" id="ayat-info">Jumlah ayat: <span id="ayat-count">0</span></p>
                    </div>
                </div>

                <!-- Type & Date -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <!-- Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Hafalan <span class="text-red-500">*</span>
                        </label>
                        <select name="type" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('type') border-red-500 @enderror">
                            <option value="setoran" {{ old('type') == 'setoran' ? 'selected' : '' }}>Setoran</option>
                            <option value="murajah" {{ old('type') == 'murajah' ? 'selected' : '' }}>Muraja'ah</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Tanggal Hafalan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="hafalan_date" value="{{ old('hafalan_date', date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('hafalan_date') border-red-500 @enderror">
                        @error('hafalan_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Audio Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rekaman Audio
                    </label>

                    <!-- Recording Controls -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <div id="audio-controls" class="space-y-4">
                            <!-- Record Button -->
                            <div id="record-section">
                                <button type="button" id="btn-record"
                                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                                    <i class="fas fa-microphone mr-2"></i>
                                    <span id="record-text">Mulai Rekam</span>
                                </button>
                                <p class="mt-2 text-sm text-gray-600">
                                    Atau upload file audio (MP3, WAV, M4A - Max 10MB)
                                </p>
                            </div>

                            <!-- Recording Status -->
                            <div id="recording-status" class="hidden">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-4 h-4 bg-red-600 rounded-full animate-pulse"></div>
                                    <span class="text-lg font-semibold text-red-600">Merekam...</span>
                                    <span id="recording-timer" class="text-lg font-mono">00:00</span>
                                </div>
                            </div>

                            <!-- Audio Player (after recording) -->
                            <div id="audio-player-section" class="hidden">
                                <audio id="audio-player" controls class="w-full mb-3"></audio>
                                <div class="flex gap-2 justify-center">
                                    <button type="button" id="btn-rerecord"
                                        class="bg-yellow-600 hover:bg-yellow-700 text-white font-semibold px-4 py-2 rounded-lg">
                                        <i class="fas fa-redo mr-2"></i>Rekam Ulang
                                    </button>
                                    <button type="button" id="btn-remove-audio"
                                        class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded-lg">
                                        <i class="fas fa-trash mr-2"></i>Hapus Audio
                                    </button>
                                </div>
                            </div>

                            <!-- File Upload -->
                            <div>
                                <input type="file" name="audio_file" id="audio-upload"
                                    accept="audio/mp3,audio/wav,audio/m4a,audio/webm,audio/ogg" class="hidden">
                                <button type="button" onclick="document.getElementById('audio-upload').click()"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                                    <i class="fas fa-upload mr-2"></i>Upload File Audio
                                </button>
                                <p id="upload-filename" class="mt-2 text-sm text-gray-600"></p>
                            </div>
                        </div>
                    </div>

                    @error('audio_file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                    <textarea name="notes" rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring focus:ring-blue-200 @error('notes') border-red-500 @enderror"
                        placeholder="Tambahkan catatan...">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3">
                    <a href="{{ route('hafalan.index') }}"
                        class="flex-1 bg-gray-500 hover:bg-gray-600 text-white font-semibold px-6 py-3 rounded-lg text-center transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-3 rounded-lg transition">
                        <i class="fas fa-save mr-2"></i>Simpan Hafalan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script>
        // Audio Recording Variables
        let mediaRecorder;
        let audioChunks = [];
        let recordingInterval;
        let recordingSeconds = 0;
        let audioBlob;

        // Calculate Ayat Count - outside ready for global access
        function updateAyatCount() {
            const startInput = document.getElementById('ayat-start');
            const endInput = document.getElementById('ayat-end');
            const countDisplay = document.getElementById('ayat-count');

            if (!startInput || !endInput || !countDisplay) {
                console.error('Elements not found', {
                    startInput,
                    endInput,
                    countDisplay
                });
                return;
            }

            const start = parseInt(startInput.value) || 1;
            const end = parseInt(endInput.value) || 1;
            const count = Math.max(0, end - start + 1);

            console.log('updateAyatCount:', {
                start,
                end,
                count
            });
            countDisplay.textContent = count;
        }

        $(document).ready(function() {
            console.log('Document ready - initializing form');

            // Function to load santri for a class
            function loadSantriForClass(classId, selectUserId = null) {
                const userSelect = $('#user-select');

                if (!classId) {
                    // If no class selected, disable santri dropdown
                    userSelect.html('<option value="">Pilih Kelas Terlebih Dahulu</option>');
                    userSelect.prop('disabled', true);
                    return;
                }

                // Enable the dropdown and show loading state
                userSelect.prop('disabled', false);
                userSelect.html('<option value="">Memuat santri...</option>');

                // Load santri for selected class via AJAX
                $.ajax({
                    url: '/hafalan/classes/' + classId + '/santri',
                    type: 'GET',
                    dataType: 'json',
                    timeout: 5000,
                    success: function(data) {
                        console.log('Santri loaded successfully for class', classId, ':', data);
                        let html = '<option value="">Pilih Santri</option>';
                        
                        if (data.length === 0) {
                            html = '<option value="">Tidak ada santri di kelas ini</option>';
                            userSelect.prop('disabled', true);
                        } else {
                            $.each(data, function(index, santri) {
                                html += '<option value="' + santri.id + '">' + santri.name + '</option>';
                            });
                            userSelect.prop('disabled', false);
                        }
                        
                        userSelect.html(html);
                        
                        // Pre-select santri if selectUserId is provided
                        if (selectUserId) {
                            console.log('Pre-selecting santri:', selectUserId);
                            userSelect.val(selectUserId);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading santri:', status, error, xhr);
                        userSelect.html('<option value="">Error - Coba Lagi</option>');
                        userSelect.prop('disabled', true);
                        alert('Gagal memuat daftar santri: ' + (xhr.responseJSON?.error || error));
                    }
                });
            }

            // Handle class selection to load santri dynamically
            $('#class-select').on('change', function() {
                const classId = $(this).val();
                loadSantriForClass(classId);
            });

            // On page load: if a class is already selected (from form submission or query param), load its santri
            const initialClassId = $('#class-select').val();
            const queryUserId = '{{ request("user_id") }}';
            
            if (initialClassId) {
                console.log('Page loaded with class selected:', initialClassId);
                // If user_id is also in query params, pass it to pre-select
                if (queryUserId) {
                    console.log('Pre-selecting user from query param:', queryUserId);
                    loadSantriForClass(initialClassId, queryUserId);
                } else {
                    loadSantriForClass(initialClassId);
                }
            } else {
                // Initialize: If no class is selected, disable santri dropdown
                $('#user-select').prop('disabled', true);
            }

            // Update max ayat based on surah
            $('#surah-select').on('change', function() {
                const maxAyat = $(this).find(':selected').data('max-ayat');
                console.log('Surah changed, max ayat:', maxAyat);
                if (maxAyat) {
                    $('#ayat-start').attr('max', maxAyat);
                    $('#ayat-end').attr('max', maxAyat);
                }
                updateAyatCount();
            });

            // Bind multiple events for ayat inputs
            const ayatInputs = document.getElementById('ayat-start');
            if (ayatInputs) {
                ayatInputs.addEventListener('input', updateAyatCount);
                ayatInputs.addEventListener('change', updateAyatCount);
                ayatInputs.addEventListener('keyup', updateAyatCount);
            }

            const ayatEndInput = document.getElementById('ayat-end');
            if (ayatEndInput) {
                ayatEndInput.addEventListener('input', updateAyatCount);
                ayatEndInput.addEventListener('change', updateAyatCount);
                ayatEndInput.addEventListener('keyup', updateAyatCount);
            }

            // Initialize on page load
            updateAyatCount();

            // Audio Upload Handler
            $('#audio-upload').on('change', function() {
                const file = this.files[0];
                if (file) {
                    $('#upload-filename').text('File: ' + file.name);
                    // Hide recording section if file uploaded
                    $('#record-section').hide();
                    $('#audio-player-section').hide();
                }
            });

            // Audio Recording
            $('#btn-record').on('click', async function() {
                if (!mediaRecorder || mediaRecorder.state === 'inactive') {
                    await startRecording();
                } else {
                    stopRecording();
                }
            });

            // Re-record
            $('#btn-rerecord').on('click', async function() {
                $('#audio-player-section').addClass('hidden');
                $('#record-section').removeClass('hidden');
                document.getElementById('audio-upload').value = '';
            });

            // Remove audio
            $('#btn-remove-audio').on('click', function() {
                $('#audio-player-section').addClass('hidden');
                $('#record-section').removeClass('hidden');
                document.getElementById('audio-upload').value = '';
                $('#upload-filename').text('');
            });
        });

        async function startRecording() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                recordingSeconds = 0;

                mediaRecorder.ondataavailable = (event) => {
                    audioChunks.push(event.data);
                };

                mediaRecorder.onstop = () => {
                    audioBlob = new Blob(audioChunks, {
                        type: 'audio/webm'
                    });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    $('#audio-player').attr('src', audioUrl);
                    $('#audio-player-section').removeClass('hidden');
                    $('#record-section').addClass('hidden');
                    $('#recording-status').addClass('hidden');

                    // Create File object from Blob
                    const file = new File([audioBlob], 'recording.webm', {
                        type: 'audio/webm'
                    });
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    document.getElementById('audio-upload').files = dataTransfer.files;
                };

                mediaRecorder.start();
                $('#record-section').addClass('hidden');
                $('#recording-status').removeClass('hidden');

                // Update timer
                recordingInterval = setInterval(() => {
                    recordingSeconds++;
                    const mins = Math.floor(recordingSeconds / 60).toString().padStart(2, '0');
                    const secs = (recordingSeconds % 60).toString().padStart(2, '0');
                    $('#recording-timer').text(`${mins}:${secs}`);
                }, 1000);

            } catch (error) {
                alert('Error accessing microphone: ' + error.message);
            }
        }

        function stopRecording() {
            if (mediaRecorder && mediaRecorder.state === 'recording') {
                mediaRecorder.stop();
                mediaRecorder.stream.getTracks().forEach(track => track.stop());
                clearInterval(recordingInterval);
            }
        }
    </script>
@endpush
