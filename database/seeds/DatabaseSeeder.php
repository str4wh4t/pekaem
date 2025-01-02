<?php
use App\MappingFakultas;
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
        $this->call(RolesSeeder::class);
        $this->call(MappingFakultas::class);
        $this->call(StatusUsulanSeeder::class);
    }
}
