<?php

namespace Database\Seeders;

use App\Models\Package;
use App\Models\Inclusion;
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

        // Create standard inclusions first
        $standardInclusions = [
            [
                'name' => 'Rice',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Drinks',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Create inclusions and store their IDs
        $inclusionIds = collect($standardInclusions)->map(function ($inclusion) {
            return Inclusion::create($inclusion)->id;
        });

        $packages = [
            [
                'name' => 'Package A',
                'price' => 280,
                'meal_limit' => 5,
                'minimum_guests' => 50,
                'maximum_guests' => 100,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Package B',
                'price' => 310,
                'meal_limit' => 6,
                'minimum_guests' => 75,
                'maximum_guests' => 150,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Package C',
                'price' => 350,
                'meal_limit' => 5,
                'minimum_guests' => 100,
                'maximum_guests' => 200,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Package D',
                'price' => 380,
                'meal_limit' => 8,
                'minimum_guests' => 150,
                'maximum_guests' => 300,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::transaction(function () use ($packages, $inclusionIds) {
            foreach ($packages as $packageData) {
                try {
                    $package = Package::create($packageData);

                    if (!$package) {
                        throw new \Exception("Failed to create package: {$packageData['name']}");
                    }

                    // Attach all standard inclusions to the package
                    $package->inclusions()->attach($inclusionIds);
                } catch (\Exception $e) {
                    throw new \Exception("Error seeding package {$packageData['name']}: " . $e->getMessage());
                }
            }
        });
    }
}
