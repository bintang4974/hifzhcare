<?php

namespace App\Http\Requests\AppreciationFund;

use Illuminate\Foundation\Http\FormRequest;

class VerifyAppreciationFundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('verify_appreciation_fund');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'in:verified,rejected'],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }
}
