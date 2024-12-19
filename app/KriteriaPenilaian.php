<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class KriteriaPenilaian extends Model
{
    //

    protected $table = 'kriteria_penilaian';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function kategori_kriteria()
    {
        return $this->belongsTo('App\KategoriKriteria');
    }

    public function penilaian_reviewer()
    {
        return $this->hasMany('App\PenilaianReviewer');
    }
}
