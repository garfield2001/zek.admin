<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Basic Package',
                'price' => 280,
                'meal_limit' => 5,
                'minimum_guests' => 50,
                'maximum_guests' => 300,
                'status' => 'active',
                'icon' => '🍽️',
                'description' => 'Perfect for medium-sized gatherings',
                'features' => [
                    'Minimum 50 guests',
                    '5 menu selections with drinks',
                    'Basic setup and service',
                    'Standard tableware and linens'
                ]
            ],
            [
                'name' => 'Standard Package',
                'price' => 350,
                'meal_limit' => 6,
                'minimum_guests' => 50,
                'maximum_guests' => 300,
                'status' => 'active',
                'icon' => '✨',
                'description' => 'Ideal for medium to large events',
                'features' => [
                    'Minimum 50 guests',
                    '6 menu selections with drinks',
                    'Premium setup and service',
                    'Premium tableware and linens',
                    'Basic event styling'
                ]
            ],
            [
                'name' => 'Premium Package',
                'price' => 450,
                'meal_limit' => 7,
                'minimum_guests' => 50,
                'maximum_guests' => 300,
                'status' => 'active',
                'icon' => '🌟',
                'description' => 'Perfect for large celebrations',
                'features' => [
                    'Minimum 50 guests',
                    '7 menu selections with drinks',
                    'Luxury setup and service',
                    'Premium tableware and linens',
                    'Additional service staff',
                    'Enhanced event styling'
                ]
            ],
            [
                'name' => 'Grand Package',
                'price' => 600,
                'meal_limit' => 8,
                'minimum_guests' => 50,
                'maximum_guests' => 300,
                'status' => 'active',
                'icon' => '👑',
                'description' => 'The ultimate catering experience',
                'features' => [
                    'Minimum 50 guests',
                    '8 menu selections with drinks',
                    'Luxury setup and service',
                    'Premium tableware and linens',
                    'Dedicated service staff',
                    'Custom menu planning',
                    'Complete event styling',
                    'VIP service coordination'
                ]
            ]
        ];

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
