<?php

namespace Database\Factories;

use App\Models\Airframe;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Panel>
 */
class PanelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'airframe_id' => function () {
                if ($airframe = Airframe::inRandomOrder()->first()) {
                    return $airframe->id;
                }
            },
            'name' => fake()->numerify('PANEL####'),
            'description' => fake()->text(),
        ];
    }
}
