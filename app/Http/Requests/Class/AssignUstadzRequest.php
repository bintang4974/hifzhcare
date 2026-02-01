<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;

class AssignUstadzRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('assign_ustadz');
    }

    public function rules(): array
    {
        return [
            'ustadz_profile_id' => ['required', 'exists:ustadz_profiles,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'ustadz_profile_id.required' => 'Ustadz harus dipilih.',
            'ustadz_profile_id.exists' => 'Ustadz tidak ditemukan.',
        ];
    }
}
