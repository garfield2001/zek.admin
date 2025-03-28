<?php

namespace Database\Seeders;

use App\Models\AddonCategory;
use App\Models\AddonPackage;
use Illuminate\Database\Seeder;

class AddonPackageSeeder extends Seeder
{
    public function run(): void
    {
        // Get categories
        $bellychonCategory = AddonCategory::where('name', 'Bellychon Package')->first();
        $foodTrayCategory = AddonCategory::where('name', 'Food Tray Menu')->first();

        // Bellychon Packages
        $bellychonPackages = [
            [
                'category_id' => $bellychonCategory->id,
                'name' => 'Package A',
                'description' => 'Lechon Belly 3kgs with sides',
                'serving_size' => 15,
                'price' => 4000,
                'status' => 'active'
            ],
            [
                'category_id' => $bellychonCategory->id,
                'name' => 'Package B',
                'description' => 'Lechon Belly 3kgs with additional sides',
                'serving_size' => 15,
                'price' => 5000,
                'status' => 'active'
            ],
            [
                'category_id' => $bellychonCategory->id,
                'name' => 'Package C',
                'description' => 'Lechon Belly 5kgs with sides',
                'serving_size' => 25,
                'price' => 6500,
                'status' => 'active'
            ]
        ];

        // Food Tray Packages (acting as menu types)
        $foodTrayPackages = [
            [
                'category_id' => $foodTrayCategory->id,
                'name' => 'Beef Trays',
                'description' => 'Selection of beef dishes',
                'serving_size' => 15,
                'status' => 'active'
            ],
            [
                'category_id' => $foodTrayCategory->id,
                'name' => 'Seafoods Trays',
                'description' => 'Selection of seafood dishes',
                'serving_size' => 15,
                'status' => 'active'
            ],
            [
                'category_id' => $foodTrayCategory->id,
                'name' => 'Pasta Trays',
                'description' => 'Selection of pasta dishes',
                'serving_size' => 15,
                'status' => 'active'
            ]
        ];

        // Create all packages
        foreach ($bellychonPackages as $package) {
            AddonPackage::create($package);
        }

        foreach ($foodTrayPackages as $package) {
            AddonPackage::create($package);
        }
    }
}
