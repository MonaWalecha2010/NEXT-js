<?php

namespace Database\Factories;

use App\Models\Airframe;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AirframeWorkpack>
 */
class AirframeWorkpackFactory extends Factory
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
            'name' => 'Workpack Template-'.fake()->randomNumber(5, true),
            'description' => fake()->text(150),

        ];
    }
}
