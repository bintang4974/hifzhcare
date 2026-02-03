<?php

namespace App\Http\Requests\Ustadz;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUstadzRequest extends FormRequest
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
        $ustadzId = $this->route('ustadz');
        $pesantrenId = session('current_pesantren_id');

        return [
            // User data
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                'unique:users,email,' . $this->getUstadzUserId($ustadzId)
            ],
            'phone' => [
                'required',
                'string',
                'max:20',
                'unique:users,phone,' . $this->getUstadzUserId($ustadzId) . ',id,pesantren_id,' . $pesantrenId
            ],

            // Ustadz profile data
            'nip' => [
                'required',
                'string',
                'max:50',
                'unique:ustadz_profiles,nip,' . $ustadzId . ',id,pesantren_id,' . $pesantrenId
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
            'name' => 'nama lengkap',
            'email' => 'email',
            'phone' => 'nomor HP',
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
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.max' => 'Nama lengkap maksimal 255 karakter.',
            
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            
            'phone.required' => 'Nomor HP wajib diisi.',
            'phone.unique' => 'Nomor HP sudah terdaftar di pesantren ini.',
            
            'nip.required' => 'NIP wajib diisi.',
            'nip.unique' => 'NIP sudah terdaftar di pesantren ini.',
            
            'join_date.required' => 'Tanggal bergabung wajib diisi.',
            'join_date.date' => 'Format tanggal tidak valid.',
            'join_date.before_or_equal' => 'Tanggal bergabung tidak boleh lebih dari hari ini.',
        ];
    }

    /**
     * Get user ID from ustadz profile
     */
    protected function getUstadzUserId($ustadzId)
    {
        $ustadz = \App\Models\UstadzProfile::find($ustadzId);
        return $ustadz ? $ustadz->user_id : null;
    }
}
