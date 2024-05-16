<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airframe>
 */
class AirframeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->numerify('Airframe-Model'),
            'revision' => fake()->numerify('####-##GZ'),
            'description' => fake()->text(),
        ];
    }
}
