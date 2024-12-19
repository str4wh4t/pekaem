<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenilaianReviewer extends Model
{
    //

    protected $table = 'penilaian_reviewer';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function jenis_pkm()
    {
        return $this->hasMany('App\JenisPkm');
    }
}
