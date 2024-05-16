<?php

namespace Database\Seeders;

use App\Models\WorkpackPanelTask;
use Illuminate\Database\Seeder;

class WorkpackPanelTasksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberWorkpackPanelTasks = 2500;
        $workpackPanelTasks = WorkpackPanelTask::factory($numberWorkpackPanelTasks)->make();

        foreach ($workpackPanelTasks as $workpackPanelTask) {
            repeat:
            try {
                $workpackPanelTask->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $this->command->warn($e->getMessage());
                $workpackPanelTask = WorkpackPanel::factory(1)->make()->first();
                goto repeat;
            }
        }
    }
}
