<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Drinks (menu_type_id: 1)
            [
                'menu_type_id' => 1,
                'name' => 'Iced Tea',
                'description' => 'Classic refreshing iced tea',
                'price' => 60,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Lemonade',
                'description' => 'Fresh homemade lemonade',
                'price' => 70,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Four Seasons Juice',
                'description' => 'Mixed tropical fruit juice blend',
                'price' => 80,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Sago\'t Gulaman',
                'description' => 'Filipino brown sugar drink with tapioca pearls and gelatin',
                'price' => 70,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Buko Juice',
                'description' => 'Fresh young coconut juice',
                'price' => 75,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Pineapple Juice',
                'description' => 'Fresh pineapple juice',
                'price' => 70,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Calamansi Juice',
                'description' => 'Filipino citrus juice',
                'price' => 65,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 1,
                'name' => 'Soft Drinks',
                'description' => 'Assorted carbonated beverages',
                'price' => 55,
                'status' => 'active'
            ],
            // Beef Dishes (menu_type_id: 2)
            [
                'menu_type_id' => 2,
                'name' => 'Beef Steak Tagalog',
                'description' => 'Filipino-style beef steak with onions',
                'price' => 250,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Beef Menudo',
                'description' => 'Filipino-style beef stew with vegetables',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Beef with Broccoli',
                'description' => 'Stir-fried beef with broccoli',
                'price' => 230,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Beef Caldereta',
                'description' => 'Filipino-style beef stew in tomato sauce',
                'price' => 250,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Sizzling Beef Bulalo in Cream Sauce',
                'description' => 'Tender beef shanks in creamy sauce',
                'price' => 280,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Beef with Mushroom',
                'description' => 'Stir-fried beef with mushrooms',
                'price' => 240,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 2,
                'name' => 'Lengua Estofado',
                'description' => 'Braised ox tongue in rich sauce',
                'price' => 280,
                'status' => 'active'
            ],

            // Pork Dishes (menu_type_id: 3)
            [
                'menu_type_id' => 3,
                'name' => 'Pork Menudo',
                'description' => 'Filipino-style pork stew with vegetables',
                'price' => 200,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 3,
                'name' => 'Spicy Pork Ribs',
                'description' => 'Tender pork ribs in spicy sauce',
                'price' => 250,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 3,
                'name' => 'Pork Binagoongan',
                'description' => 'Pork with shrimp paste',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 3,
                'name' => 'Sweet and Sour Pork',
                'description' => 'Crispy pork in sweet and sour sauce',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 3,
                'name' => 'Lumpia Shanghai',
                'description' => 'Filipino-style spring rolls',
                'price' => 180,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 3,
                'name' => 'Pork Steak',
                'description' => 'Filipino-style pork steak',
                'price' => 230,
                'status' => 'active'
            ],

            // Chicken Dishes (menu_type_id: 4)
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Afritada',
                'description' => 'Chicken stewed in tomato sauce',
                'price' => 200,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Buttered Chicken',
                'description' => 'Chicken in butter sauce',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Garlic Chicken',
                'description' => 'Chicken with roasted garlic',
                'price' => 200,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Fried Chicken',
                'description' => 'Classic crispy fried chicken',
                'price' => 190,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Curry',
                'description' => 'Filipino-style chicken curry',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Teriyaki',
                'description' => 'Japanese-style chicken in teriyaki sauce',
                'price' => 230,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Buffalo Wings',
                'description' => 'Spicy buffalo-style chicken wings',
                'price' => 240,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Cordon Bleu',
                'description' => 'Breaded chicken stuffed with ham and cheese',
                'price' => 260,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 4,
                'name' => 'Chicken Ala King',
                'description' => 'Creamy chicken with vegetables',
                'price' => 240,
                'status' => 'active'
            ],

            // Fish & Seafoods (menu_type_id: 5)
            [
                'menu_type_id' => 5,
                'name' => 'Mixed Seafoods',
                'description' => 'Assorted seafood dish',
                'price' => 300,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 5,
                'name' => 'Buttered Garlic Shrimp',
                'description' => 'Shrimp in butter garlic sauce',
                'price' => 280,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 5,
                'name' => 'Sweet and Sour Fish',
                'description' => 'Fish fillet in sweet and sour sauce',
                'price' => 250,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 5,
                'name' => 'Fish Fillet with Taosi Sauce',
                'description' => 'Fish fillet in black bean sauce',
                'price' => 260,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 5,
                'name' => 'Breaded Fish Fillet with Tartar Sauce',
                'description' => 'Crispy fish fillet with tartar sauce',
                'price' => 250,
                'status' => 'active'
            ],

            // Vegetables (menu_type_id: 6)
            [
                'menu_type_id' => 6,
                'name' => 'Chopsuey with Quail Egg',
                'description' => 'Mixed vegetables with quail eggs',
                'price' => 180,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 6,
                'name' => 'Vegetable with Seafoods',
                'description' => 'Mixed vegetables with seafood',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 6,
                'name' => 'Buttered Mix Vegetables',
                'description' => 'Mixed vegetables in butter sauce',
                'price' => 160,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 6,
                'name' => 'Pinakbet',
                'description' => 'Filipino mixed vegetable dish',
                'price' => 180,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 6,
                'name' => 'Stir Fry Vegetable',
                'description' => 'Asian-style stir-fried vegetables',
                'price' => 160,
                'status' => 'active'
            ],

            // Pasta (menu_type_id: 7)
            [
                'menu_type_id' => 7,
                'name' => 'Spaghetti with Bechamel Sauce',
                'description' => 'Spaghetti in creamy white sauce',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 7,
                'name' => 'Seafood Marinara',
                'description' => 'Pasta with mixed seafood in tomato sauce',
                'price' => 260,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 7,
                'name' => 'Creamy Carbonara',
                'description' => 'Classic creamy carbonara pasta',
                'price' => 240,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 7,
                'name' => 'Baked Macaroni',
                'description' => 'Baked macaroni in meat sauce',
                'price' => 220,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 7,
                'name' => 'Baked Lasagna',
                'description' => 'Classic baked lasagna',
                'price' => 260,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 7,
                'name' => 'Baked Spaghetti',
                'description' => 'Baked spaghetti in meat sauce',
                'price' => 240,
                'status' => 'active'
            ],

            // Noodles (menu_type_id: 8)
            [
                'menu_type_id' => 8,
                'name' => 'Sotanghon Guisado',
                'description' => 'Stir-fried glass noodles',
                'price' => 200,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 8,
                'name' => 'Pancit Canton',
                'description' => 'Filipino-style canton noodles',
                'price' => 200,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 8,
                'name' => 'Bam-E',
                'description' => 'Cebuano-style mixed noodles',
                'price' => 220,
                'status' => 'active'
            ],

            // Dessert (menu_type_id: 9)
            [
                'menu_type_id' => 9,
                'name' => 'Fresh Mix Fruits',
                'description' => 'Assorted fresh fruit platter',
                'price' => 150,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 9,
                'name' => 'Mango Sago',
                'description' => 'Mango cream with sago pearls',
                'price' => 120,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 9,
                'name' => 'Buko Salad',
                'description' => 'Young coconut fruit salad',
                'price' => 130,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 9,
                'name' => 'Buko Pandan',
                'description' => 'Coconut pandan cream dessert',
                'price' => 130,
                'status' => 'active'
            ],
            [
                'menu_type_id' => 9,
                'name' => 'Macaroni Salad',
                'description' => 'Sweet macaroni dessert salad',
                'price' => 140,
                'status' => 'active'
            ]
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
