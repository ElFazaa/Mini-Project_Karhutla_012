<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilterLaporanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:150'],
            'wilayah_id' => ['nullable', 'integer', 'exists:wilayah,id'],
            'status' => ['nullable', 'in:baru,perlu_perbaikan,perlu_diteruskan,diteruskan,selesai'],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
