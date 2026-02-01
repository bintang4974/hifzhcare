<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class RequestCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('request_certificate');
    }

    public function rules(): array
    {
        return [
            'juz_completed' => ['required', 'integer', 'min:1', 'max:30'],
        ];
    }

    public function messages(): array
    {
        return [
            'juz_completed.required' => 'Juz yang diselesaikan harus diisi.',
            'juz_completed.min' => 'Juz minimal 1.',
            'juz_completed.max' => 'Juz maksimal 30.',
        ];
    }
}
