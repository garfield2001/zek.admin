<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 10; $i++) {
            Customer::create([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'phone' => $faker->numerify('09#########'),
                'address' => $faker->address,
                'city' => $faker->city,
                'state' => $faker->state,
            ]);
        }
    }
}
