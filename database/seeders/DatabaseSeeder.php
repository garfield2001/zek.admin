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
            UserSeeder::class,
            MenuTypeSeeder::class,
            MenuSeeder::class,
            PackageSeeder::class,
            AddonCategorySeeder::class,
            AddonPackageSeeder::class,
            AddonPackageMenuSeeder::class,
            CustomerSeeder::class,
            CateringSettingSeeder::class,
            // Add other seeders...
        ]);
    }
}
