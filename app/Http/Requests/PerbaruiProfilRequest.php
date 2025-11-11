<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerbaruiProfilRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'nim'              => 'required|string|max:20|unique:profil_mahasiswa,nim,' . optional($this->user()->profilMahasiswa)->id,
            'program_studi_id' => 'required|exists:program_studi,id',
            'no_hp'            => 'nullable|string|max:30',
        ];
    }
}
