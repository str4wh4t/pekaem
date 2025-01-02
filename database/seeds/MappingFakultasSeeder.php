<?php
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MappingFakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('mapping_fakultas')->insert([
            ['id' => 1, 'unit_id' => '1', 'kodeF' => '01', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'unit_id' => '2', 'kodeF' => '02', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'unit_id' => '3', 'kodeF' => '03', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'unit_id' => '4', 'kodeF' => '04', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'unit_id' => '5', 'kodeF' => '05', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'unit_id' => '6', 'kodeF' => '06', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'unit_id' => '7', 'kodeF' => '07', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'unit_id' => '8', 'kodeF' => '08', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'unit_id' => '9', 'kodeF' => '09', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'unit_id' => '10', 'kodeF' => '10', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'unit_id' => '11', 'kodeF' => '11', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'unit_id' => '12', 'kodeF' => '12', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'unit_id' => '13', 'kodeF' => '13', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'unit_id' => '14', 'kodeF' => '14', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
