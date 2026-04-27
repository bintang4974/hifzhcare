<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateUstadzRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_users');
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Get pesantren_id: from request (Super Admin) or session (Regular Admin)
        $pesantrenId = $this->user()->isSuperAdmin() 
            ? $this->pesantren_id 
            : session('current_pesantren_id');

        return [
            // Pesantren selection (Super Admin only)
            'pesantren_id' => $this->user()->isSuperAdmin() 
                ? ['required', 'exists:pesantrens,id'] 
                : ['nullable'],

            // User data
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,NULL,id,pesantren_id,' . $pesantrenId
            ],
            'password' => ['nullable', 'string', 'min:8'],

            // Ustadz profile data
            'nip' => [
                'required',
                'string',
                'max:50',
                'unique:ustadz_profiles,nip,NULL,id,pesantren_id,' . $pesantrenId
            ],
            'specialization' => ['nullable', 'string', 'max:255'],
            'join_date' => ['required', 'date', 'before_or_equal:today'],
            'address' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'pesantren_id' => 'pesantren',
            'name' => 'nama lengkap',
            'email' => 'email',
            'phone' => 'nomor HP',
            'password' => 'password',
            'nip' => 'NIP',
            'specialization' => 'spesialisasi',
            'join_date' => 'tanggal bergabung',
            'address' => 'alamat',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    public function messages(): array
    {
        return [
            'pesantren_id.required' => 'Pesantren wajib dipilih.',
            'pesantren_id.exists' => 'Pesantren yang dipilih tidak valid.',

            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',

            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',

            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar di pesantren ini.',

            'password.min' => 'Password minimal 8 karakter.',

            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar di pesantren ini.',

            'join_date.required' => 'Tanggal bergabung wajib diisi.',
            'join_date.date' => 'Format tanggal tidak valid.',
            'join_date.before_or_equal' => 'Tanggal bergabung tidak boleh lebih dari hari ini.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Auto-fill pesantren_id from session only for non-Super Admin
        if (!$this->user()->isSuperAdmin()) {
            $this->merge([
                'pesantren_id' => session('current_pesantren_id'),
            ]);
        }
    }
}
