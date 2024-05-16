<?php

namespace Database\Factories;

use App\Models\Aeroplane;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Workpack>
 */
class WorkpackFactory extends Factory
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
            'aeroplane_id' => function () {
                if ($aeroplane = Aeroplane::inRandomOrder()->first()) {
                    return $aeroplane->id;
                }
            },
            'name' => 'Workpack-'.fake()->randomNumber(5, true),
            'description' => fake()->text(150),

        ];
    }
}
