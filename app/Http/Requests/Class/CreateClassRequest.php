<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create_classes');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('classes', 'code')->where('pesantren_id', auth()->user()->pesantren_id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kelas harus diisi.',
            'code.unique' => 'Kode kelas sudah digunakan.',
            'max_capacity.required' => 'Kapasitas maksimal harus diisi.',
            'max_capacity.min' => 'Kapasitas minimal 1 santri.',
            'max_capacity.max' => 'Kapasitas maksimal 100 santri.',
        ];
    }
}
