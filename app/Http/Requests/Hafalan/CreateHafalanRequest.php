<?php

namespace App\Http\Requests\Hafalan;

use App\Support\Helpers\QuranHelper;
use Illuminate\Foundation\Http\FormRequest;

class CreateHafalanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled by middleware/policy
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'class_id' => ['nullable', 'exists:classes,id'],
            'surah_number' => ['required', 'integer', 'min:1', 'max:114'],
            'ayat_start' => ['required', 'integer', 'min:1'],
            'ayat_end' => ['required', 'integer', 'gte:ayat_start'],
            'type' => ['required', 'in:setoran,murajah'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'audio_file' => ['nullable', 'file', 'mimes:mp3,webm,ogg,wav,m4a', 'max:10240'], // 10MB
            'hafalan_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'Santri harus dipilih.',
            'user_id.exists' => 'Santri tidak ditemukan.',
            'class_id.exists' => 'Kelas tidak ditemukan.',
            'surah_number.required' => 'Surah harus dipilih.',
            'surah_number.min' => 'Nomor surah minimal 1.',
            'surah_number.max' => 'Nomor surah maksimal 114.',
            'ayat_start.required' => 'Ayat awal harus diisi.',
            'ayat_start.min' => 'Ayat awal minimal 1.',
            'ayat_end.required' => 'Ayat akhir harus diisi.',
            'ayat_end.gte' => 'Ayat akhir harus lebih besar atau sama dengan ayat awal.',
            'type.required' => 'Jenis hafalan harus dipilih.',
            'type.in' => 'Jenis hafalan tidak valid.',
            'audio_file.file' => 'File audio tidak valid.',
            'audio_file.mimes' => 'Format audio harus: mp3, webm, ogg, wav, atau m4a.',
            'audio_file.max' => 'Ukuran audio maksimal 10MB.',
            'hafalan_date.required' => 'Tanggal hafalan harus diisi.',
            'hafalan_date.date' => 'Format tanggal tidak valid.',
            'hafalan_date.before_or_equal' => 'Tanggal hafalan tidak boleh di masa depan.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-fill created_by_user_id
        $this->merge([
            'created_by_user_id' => auth()->id(),
        ]);

        // Auto-fill pesantren_id if user is in pesantren
        if (auth()->user()->pesantren_id) {
            $this->merge([
                'pesantren_id' => auth()->user()->pesantren_id,
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Validate ayat range
            $surahNumber = $this->surah_number;
            $ayatStart = $this->ayat_start;
            $ayatEnd = $this->ayat_end;

            if ($surahNumber && $ayatStart && $ayatEnd) {
                $maxAyat = QuranHelper::getMaxAyat($surahNumber);

                if ($ayatEnd > $maxAyat) {
                    $surahName = QuranHelper::getSurahName($surahNumber);
                    $validator->errors()->add(
                        'ayat_end',
                        "Surah {$surahName} hanya memiliki {$maxAyat} ayat."
                    );
                }
            }
        });
    }
}
