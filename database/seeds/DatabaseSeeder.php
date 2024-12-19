<?php

use Beta\Microsoft\Graph\Model\Status;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(StatusUsulanSeeder::class);
        $this->call(RolesSeeder::class);
    }
}
