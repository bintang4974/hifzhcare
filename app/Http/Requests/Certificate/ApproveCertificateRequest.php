<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class ApproveCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('approve_certificate');
    }

    public function rules(): array
    {
        return [
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
