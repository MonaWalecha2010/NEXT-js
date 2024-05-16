<?php

namespace Database\Factories;

use App\Models\AirframeWorkpack;
use App\Models\Panel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AirframeWorkpackPanel>
 */
class AirframeWorkpackPanelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'airframe_workpack_id' => function () {
                if ($airframe_workpack = AirframeWorkpack::inRandomOrder()->first()) {
                    return $airframe_workpack->id;
                }
            },
            'panel_id' => function () {
                if ($diagram = Panel::inRandomOrder()->first()) {
                    return $diagram->id;
                }
            },
            'description' => fake()->text(100),
        ];
    }
}
