<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UsersTableSeeder extends Seeder
    {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
        {
            $user = new User();
            $faker = \Faker\Factory::create();
            for ($i = 0; $i < 10; $i++) {
                $user->create([
                    'name' => $faker->name(20),
                    'money' => rand(1,1000),
                ]);
            }
        }
    }
