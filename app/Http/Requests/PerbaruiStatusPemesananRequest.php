<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerbaruiStatusPemesananRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->peran === 'admin';
    }
    public function rules(): array
    {
        return ['status' => 'required|in:menunggu,terkonfirmasi,check_in,selesai,dibatalkan'];
    }
}
