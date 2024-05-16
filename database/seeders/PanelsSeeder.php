<?php

namespace Database\Seeders;

use App\Models\Panel;
use Illuminate\Database\Seeder;

class PanelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $numberPanels = 250;
        $panels = Panel::factory($numberPanels)->make();
        foreach ($panels as $panel) {
            repeat:
            try {
                $panel->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $this->command->warn($e->getMessage());
                $panel = Panel::factory(1)->make()->first();
                goto repeat;
            }
        }
    }
}
