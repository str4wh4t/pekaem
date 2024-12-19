<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPkm extends Model
{
    //

    protected $table = 'jenis_pkm';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function kategori_kegiatan()
    {
        return $this->belongsTo('App\KategoriKegiatan');
    }

    public function kategori_kriteria()
    {
        return $this->belongsTo('App\KategoriKriteria');
    }

    public function usulan_pkm()
    {
        return $this->hasMany('App\UsulanPKM');
    }
}
