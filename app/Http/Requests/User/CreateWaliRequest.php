<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class CreateWaliRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create_users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['nullable', 'string', 'min:8'],
            'nik' => ['nullable', 'string', 'max:16'],
            'relation' => ['required', 'in:ayah,ibu,wali'],
            'occupation' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
        ];
    }
}
