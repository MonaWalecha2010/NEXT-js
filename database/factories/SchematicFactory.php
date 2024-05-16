<?php

namespace Database\Factories;

use App\Models\Airframe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schematic>
 */
class SchematicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                if ($user = User::inRandomOrder()->first()) {
                    return $user->id;
                }
            },
            'airframe_id' => function () {
                if ($airframe = Airframe::inRandomOrder()->first()) {
                    return $airframe->id;
                }
            },
            'name' => 'Figure '.fake()->randomDigitNotNull().': '.fake()->randomElements(['LH', 'RH'])[0].' '.fake()->randomElements(['LOWER', 'UPPER'])[0].fake()->randomElements(['DOORS', 'COMPOSITE MATERIAL PARTS'])[0],
            'image' => fake()->imageUrl(300, 300, 'diagram', true),
        ];
    }
}
