<?php

use Illuminate\Database\Seeder;

class TemaUsulanPkmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $temas = [
            'Kemandirian pangan, energi, dan air',
            'Kesehatan dan gizi masyarakat',
            'Pencegahan dan Pemberantasan Korupsi',
            'Pemberantasan Kemiskinan',
            'Pencegahan dan Pemberantasan Narkoba',
            'Penguatan pendidikan, sains, dan teknologi',
            'Penguatan kesetaraan gender dan perlindungan hak-hak perempuan, anak, dan penyandang disabilitas',
            'Pelestarian lingkungan dan mitigasi bencana',
            'Pemerataan ekonomi, penguatan UMKM, dan pembangunan Ibu Kota Negara (IKN)',
            'Pelestarian seni budaya dan peningkatan ekonomi kreatif',
        ];

        foreach ($temas as $nama) {
            DB::table('tema_usulan_pkm')->insert([
                'nama_tema' => $nama,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
