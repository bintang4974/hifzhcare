<?php

namespace App\Http\Requests\Class;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClassRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('edit_classes');
    }

    public function rules(): array
    {
        $classId = $this->route('class');

        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:50', Rule::unique('classes', 'code')->ignore($classId)->where('pesantren_id', auth()->user()->pesantren_id)],
            'description' => ['nullable', 'string', 'max:1000'],
            'max_capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'status' => ['required', 'in:active,inactive'],
        ];
    }
}
