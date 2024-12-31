<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsulanPkmDokumen extends Model
{
    //

    protected $table = 'usulan_pkm_dokumen';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'usulan_pkm_id',
        'document_path'
    ];

    public function usulan_pkm()
    {
        return $this->belongsTo('App\UsulanPkm');
    }
}
