<?php

namespace Database\Seeders;

use App\Models\TrainingRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrainingRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'Training Admin',
            'Training Officer',
            'Training Manager',
            'Training Engineer',
            'Training Mechanic',
        ];

        foreach ($data as $datum) {
            TrainingRole::firstOrCreate(
                ['name' => $datum],
                ['slug' => Str::slug($datum)]
            );
        }
    }
}
