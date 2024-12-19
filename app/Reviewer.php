<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reviewer extends Model
{
    //
    protected $table = 'reviewer';
    // protected $dateFormat = 'Y-m-d H:i:s';
    public $timestamps = false;

    public function usulan_pkm()
    {
        return $this->belongsToMany('App\UsulanPkm');
    }

    public function reviewer_usulan_pkm()
    {
        return $this->hasMany('App\ReviewerUsulanPkm');
    }

    public function penilaian_reviewer()
    {
        return $this->hasMany('App\PenilaianReviewer');
    }
}
