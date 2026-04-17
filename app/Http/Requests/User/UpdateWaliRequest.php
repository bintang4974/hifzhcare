<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWaliRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('edit_users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $waliProfile = $this->route('wali');
        $waliId = is_object($waliProfile) ? $waliProfile->id : $waliProfile;
        $pesantrenId = session('current_pesantren_id');

        return [
            // User data
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $this->getWaliUserId($waliProfile)
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,' . $this->getWaliUserId($waliProfile) . ',id,pesantren_id,' . $pesantrenId
            ],

            // Wali profile data
            'nik' => [
                'nullable',
                'digits:16',
                'unique:wali_profiles,nik,' . $waliId . ',id,pesantren_id,' . $pesantrenId
            ],
            'relation' => ['required', 'in:ayah,ibu,wali'],
            'occupation' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama lengkap',
            'email' => 'email',
            'phone' => 'nomor HP',
            'nik' => 'NIK',
            'relation' => 'hubungan',
            'occupation' => 'pekerjaan',
            'address' => 'alamat',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar di pesantren ini.',
            
            'nik.digits' => 'NIK harus 16 digit.',
            'nik.unique' => 'NIK sudah terdaftar di pesantren ini.',
            
            'relation.required' => 'Hubungan dengan santri wajib diisi.',
            'relation.in' => 'Hubungan harus salah satu dari: Ayah, Ibu, atau Wali.',
            
            'occupation.max' => 'Pekerjaan maksimal 100 karakter.',
        ];
    }

    /**
     * Get user ID from wali profile
     */
    protected function getWaliUserId($waliProfile)
    {
        // If it's already a model instance
        if ($waliProfile instanceof \App\Models\WaliProfile) {
            return $waliProfile->user_id;
        }
        
        // If it's an ID, find the model
        $wali = \App\Models\WaliProfile::find($waliProfile);
        return $wali ? $wali->user_id : null;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean NIK (remove non-numeric characters)
        if ($this->filled('nik')) {
            $this->merge([
                'nik' => preg_replace('/\D/', '', $this->nik),
            ]);
        }
    }
}
