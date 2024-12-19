<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JenisPkmRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_pkm' => [
                'required',
                'string',
                'max:255',
            ],
            'kategori_kriteria_id' => 'required|integer|exists:kategori_kriteria,id',
            'keterangan' => [
                'required',
                'string',
                'max:255',
            ],
            'score_min' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
