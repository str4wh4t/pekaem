<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Perbaikan extends Model
{
    //

    protected $table = 'perbaikan';
    // protected $primaryKey = 'usulan_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->belongsTo('App\UsulanPkm');
    }

    public function mhs()
    {
        return $this->belongsTo('App\Mhs');
    }

}
