<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KategoriKegiatan extends Model
{
    //

    protected $table = 'kategori_kegiatan';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function jenis_pkm()
    {
        return $this->hasMany('App\JenisPkm');
    }
}
