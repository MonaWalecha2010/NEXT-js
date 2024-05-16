<?php

namespace Database\Factories;

use App\Models\Aeroplane;
use App\Models\User;
use App\Models\Workpack;
use App\Models\WorkpackPanel;
use App\Models\WorkpackPanelAction;
use App\Models\WorkpackPanelStep;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkpackPanelTask>
 */
class WorkpackPanelTaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user = $workpack = User::inRandomOrder()->first();
        $notes = new \stdClass();
        $notes->comment = fake()->text();

        return [
            'user_id' => function () use ( $user ) {
                if ($user) {
                    return $user->id;
                }
            },
            'user_code' => function () use ( $user ) {
                if ($user) {
                    return $user->code;
                }
            },
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
            'workpack_panel_id' => function () {
                if ($workpackPanel = WorkpackPanel::inRandomOrder()->first()) {
                    return $workpackPanel->id;
                }
            },
            'workpack_panel_step_id' => function () {
                if ($workpackPanelStatus = WorkpackPanelStep::inRandomOrder()->first()) {
                    return $workpackPanelStatus->id;
                }
            },
            'workpack_panel_action_id' => function () {
                if ($workpackPanelAction = WorkpackPanelAction::inRandomOrder()->first()) {
                    return $workpackPanelAction->id;
                }
            },
            'notes' => json_encode( $notes ),
            'created_at' => fake()->date(),
            'updated_at' => fake()->date(),
        ];
    }
}
