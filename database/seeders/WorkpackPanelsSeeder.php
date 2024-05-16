<?php

namespace Database\Seeders;

use App\Models\WorkpackPanel;
use Illuminate\Database\Seeder;

class WorkpackPanelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Now create 10 Workpackpanels. We have to uses the trycatch to avoid issues with non-unique combinations of Workpack and Panel ids
        $numberWorkpackPanels = 450;
        $workpackPanels = WorkpackPanel::factory($numberWorkpackPanels)->make();
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
