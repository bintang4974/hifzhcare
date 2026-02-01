<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;

class EnrollSantriRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('enroll_santri');
    }

    public function rules(): array
    {
        return [
            'santri_profile_id' => ['required', 'exists:santri_profiles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'santri_profile_id.required' => 'Santri harus dipilih.',
            'santri_profile_id.exists' => 'Santri tidak ditemukan.',
        ];
    }
}
