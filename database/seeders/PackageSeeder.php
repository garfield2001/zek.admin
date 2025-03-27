<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\PackageInclusion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        $packages = [
            [
                'name' => 'Basic Package',
                'price' => 299.99,
                'meal_limit' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Premium Package',
                'price' => 399.99,
                'meal_limit' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Deluxe Package',
                'price' => 499.99,
                'meal_limit' => 5,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Standard inclusions for all packages
        $standardInclusions = [
            [
                'name' => 'Rice',
                'description' => 'Unlimited steamed white rice',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Drinks',
                'description' => 'Unlimited iced tea',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($packages as $packageData) {
            try {
                $package = Package::create($packageData);

                if (!$package) {
                    throw new \Exception("Failed to create package: {$packageData['name']}");
                }

                // Add standard inclusions to each package
                foreach ($standardInclusions as $inclusion) {
                    $inclusion['package_id'] = $package->id;
                    PackageInclusion::create($inclusion);
                }
            } catch (\Exception $e) {
                throw new \Exception("Error seeding package {$packageData['name']}: " . $e->getMessage());
            }
        }
    }
}
