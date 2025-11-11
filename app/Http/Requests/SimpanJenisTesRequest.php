<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanJenisTesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->peran === 'admin';
    }
    public function rules(): array
    {
        return [
            'kode' => 'required|string|max:50|unique:jenis_tes,kode,' . ($this->jenis_te->id ?? 'null'),
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'biaya' => 'nullable|integer|min:0',
            'aktif' => 'required|boolean',
        ];
    }
}
