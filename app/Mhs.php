<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mhs extends Model
{
    //
    // protected $table = 'mahasiswa';
    protected $primaryKey = 'nim'; // or null
    public $incrementing = false;
    // In Laravel 6.0+ make sure to also set $keyType
    protected $keyType = 'string';

    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    public function anggota_pkm()
    {
        return $this->hasMany('App\AnggotaPkm');
    }

    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas', 'kode_fakultas', 'kodeF');
    }
}
