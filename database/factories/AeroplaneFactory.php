<?php

namespace Database\Factories;

use App\Models\Airframe;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Aeroplane>
 */
class AeroplaneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->numerify('Aeroplane-Tailnumber-####'),
            'description' => fake()->text(),
            'owner_id' => function () {
                if ($owner = Owner::inRandomOrder()->first()) {
                    return $owner->id;
                }
            },
            'airframe_id' => function () {
                if ($airframe = Airframe::inRandomOrder()->first()) {
                    return $airframe->id;
                }
            },
        ];
    }
}
