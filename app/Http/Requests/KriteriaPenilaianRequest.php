<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KriteriaPenilaianRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_kriteria' => [
                'required',
                'string',
                'max:255',
            ],
            'urutan' => [
                'required',
                'integer',
                'gt:0',
            ],
            'bobot' => [
                'required',
                'integer',
            ],
        ];
    }
}
