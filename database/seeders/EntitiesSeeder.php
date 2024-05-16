<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'airframe',
            'airframe_workpack_panel',
            'aeroplane',
            'diagram',
            'panel',
            'workpack',
            'task',
        ];

        foreach ($data as $datum) {
            Entity::firstOrCreate(
                ['name' => $datum],
            );
        }
    }
}
