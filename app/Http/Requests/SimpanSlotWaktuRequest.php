<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SimpanSlotWaktuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->peran === 'admin';
    }
    public function rules(): array
    {
        return [
            'jenis_tes_id' => 'required|exists:jenis_tes,id',
            'tanggal'      => 'required|date|after_or_equal:today',
            'mulai'        => 'required|date_format:H:i',
            'selesai'      => 'required|date_format:H:i|after:mulai',
            'kuota'        => 'required|integer|min:1|max:200',
        ];
    }
}
