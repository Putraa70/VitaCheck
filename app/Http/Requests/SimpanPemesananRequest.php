<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanPemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'jenis_tes_id'  => ['required', 'exists:jenis_tes,id'],
            'slot_waktu_id' => ['required', 'exists:slot_waktu,id'],
        ];
    }
}
