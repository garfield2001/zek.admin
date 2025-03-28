<?php

namespace Database\Seeders;

use App\Models\AddonCategory;
use Illuminate\Database\Seeder;

class AddonCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bellychon Package',
                'description' => 'Each tray good for 15pax',
                'status' => 'active'
            ],
            [
                'name' => 'Food Tray Menu',
                'description' => 'Each tray good for 12-15pax',
                'status' => 'active'
            ]
        ];

        foreach ($categories as $category) {
            AddonCategory::create($category);
        }
    }
}
