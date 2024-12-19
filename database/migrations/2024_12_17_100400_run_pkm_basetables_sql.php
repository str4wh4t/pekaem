<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RunPkmBasetablesSql extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Path ke file SQL
        $filePath = database_path('sql/pkm_base_tables.sql');

        // Cek jika file SQL ada
        if (File::exists($filePath)) {
            // Membaca isi file SQL
            $sql = File::get($filePath);

            // Menjalankan SQL
            DB::unprepared($sql);
        } else {
            throw new \Exception("File SQL tidak ditemukan di: $filePath");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tabel sesuai nama tabel di file SQL jika ingin rollback
        // DB::statement('DROP TABLE IF EXISTS mhs');
    }
}
