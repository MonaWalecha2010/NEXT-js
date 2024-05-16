<?php

namespace Database\Factories;

use App\Models\Aeroplane;
use App\Models\Panel;
use App\Models\Workpack;
use App\Models\WorkpackPanelAction;
use App\Models\WorkpackPanelStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkpackPanel>
 */
class WorkpackPanelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'aeroplane_id' => function () {
                if ($aeroplane = Aeroplane::inRandomOrder()->first()) {
                    return $aeroplane->id;
                }
            },
            'workpack_id' => function () {
                if ($workpack = Workpack::inRandomOrder()->first()) {
                    return $workpack->id;
                }
            },
            'panel_id' => function () {
                if ($diagram = Panel::inRandomOrder()->first()) {
                    return $diagram->id;
                }
            },
            'workpack_panel_action_id' => function () {
                if ($action = WorkpackPanelAction::inRandomOrder()->first()) {
                    return $action->id;
                }
            },
            'workpack_panel_step_id' => function () {
                if ($step = WorkpackPanelStep::inRandomOrder()->first()) {
                    return $step->id;
                }
            },
            'description' => fake()->text(100),
        ];
    }
}
