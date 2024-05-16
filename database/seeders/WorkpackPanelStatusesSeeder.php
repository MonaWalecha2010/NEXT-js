<?php

namespace Database\Seeders;

use App\Models\WorkpackPanelStep;
use Illuminate\Database\Seeder;

class WorkpackPanelStatusesSeeder extends Seeder {

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //God I hate using American spellings, but let's be consistent with Tailwind naming...
        $data = [
            [ 'Pending Removal', 1, 'gray' ],
            [ 'Removal', 2, 'red' ],
            [ 'Inspect Clear', 3, 'orange' ],
            [ 'Install', 4, 'cyan' ],
            [ 'Inspect Install ', 5, 'blue' ],
            [ 'Seal',6, 'green' ],
//            [ 'Complete', 7, 'black' ],
        ];

        foreach ( $data as $datum )
        {
            WorkpackPanelStep::firstOrCreate(
                [ 'name' => $datum[ 0 ] ],
                [ 'order' => $datum[ 1 ] ],
                [ 'color' => $datum[ 2 ] ],
            );
        }
    }
}
