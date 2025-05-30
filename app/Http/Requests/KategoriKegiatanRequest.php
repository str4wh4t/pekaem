<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KategoriKegiatanRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_kategori_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
            'deskripsi' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }
}
