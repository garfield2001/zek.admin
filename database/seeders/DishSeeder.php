<?php

namespace Database\Seeders;

use App\Models\Dish;
use App\Models\MealType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DishSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get meal types
        $beefType = MealType::where('name', 'Beef')->first();
        $porkType = MealType::where('name', 'Pork')->first();
        $chickenType = MealType::where('name', 'Chicken')->first();
        $seafoodType = MealType::where('name', 'Seafood')->first();
        $vegetableType = MealType::where('name', 'Vegetable')->first();

        if (!$beefType || !$porkType || !$chickenType || !$seafoodType || !$vegetableType) {
            throw new \Exception('Required meal types not found. Please run MealTypeSeeder first.');
        }

        $now = Carbon::now();

        // Beef Dishes
        $beefDishes = [
            [
                'name' => 'Beef Caldereta',
                'description' => 'Filipino beef stew with rich tomato sauce and vegetables',
                'price' => 250.00,
                'image_url' => 'https://images.unsplash.com/photo-1635321350281-e7330c94960b?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Beef Mechado',
                'description' => 'Tender beef braised in tomato sauce with potatoes and carrots',
                'price' => 230.00,
                'image_url' => 'https://images.unsplash.com/photo-1633237308525-cd587cf71926?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Beef Steak',
                'description' => 'Filipino-style beef steak with onions in soy-calamansi sauce',
                'price' => 270.00,
                'image_url' => 'https://images.unsplash.com/photo-1615937691194-97dbd3f3dc29?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Pork Dishes
        $porkDishes = [
            [
                'name' => 'Lechon Kawali',
                'description' => 'Crispy deep-fried pork belly served with liver sauce',
                'price' => 220.00,
                'image_url' => 'https://images.unsplash.com/photo-1619221882220-947b3d3c8861?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pork Adobo',
                'description' => 'Classic Filipino pork adobo in soy sauce and vinegar',
                'price' => 200.00,
                'image_url' => 'https://images.unsplash.com/photo-1623238913973-21e45cced554?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Pork Sinigang',
                'description' => 'Sour soup with pork and vegetables',
                'price' => 210.00,
                'image_url' => 'https://images.unsplash.com/photo-1623239803865-fc8630b9dd0b?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Chicken Dishes
        $chickenDishes = [
            [
                'name' => 'Chicken Inasal',
                'description' => 'Grilled chicken marinated in annatto oil and spices',
                'price' => 180.00,
                'image_url' => 'https://images.unsplash.com/photo-1598515214211-89d3c73ae83b?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chicken Afritada',
                'description' => 'Chicken stewed in tomato sauce with vegetables',
                'price' => 190.00,
                'image_url' => 'https://images.unsplash.com/photo-1567529692333-de9fd6772897?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chicken Tinola',
                'description' => 'Ginger-based chicken soup with green papaya',
                'price' => 170.00,
                'image_url' => 'https://images.unsplash.com/photo-1562967916-eb82221dfb92?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Seafood Dishes
        $seafoodDishes = [
            [
                'name' => 'Sinigang na Hipon',
                'description' => 'Sour and savory shrimp soup with vegetables',
                'price' => 260.00,
                'image_url' => 'https://images.unsplash.com/photo-1625943553852-781c6dd46faa?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Inihaw na Pusit',
                'description' => 'Grilled squid with calamansi and soy sauce',
                'price' => 240.00,
                'image_url' => 'https://images.unsplash.com/photo-1600699626575-36d423c6d5f5?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Sweet & Sour Fish',
                'description' => 'Deep-fried fish fillet in sweet and sour sauce',
                'price' => 230.00,
                'image_url' => 'https://images.unsplash.com/photo-1626645738196-c2a7c87a8f58?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Vegetable Dishes
        $vegetableDishes = [
            [
                'name' => 'Pinakbet',
                'description' => 'Mixed vegetables with shrimp paste',
                'price' => 160.00,
                'image_url' => 'https://images.unsplash.com/photo-1627662168223-7df99068099a?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Chopsuey',
                'description' => 'Stir-fried mixed vegetables in oyster sauce',
                'price' => 150.00,
                'image_url' => 'https://images.unsplash.com/photo-1625944525903-a8f18716ee7d?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Gising Gising',
                'description' => 'Spicy mixed vegetables in coconut milk',
                'price' => 170.00,
                'image_url' => 'https://images.unsplash.com/photo-1627662168483-2b8fcb0c3319?auto=format&fit=crop&q=80&w=800',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Create dishes for each type
        foreach ($beefDishes as $dish) {
            $dish['meal_type_id'] = $beefType->id;
            $dish['is_available'] = true;
            Dish::create($dish);
        }

        foreach ($porkDishes as $dish) {
            $dish['meal_type_id'] = $porkType->id;
            $dish['is_available'] = true;
            Dish::create($dish);
        }

        foreach ($chickenDishes as $dish) {
            $dish['meal_type_id'] = $chickenType->id;
            $dish['is_available'] = true;
            Dish::create($dish);
        }

        foreach ($seafoodDishes as $dish) {
            $dish['meal_type_id'] = $seafoodType->id;
            $dish['is_available'] = true;
            Dish::create($dish);
        }

        foreach ($vegetableDishes as $dish) {
            $dish['meal_type_id'] = $vegetableType->id;
            $dish['is_available'] = true;
            Dish::create($dish);
        }
    }
}
