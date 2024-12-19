<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsulanPkm extends Model
{
    //

    protected $table = 'usulan_pkm';
    // protected $primaryKey = 'usulan_pkm_id';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function anggota_pkm()
    {
        return $this->hasMany('App\AnggotaPkm');
    }

    public function mhs()
    {
        return $this->belongsTo('App\Mhs');
    }

    public function jenis_pkm()
    {
        return $this->belongsTo('App\JenisPkm');
    }

    public function status_usulan()
    {
        return $this->belongsTo('App\StatusUsulan');
    }

    public function pegawai()
    {
        return $this->belongsTo('App\Pegawai');
    }

    public function revisi()
    {
        return $this->hasMany('App\Revisi');
    }

    public function penilaian_reviewer()
    {
        return $this->hasMany('App\PenilaianReviewer');
    }

    public function review()
    {
        return $this->hasMany('App\Review');
    }

    public function perbaikan()
    {
        return $this->hasMany('App\Perbaikan');
    }

    public function reviewer()
    {
        return $this->belongsToMany('App\Reviewer');
    }

    public function reviewer_usulan_pkm()
    {
        return $this->hasMany('App\ReviewerUsulanPkm');
    }
}
