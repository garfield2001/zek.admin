<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,           // First, create users
            MealTypeSeeder::class,       // Then meal types
            DishSeeder::class,           // Then dishes (needs meal types)
            PackageSeeder::class,        // Then packages
            CateringSettingSeeder::class, // Finally settings
        ]);
    }
}
