<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';
    protected $dateFormat = 'Y-m-d H:i:s';
    protected $fillable = ['status_aplikasi', 'tahun_dipilih'];

    /**
     * Tahun yang dipilih secara global untuk filter data (usulan_pkm, target_pkm_tahunan, dll).
     *
     * @return int
     */
    public static function getTahunDipilih()
    {
        $s = self::first();
        return $s && $s->tahun_dipilih ? (int) $s->tahun_dipilih : (int) date('Y');
    }
}
