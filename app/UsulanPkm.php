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

    public function tema_usulan_pkm()
    {
        return $this->belongsTo('App\TemaUsulanPkm');
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

    public function usulan_pkm_dokumen()
    {
        return $this->hasMany('App\UsulanPkmDokumen');
    }

    public function getJudulAttribute($value)
    {
        // Bersihkan \xC2\xA0 (non-breaking space → Â)
        $clean = str_replace("\xC2\xA0", ' ', $value);
        $clean = preg_replace('/[^\x00-\x7F]/', '', $clean);

        // Rapikan spasi berlebihan
        return trim(preg_replace('/\s+/', ' ', $clean));
    }
}
