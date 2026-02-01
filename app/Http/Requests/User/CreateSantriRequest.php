<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSantriRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create_users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            // User data
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['nullable', 'string', 'min:8'],

            // Santri profile
            'nis' => ['required', 'string', 'max:50', Rule::unique('santri_profiles', 'nis')->where('pesantren_id', auth()->user()->pesantren_id)],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:L,P'],
            'address' => ['required', 'string', 'max:500'],
            'entry_date' => ['required', 'date', 'before_or_equal:today'],

            // Wali data (optional - will check for existing)
            'wali_id' => ['nullable', 'exists:wali_profiles,id'],
            'wali_name' => ['required_without:wali_id', 'string', 'max:255'],
            'wali_email' => ['nullable', 'email'],
            'wali_phone' => ['required_without:wali_id', 'string', 'max:20'],
            'wali_nik' => ['nullable', 'string', 'max:16'],
            'wali_relation' => ['required_without:wali_id', 'in:ayah,ibu,wali'],
            'wali_occupation' => ['nullable', 'string', 'max:255'],
            'wali_address' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama santri harus diisi.',
            'phone.required' => 'Nomor telepon harus diisi.',
            'phone.unique' => 'Nomor telepon sudah terdaftar.',
            'nis.required' => 'NIS harus diisi.',
            'nis.unique' => 'NIS sudah terdaftar di pesantren ini.',
            'birth_date.required' => 'Tanggal lahir harus diisi.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'gender.required' => 'Jenis kelamin harus dipilih.',
            'address.required' => 'Alamat harus diisi.',
            'entry_date.required' => 'Tanggal masuk harus diisi.',
            'wali_name.required_without' => 'Nama wali harus diisi jika tidak memilih wali yang ada.',
            'wali_phone.required_without' => 'Nomor telepon wali harus diisi.',
            'wali_relation.required_without' => 'Hubungan wali harus dipilih.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Generate random password if not provided
        if (!$this->password) {
            $this->merge([
                'password' => \Illuminate\Support\Str::random(10),
            ]);
        }
    }
}
