<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StatusUsulan extends Model
{
    //

    protected $table = 'status_usulan';
    // protected $primaryKey = 'usulan_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->hasMany('App\UsulanPKM');
    }

    public function revisi()
    {
        return $this->hasMany('App\Revisi');
    }

}
