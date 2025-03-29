<?php

namespace Database\Seeders;

use App\Models\AddonPackage;
use Illuminate\Database\Seeder;

class AddonPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'category_id' => 1, // Lechon category
                'name' => 'Package A',
                'description' => 'Premium lechon belly package for 15 pax. Includes 1 whole lechon belly, 3 side dishes of your choice, special lechon sauce, and steamed rice. Perfect for intimate gatherings.',
                'meal_limit' => 4,
                'serving_size' => 15,
                'price' => 4000,
                'status' => 'active'
            ],
            [
                'category_id' => 1, // Lechon category
                'name' => 'Package B',
                'description' => 'Deluxe lechon belly package for 15 pax. Includes 1 whole lechon belly, 4 side dishes of your choice, special lechon sauce, steamed rice, and dessert. Ideal for family celebrations.',
                'meal_limit' => 5,
                'serving_size' => 15,
                'price' => 5000,
                'status' => 'active'
            ],
            [
                'category_id' => 1, // Lechon category
                'name' => 'Package C',
                'description' => 'Premium plus lechon belly package for 15 pax. Includes 1 whole lechon belly, 4 side dishes of your choice, special lechon sauce, pancit canton, steamed rice, and 2 desserts. Perfect for special occasions.',
                'meal_limit' => 5,
                'serving_size' => 15,
                'price' => 6500,
                'status' => 'active'
            ],
            STUCKED BETWEEN ADJUSTING ADD-ONS SIDE 
            BECAUSE OF THE COMPLEXITY ADD ON PACKAGES



            // Add more addon packages...
        ];

        foreach ($packages as $package) {
            AddonPackage::create($package);
        }
    }
}
