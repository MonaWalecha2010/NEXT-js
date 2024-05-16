<?php

namespace Database\Seeders;

use App\Models\Workpack;
use Illuminate\Database\Seeder;

class WorkpacksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Now create 10 Workpacks.
        $numberWorkpackPanels = 2;
        $workpackPanels = Workpack::factory($numberWorkpackPanels)->make();
        foreach ($workpackPanels as $workpackPanel) {
            repeat:
            try {
                $workpackPanel->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $this->command->warn($e->getMessage());
                $workpackPanel = WorkpackPanel::factory(1)->make()->first();
                goto repeat;
            }
        }
    }
}
