<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //

    protected $table = 'review';
    // protected $primaryKey = 'usulan_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->belongsTo('App\UsulanPkm');
    }

    public function status_usulan()
    {
        return $this->belongsTo('App\StatusUsulan');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai');
    }

}
