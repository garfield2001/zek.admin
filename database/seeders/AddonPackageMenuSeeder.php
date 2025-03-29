<?php

namespace Database\Seeders;

use App\Models\AddonPackage;
use Illuminate\Database\Seeder;

class AddonPackageMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Get the first addon package (Lechon Package A)
        $lechonPackage = AddonPackage::where('name', 'Package A')->first();
        if ($lechonPackage) {
            // Attach menu types (assuming IDs from MenuTypeSeeder)
            $lechonPackage->menuTypes()->attach([
                1, // Drinks
                2, // Beef
                3, // Pork
                4  // Chicken
            ]);
        }

        // Get the seafood platter package
        $seafoodPackage = AddonPackage::where('name', 'Seafood Platter')->first();
        if ($seafoodPackage) {
            $seafoodPackage->menuTypes()->attach([
                1, // Drinks
                5  // Fish & Seafoods
            ]);
        }
    }
}
