<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUstadzRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()->can('create_users');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone'],
            'password' => ['nullable', 'string', 'min:8'],
            'nip' => ['required', 'string', 'max:50', Rule::unique('ustadz_profiles', 'nip')->where('pesantren_id', auth()->user()->pesantren_id)],
            'specialization' => ['nullable', 'string', 'max:255'],
            'join_date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }
}
