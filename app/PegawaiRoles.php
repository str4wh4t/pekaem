<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PegawaiRoles extends Model
{
    //
    protected $table = 'pegawai_roles';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function roles()
    {
        return $this->belongsTo('App\Roles');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai');
    }

    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas', 'fakultas_id', 'kodeF');
    }
}
