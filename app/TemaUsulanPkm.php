<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TemaUsulanPkm extends Model
{
    //

    protected $table = 'tema_usulan_pkm';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->hasMany('App\UsulanPkm');
    }
}
