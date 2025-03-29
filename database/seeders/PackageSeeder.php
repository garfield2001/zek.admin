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

        $packages = [
            [
                'name' => 'Basic Package',
                'price' => 280,
                'min_guests' => 50,
                'menu_limit' => 5,
                'predefined_drinks' => true,
                'icon' => '🍽️',
                'description' => 'Perfect for medium-sized gatherings',
                'features' => [
                    [
                        'feature' => 'Minimum 50 guests',
                    ],
                    [
                        'feature' => '5 menu selections (4 customizable + 1 predefined drink)',
                    ],
                    [
                        'feature' => 'Predefined drinks selection',
                    ],
                    [
                        'feature' => 'Basic setup and service',
                    ],
                    [
                        'feature' => 'Standard tableware and linens',
                    ]
                ]
            ],
            [
                'name' => 'Standard Package',
                'price' => 350,
                'min_guests' => 50,
                'menu_limit' => 7,
                'predefined_drinks' => true,
                'icon' => '✨',
                'description' => 'Ideal for medium to large events',
                'features' => [
                    [
                        'feature' => 'Minimum 50 guests',
                    ],
                    [
                        'feature' => '7 menu selections (6 customizable + 1 predefined drink)',
                    ],
                    [
                        'feature' => 'Predefined drinks selection',
                    ],
                    [
                        'feature' => 'Premium setup and service',
                    ],
                    [
                        'feature' => 'Premium tableware and linens',
                    ]
                ]
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

        foreach ($packages as $package) {
            Package::create($package);
        }
    }
}
