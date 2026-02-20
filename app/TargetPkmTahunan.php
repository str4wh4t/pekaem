<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TargetPkmTahunan extends Model
{
    //
    protected $table = 'target_pkm_tahunan';
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $fillable = [
        'tahun',
        'kode_fakultas',
        'jumlah_mahasiswa_aktif',
        'target_usulan_pkm',
    ];

    public function fakultas()
    {
        return $this->belongsTo('App\Fakultas', 'kode_fakultas', 'kodeF');
    }
}
