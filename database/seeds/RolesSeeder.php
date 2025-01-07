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
                'keterangan' => 'LEVEL SUPER USER',
                'created_at' => '2020-02-05 00:11:18',
                'updated_at' => '2020-02-05 00:11:19',
            ],
            [
                'id' => 2,
                'role' => 'ADMIN',
                'keterangan' => 'ADMIN UNIVERSITAS',
                'created_at' => '2020-02-06 21:05:31',
                'updated_at' => '2020-02-06 21:05:32',
            ],
            [
                'id' => 3,
                'role' => 'PEMBIMBING',
                'keterangan' => 'DOSEN PEMBIMBING',
                'created_at' => '2020-02-10 03:38:17',
                'updated_at' => '2020-02-10 03:38:17',
            ],
            [
                'id' => 4,
                'role' => 'REVIEWER',
                'keterangan' => 'REVIEWER',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
            [
                'id' => 5,
                'role' => 'WD1',
                'keterangan' => 'WD1',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
            [
                'id' => 6,
                'role' => 'ADMINFAKULTAS',
                'keterangan' => 'ADMIN FAKULTAS',
                'created_at' => '2020-02-06 21:05:31',
                'updated_at' => '2020-02-06 21:05:32',
            ],
        ]);
    }
}
