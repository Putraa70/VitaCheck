<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $user = $this->user();
        $profilId = optional($user->profilMahasiswa)->id;

        return [
            // USERS
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // PROFIL_MAHASISWA
            'nim' => [
                'required',
                'string',
                'max:20',
                Rule::unique('profil_mahasiswas', 'nim')->ignore($profilId),
            ],
            'program_studi_id' => ['required', Rule::exists('program_studis', 'id')],
            'no_hp' => ['nullable', 'string', 'max:30'],
            // kalau mau lebih ketat:
            // 'no_hp' => ['nullable', 'regex:/^(\+62|0)[0-9]{8,15}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah terdaftar.',
            'program_studi_id.required' => 'Program studi wajib dipilih.',
            'program_studi_id.exists' => 'Program studi tidak ditemukan.',
        ];
    }
}
