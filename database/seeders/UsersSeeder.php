<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (config('jetworks145.admin_name')) {
            User::firstOrCreate(
                ['email' => config('jetworks145.admin_email')],
                [
                    'role_id' => 1,
                    'training_role_id' => 1,
                    'fname' => 'Clive',
                    'lname' => 'Sweeting',
                    'name' => config('jetworks145.admin_name'),
                    'password' => bcrypt(config('jetworks145.admin_password')),
                    'signature' => fake()->imageUrl(800, 120, 'signature', true),
                    'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
                    'phone' => fake()->phoneNumber(),
                    'code' => fake()->numerify('######'),
                    'color' => 'red',
                ]
            );

            User::firstOrCreate(
                    ['email' => 'ben@wai.uk'],
                [
                    'role_id' => 2,
                    'training_role_id' => 3,
                    'fname' => 'Ben',
                    'lname' => 'Hewson',
                    'name' => 'Ben Hewson',
                    'password' => bcrypt(config('jetworks145.admin_password')),
                    'signature' => fake()->imageUrl(800, 120, 'signature', true),
                    'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
                    'phone' => fake()->phoneNumber(),
                    'code' => fake()->numerify('######'),
                    'color' => 'red',
                ]
            );


            User::firstOrCreate(
                ['email' => 'mechanic@jetworks.wai.uk'],
                [
                    'role_id' => 3,
                    'training_role_id' => 5,
                    'fname' => 'Mechanic',
                    'lname' => '@Jetworks',
                    'name' => 'Mechanic @jetworks',
                    'password' => bcrypt( 'Mechanic111!!!' ),
                    'signature' => fake()->imageUrl(800, 120, 'signature', true),
                    'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
                    'phone' => fake()->phoneNumber(),
                    'code' => fake()->numerify('######'),
                    'color' => 'blue',
                ]
            );

            User::firstOrCreate(
                ['email' =>'engineer@jetworks.wai.uk'],
                [
                    'role_id' => 4,
                    'training_role_id' => 2,
                    'fname' => 'Engineer',
                    'lname' => '@Jetworks',
                    'name' => 'Engineer @jetworks',
                    'password' => bcrypt( 'Engineer999???' ),
                    'signature' => fake()->imageUrl(800, 120, 'signature', true),
                    'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
                    'phone' => fake()->phoneNumber(),
                    'code' => fake()->numerify('######'),
                    'color' => 'blue',
                ]
            );

            User::firstOrCreate(
                ['email' =>'cengineer@jetworks.wai.uk'],
                [
                    'role_id' => 5,
                    'training_role_id' => 4,
                    'fname' => 'Senior Engineer',
                    'lname' => '@Jetworks',
                    'name' => 'Senior Engineer @jetworks',
                    'password' => bcrypt( 'CEngineer666$$$' ),
                    'signature' => fake()->imageUrl(800, 120, 'signature', true),
                    'stamp' => fake()->imageUrl(300, 300, 'stamp', true),
                    'phone' => fake()->phoneNumber(),
                    'code' => fake()->numerify('######'),
                    'color' => 'green',
                ]
            );
        }

        //Now create 10 Users. We have to uses the trycatch to cope with faker not creating unique 'code' per user
//        $numberusers = 10;
//        $users = User::factory($numberusers)->make();
//        foreach ($users as $user) {
//            repeat:
//            try {
//                $user->save();
//            } catch (\Illuminate\Database\QueryException $e) {
//                $this->command->warn($e->getMessage());
//                $user = User::factory(1)->make()->first();
//                goto repeat;
//            }
//        }
    }
}
