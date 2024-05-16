<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $fname = fake()->firstName();
        $lname = fake()->lastName();
        $role_id = random_int(2,5);
        $color = ( $role_id == 5 ) ? 'green' :  'blue';

        return [
            'role_id' => $role_id,
            'fname' => $lname,
            'lname' => $fname,
            'name' => $fname . " " . $lname,
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt(config('jetworks145.admin_password')),
            'remember_token' => Str::random(10),
            'signature' => fake()->imageUrl(800, 120, 'signature', true),
            'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
            'phone' => fake()->phoneNumber(),
            'code' => fake()->numerify('######'),
            'color' => $color,
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
