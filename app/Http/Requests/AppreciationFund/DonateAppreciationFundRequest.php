<?php

namespace App\Http\Requests\AppreciationFund;

use Illuminate\Foundation\Http\FormRequest;

class DonateAppreciationFundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('donate_appreciation_fund');
    }

    public function rules(): array
    {
        return [
            'ustadz_profile_id' => ['required', 'exists:ustadz_profiles,id'],
            'santri_profile_id' => ['required', 'exists:santri_profiles,id'],
            'amount' => ['required', 'numeric', 'min:10000', 'max:10000000'],
            'notes' => ['nullable', 'string', 'max:500'],
            'proof_of_payment' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'ustadz_profile_id.required' => 'Ustadz harus dipilih.',
            'santri_profile_id.required' => 'Santri harus dipilih.',
            'amount.required' => 'Jumlah donasi harus diisi.',
            'amount.min' => 'Jumlah donasi minimal Rp 10.000.',
            'amount.max' => 'Jumlah donasi maksimal Rp 10.000.000.',
            'proof_of_payment.required' => 'Bukti pembayaran harus diupload.',
            'proof_of_payment.mimes' => 'Format file harus JPG, PNG, atau PDF.',
            'proof_of_payment.max' => 'Ukuran file maksimal 2MB.',
        ];
    }
}
