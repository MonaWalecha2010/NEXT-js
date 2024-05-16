<?php

namespace Database\Seeders;

use App\Models\WorkpackPanelAction;
use Illuminate\Database\Seeder;

class WorkpackPanelActionsSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [ 'Available', 1 ],
            [ 'In Progress', 2 ],
            [ 'Completed', 3 ],
        ];

        foreach ( $data as $datum )
        {
            WorkpackPanelAction::firstOrCreate(
                [ 'name' => $datum[ 0 ] ],
                [ 'order' => $datum[ 1 ] ]
            );
        }
    }
}
