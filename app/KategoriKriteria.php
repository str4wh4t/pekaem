<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriKriteria extends Model
{
    protected $table = 'kategori_kriteria';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function kriteria_penilaian()
    {
        return $this->hasMany('App\KriteriaPenilaian');
    }

    public function jenis_pkm()
    {
        return $this->hasMany('App\JenisPkm');
    }
}
