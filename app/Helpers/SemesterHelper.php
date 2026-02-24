<?php

namespace App\Helpers;

use App\Mhs;
use App\UsulanPkm;

class SemesterHelper
{
    /**
     * Hitung semester saat ini berdasarkan smt_masuk dan tahun_masuk mahasiswa
     * 
     * @param Mhs $mhs Model mahasiswa
     * @param UsulanPkm|null $usulan_pkm Model usulan PKM (optional, jika ada akan menggunakan semester yang sudah tersimpan)
     * @return int Semester saat ini
     */
    public static function calculateSemester(Mhs $mhs, ?UsulanPkm $usulan_pkm = null)
    {
        // Jika sudah ada semester di usulan_pkm, gunakan itu
        if ($usulan_pkm && !empty($usulan_pkm->semester)) {
            return (int)$usulan_pkm->semester;
        }

        // Hitung semester berdasarkan smt_masuk dan tahun_masuk
        $tahun_sekarang = (int)date('Y');
        $bulan_sekarang = (int)date('m');
        $tahun_masuk = !empty($mhs->tahun_masuk) ? (int)$mhs->tahun_masuk : $tahun_sekarang;
        $smt_masuk = !empty($mhs->smt_masuk) ? (int)$mhs->smt_masuk : 1;
        
        // Tentukan semester saat ini berdasarkan bulan
        // Semester 1: Januari (1) - Juli (7)
        // Semester 2: Agustus (8) - Desember (12)
        $smt_sekarang = ($bulan_sekarang >= 1 && $bulan_sekarang <= 7) ? 1 : 2;
        
        // Hitung selisih tahun
        $selisih_tahun = $tahun_sekarang - $tahun_masuk;
        
        // Hitung semester ke berapa
        if ($selisih_tahun == 0) {
            // Tahun masuk sama dengan tahun sekarang
            if ($smt_masuk == 1) {
                // Masuk semester 1, semester saat ini = smt_sekarang
                $semester = $smt_sekarang;
            } else {
                // Masuk semester 2
                // Jika sekarang masih smt 2 tahun yang sama, berarti semester 1
                // Jika sekarang sudah smt 1 tahun berikutnya, berarti semester 2
                $semester = ($smt_sekarang == 2) ? 1 : 2;
            }
        } else {
            // Hitung semester berdasarkan selisih tahun
            if ($smt_masuk == 1) {
                // Masuk semester 1
                // Semester = (selisih_tahun * 2) + smt_sekarang
                $semester = ($selisih_tahun * 2) + $smt_sekarang;
            } else {
                // Masuk semester 2
                // Jika sekarang smt 1: semester = (selisih_tahun * 2)
                // Jika sekarang smt 2: semester = (selisih_tahun * 2) + 1
                $semester = ($selisih_tahun * 2) + ($smt_sekarang == 2 ? 1 : 0);
            }
        }
        
        // Pastikan semester minimal 1
        return max(1, $semester);
    }
}
