<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JenisPkm extends Model
{
    //

    protected $table = 'jenis_pkm';
    // protected $primaryKey = 'usulan_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->hasMany('App\UsulanPKM');
    }

}
