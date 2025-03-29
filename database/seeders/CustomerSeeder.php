<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        /* $faker = Faker::create();

        // Create 50 sample customers
        for ($i = 0; $i < 50; $i++) {
            Customer::create([
                'first_name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'phone' => $faker->unique()->numerify('09#########'), // Philippine mobile format
                'address' => $faker->streetAddress(),
                'city' => $faker->city(),
                'state' => $faker->state(),
            ]);
        } */
    }
}
