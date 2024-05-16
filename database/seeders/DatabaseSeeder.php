<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Aeroplane;
use App\Models\Airframe;
use App\Models\Owner;
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
        $this->call(TrainingRolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(EntitiesSeeder::class);
        $this->call(WorkpackPanelStatusesSeeder::class);
        $this->call(WorkpackPanelActionsSeeder::class);
//        Airframe::factory(5)->create();
        Owner::factory(3)->create();
//        Aeroplane::factory(15)->create();
//        $this->call(PanelsSeeder::class);
//        $this->call(SchematicSeeder::class);
//        $this->call(SchematicPanelsSeeder::class);
//        $this->call(WorkpacksSeeder::class);
//        $this->call(WorkpackPanelsSeeder::class);
//        $this->call(WorkpackPanelTasksSeeder::class);
//        $this->call(AirframeWorkpacksSeeder::class);
//        $this->call(AirframeWorkpackPanelsSeeder::class);
    }
}
