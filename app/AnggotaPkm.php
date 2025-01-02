<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnggotaPkm extends Model
{
    //

    protected $table = 'anggota_pkm';
    // protected $primaryKey = 'anggota_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->belongsTo('App\UsulanPkm');//
    }

    public function mhs()
    {
        return $this->belongsTo('App\Mhs');
    }
}
