<?php

namespace Database\Seeders;

use App\Models\MenuType;
use Illuminate\Database\Seeder;

class MenuTypeSeeder extends Seeder
{
    public function run(): void
    {
        $menuTypes = [
            [
                'name' => 'Drinks',
                'description' => 'Beverages and refreshments'
            ],
            [
                'name' => 'Beef',
                'description' => 'Beef dishes'
            ],
            [
                'name' => 'Pork',
                'description' => 'Pork dishes'
            ],
            [
                'name' => 'Chicken',
                'description' => 'Chicken dishes'
            ],
            [
                'name' => 'Fish & Seafoods',
                'description' => 'Seafood dishes'
            ],
            [
                'name' => 'Vegetables',
                'description' => 'Vegetable dishes'
            ],
            [
                'name' => 'Pasta',
                'description' => 'Pasta dishes'
            ],
            [
                'name' => 'Noodles',
                'description' => 'Asian noodle dishes'
            ],
            [
                'name' => 'Dessert',
                'description' => 'Sweet treats and desserts'
            ]
        ];

        foreach ($menuTypes as $menuType) {
            MenuType::create($menuType);
        }
    }
}
