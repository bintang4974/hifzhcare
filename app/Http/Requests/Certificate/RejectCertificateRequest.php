<?php

namespace App\Http\Requests\Certificate;

use Illuminate\Foundation\Http\FormRequest;

class RejectCertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('approve_certificate');
    }

    public function rules(): array
    {
        return [
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Alasan penolakan harus diisi.',
        ];
    }
}
