<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Super Administrator',
            'Administrator',
            'Mechanic',
            'Engineer',
            'C Engineer',
        ];

        foreach ($data as $datum) {
            Role::firstOrCreate(
                ['name' => $datum],
                ['slug' => Str::slug($datum)]
            );
        }
    }
}
