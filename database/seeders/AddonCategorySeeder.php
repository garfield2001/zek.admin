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
                'description' => 'Special lechon belly packages perfect for small to medium gatherings, includes complementary sides and sauces',
                'status' => 'active'
            ],
            [
                'name' => 'Food Pack Lunch',
                'description' => 'Individual packed meals ideal for corporate events, seminars, and meetings, includes main dish, rice, and sides',
                'status' => 'active'
            ],
            [
                'name' => 'Food Tray Menu',
                'description' => 'Party-sized food trays perfect for sharing at events and celebrations, available in various Filipino and international dishes',
                'status' => 'active'
            ],
            [
                'name' => 'Special Events Package',
                'description' => 'Customizable food packages for weddings, debuts, and corporate events with complete setup and service',
                'status' => 'active'
            ]
        ];

        foreach ($categories as $category) {
            AddonCategory::create($category);
        }
    }
}
