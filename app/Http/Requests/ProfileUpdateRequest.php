<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        // Jika user terdaftar biasa (bukan Google), wajib memasukkan current_password saat ganti password
        if ($this->user()->provider !== 'google') {
            $rules['current_password'] = ['required_with:password', 'nullable', 'string', 'current_password'];
        } else {
            $rules['current_password'] = ['nullable', 'string'];
        }

        return $rules;
    }
}
