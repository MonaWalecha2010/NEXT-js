<?php

namespace Database\Seeders;

use App\Models\SchematicPanel;
use Illuminate\Database\Seeder;

class SchematicPanelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Now create 10 Users. We have to uses the trycatch to cope with faker not creating unique 'code' per user
        $numberDiagramPanels = 1750;
        $diagramPanels = SchematicPanel::factory($numberDiagramPanels)->make();
        foreach ($diagramPanels as $diagramPanel) {
            repeat:
            try {
                $diagramPanel->save();
            } catch (\Illuminate\Database\QueryException $e) {
                $this->command->warn($e->getMessage());
                $diagramPanel = SchematicPanel::factory(1)->make()->first();
                goto repeat;
            }
        }
    }
}
