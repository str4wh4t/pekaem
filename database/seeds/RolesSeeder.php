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
                'keterangan' => 'Untuk user level super user',
                'created_at' => '2020-02-05 00:11:18',
                'updated_at' => '2020-02-05 00:11:19',
            ],
            [
                'id' => 2,
                'role' => 'ADMIN',
                'keterangan' => 'Untuk user admin atau pengelola PKM',
                'created_at' => '2020-02-06 21:05:31',
                'updated_at' => '2020-02-06 21:05:32',
            ],
            [
                'id' => 3,
                'role' => 'PEMBIMBING',
                'keterangan' => 'Untuk user dosen pembimbing PKM',
                'created_at' => '2020-02-10 03:38:17',
                'updated_at' => '2020-02-10 03:38:17',
            ],
            [
                'id' => 4,
                'role' => 'REVIEWER',
                'keterangan' => 'Untuk user reviewer',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
            [
                'id' => 5,
                'role' => 'WD1-TASKFORCE',
                'keterangan' => 'Untuk user wd1 atau taskforce',
                'created_at' => '2020-02-10 03:38:55',
                'updated_at' => '2020-02-10 03:38:55',
            ],
        ]);
    }
}
