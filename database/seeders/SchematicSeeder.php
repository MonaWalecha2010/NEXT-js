<?php

namespace Database\Seeders;

use App\Models\Schematic;
use Illuminate\Database\Seeder;

class SchematicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberDiagrams = 15;
        $diagrams = Schematic::factory($numberDiagrams)->make();
        foreach ($diagrams as $diagram) {
            repeat:
            try {
                $diagram->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $this->command->warn($e->getMessage());
                $diagram = Schematic::factory(1)->make()->first();
                goto repeat;
            }
        }
    }
}
