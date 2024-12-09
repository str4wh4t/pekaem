<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    //
    protected $table = 'pegawai';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    public function pegawai_roles()
    {
        return $this->hasMany('App\PegawaiRoles');
    }

    public function roles()
    {
        return $this->belongsToMany('App\Roles');
    }

    public function usulan_pkm()
    {
        return $this->hasMany('App\UsulanPkm');
    }

    public function revisi()
    {
        return $this->hasMany('App\Revisi');
    }

}
