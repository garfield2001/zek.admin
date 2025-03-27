<?php

namespace Database\Seeders;

use App\Models\MenuType;
use Illuminate\Database\Seeder;

class MenuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuTypes = [
            [
                'name' => 'Beef',
                'description' => 'Premium beef dishes expertly prepared with rich flavors and tender textures',
            ],
            [
                'name' => 'Pork',
                'description' => 'Succulent pork dishes crafted with traditional and modern cooking techniques',
            ],
            [
                'name' => 'Chicken',
                'description' => 'Versatile chicken dishes ranging from classic favorites to gourmet preparations',
            ],
            [
                'name' => 'Fish & Seafoods',
                'description' => 'Fresh seafood selections featuring fish, shrimp, and other marine delicacies',
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Nutritious vegetable dishes prepared to maintain freshness and natural flavors',
            ],
            [
                'name' => 'Pasta',
                'description' => 'Italian-inspired pasta dishes with rich sauces and premium ingredients',
            ],
            [
                'name' => 'Noodles',
                'description' => 'Asian-style noodle dishes featuring various cooking styles and flavors',
            ],
            [
                'name' => 'Dessert',
                'description' => 'Sweet endings to your meal with traditional Filipino and international desserts',
            ],
        ];

        foreach ($menuTypes as $menuType) {
            MenuType::create($menuType);
        }
    }
}
