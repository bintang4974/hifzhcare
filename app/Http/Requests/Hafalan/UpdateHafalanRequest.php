<?php

namespace App\Http\Requests\Hafalan;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHafalanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $rules = parent::rules();

        // Make audio_file optional for update
        $rules['audio_file'] = ['nullable', 'file', 'mimes:mp3,webm,ogg,wav,m4a', 'max:10240'];

        return $rules;
    }
}
