<?php

namespace Database\Seeders;

use App\Models\MealType;
use Illuminate\Database\Seeder;

class MealTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mealTypes = [
            [
                'name' => 'Beef',
                'description' => 'Delicious beef dishes perfect for any occasion',
            ],
            [
                'name' => 'Pork',
                'description' => 'Savory pork dishes that everyone will love',
            ],
            [
                'name' => 'Chicken',
                'description' => 'Tender chicken dishes for all preferences',
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh seafood dishes for a unique dining experience',
            ],
            [
                'name' => 'Vegetable',
                'description' => 'Healthy and flavorful vegetable dishes',
            ],
        ];

        foreach ($mealTypes as $mealType) {
            MealType::create($mealType);
        }
    }
}
