<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Insert data ke tabel roles
        DB::table('roles')->insert([
            [
                'id' => 1,
                'role' => 'SUPER',
                'keterangan' => 'Super User',
                'created_at' => '2020-02-05 00:11:18',
                'updated_at' => '2020-02-05 00:11:19',
            ],
            [
                'id' => 2,
                'role' => 'ADMIN',
                'keterangan' => 'Admin Universitas',
                'created_at' => '2020-02-06 21:05:31',
                'updated_at' => '2020-02-06 21:05:32',
            ],
            [
                'id' => 3,
                'role' => 'PEMBIMBING',
                'keterangan' => 'Dosen Pendamping',
                'created_at' => '2020-02-10 03:38:17',
                'updated_at' => '2020-02-10 03:38:17',
            ],
            [
                'id' => 4,
                'role' => 'REVIEWER',
                'keterangan' => 'Reviewer',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
            [
                'id' => 5,
                'role' => 'WD1',
                'keterangan' => 'Wakil Dekan I',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
            [
                'id' => 6,
                'role' => 'ADMINFAKULTAS',
                'keterangan' => 'Admin Fakultas',
                'created_at' => '2020-02-06 21:05:31',
                'updated_at' => '2020-02-06 21:05:32',
            ],
        ]);
    }
}
