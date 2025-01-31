<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusUsulanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert data ke tabel status_usulan
        DB::table('status_usulan')->insert([
            ['id' => 1, 'keterangan' => 'BARU', 'urutan' => 0, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'keterangan' => 'MENUNGGU', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'keterangan' => 'DITOLAK', 'urutan' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'keterangan' => 'DISETUJUI', 'urutan' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'keterangan' => 'LANJUT', 'urutan' => 3, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'keterangan' => 'LOLOS', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'keterangan' => 'GAGAL', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'keterangan' => 'SUDAH_DINILAI', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'keterangan' => 'BELUM_DINILAI', 'urutan' => 4, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
