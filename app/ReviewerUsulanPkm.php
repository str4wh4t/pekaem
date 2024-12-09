<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReviewerUsulanPkm extends Model
{
    //
    protected $table = 'reviewer_usulan_pkm';
    protected $dateFormat = 'Y-m-d H:i:s';

    public function usulan_pkm()
    {
        return $this->belongsTo('App\UsulanPkm');
    }

    public function reviewer()
    {
        return $this->belongsTo('App\reviewer');
    }
}
