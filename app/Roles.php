<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    //
    protected $table = 'roles';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function pegawai_roles()
    {
        return $this->hasMany('App\PegawaiRoles');
    }
    
    public function pegawai()
    {
        return $this->belongsToMany('App\Pegawai');
    }
}
