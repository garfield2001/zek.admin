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
        $fishAndSeafoodType = MealType::where('name', 'Fish & Seafoods')->first();
        $vegetableType = MealType::where('name', 'Vegetables')->first();
        $pastaType = MealType::where('name', 'Pasta')->first();
        $noodlesType = MealType::where('name', 'Noodles')->first();
        $dessertType = MealType::where('name', 'Dessert')->first();

        if (
            !$beefType || !$porkType || !$chickenType || !$fishAndSeafoodType ||
            !$vegetableType || !$pastaType || !$noodlesType || !$dessertType
        ) {
            throw new \Exception('Required meal types not found. Please run MealTypeSeeder first.');
        }

        $now = Carbon::now();

        // Beef Dishes
        $beefDishes = [
            [
                'name' => 'BEEF STEAK TAGALOG',
                'description' => 'Filipino-style beef steak with onions in soy-calamansi sauce',
                'price' => 270.00,
                'image_url' => 'https://example.com/images/beef-steak-tagalog.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BEEF MENUDO',
                'description' => 'Diced beef with liver and vegetables in tomato sauce',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/beef-menudo.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BEEF WITH BROCCOLI',
                'description' => 'Stir-fried beef slices with fresh broccoli in oyster sauce',
                'price' => 260.00,
                'image_url' => 'https://example.com/images/beef-broccoli.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BEEF CALDERETA',
                'description' => 'Rich and spicy beef stew with potatoes and bell peppers',
                'price' => 280.00,
                'image_url' => 'https://example.com/images/beef-caldereta.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SIZZLING BEEF BULALO IN CREAM SAUCE',
                'description' => 'Tender beef shanks in creamy mushroom sauce',
                'price' => 300.00,
                'image_url' => 'https://example.com/images/sizzling-bulalo.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BEEF WITH MUSHROOM',
                'description' => 'Tender beef slices with assorted mushrooms',
                'price' => 270.00,
                'image_url' => 'https://example.com/images/beef-mushroom.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'LENGUA ESTOFADO',
                'description' => 'Braised ox tongue in rich tomato sauce',
                'price' => 290.00,
                'image_url' => 'https://example.com/images/lengua-estofado.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Pork Dishes
        $porkDishes = [
            [
                'name' => 'PORK MENUDO',
                'description' => 'Diced pork with liver and vegetables in tomato sauce',
                'price' => 240.00,
                'image_url' => 'https://example.com/images/pork-menudo.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SPICY PORK RIBS',
                'description' => 'Tender pork ribs in spicy barbecue sauce',
                'price' => 260.00,
                'image_url' => 'https://example.com/images/spicy-pork-ribs.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'PORK BINAGOONGAN',
                'description' => 'Crispy pork belly with shrimp paste',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/binagoongan.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SWEET AND SOUR PORK',
                'description' => 'Crispy pork in sweet and sour sauce with pineapple',
                'price' => 240.00,
                'image_url' => 'https://example.com/images/sweet-sour-pork.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'LUMPIA SHANGHAI',
                'description' => 'Crispy spring rolls with pork and vegetables',
                'price' => 200.00,
                'image_url' => 'https://example.com/images/lumpia-shanghai.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'PORK STEAK',
                'description' => 'Grilled pork chops in mushroom gravy',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/pork-steak.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Chicken Dishes
        $chickenDishes = [
            [
                'name' => 'CHICKEN AFRITADA',
                'description' => 'Chicken stewed in tomato sauce with vegetables',
                'price' => 230.00,
                'image_url' => 'https://example.com/images/chicken-afritada.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BUTTERED CHICKEN',
                'description' => 'Chicken pieces in rich butter garlic sauce',
                'price' => 240.00,
                'image_url' => 'https://example.com/images/buttered-chicken.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'GARLIC CHICKEN',
                'description' => 'Crispy chicken with roasted garlic',
                'price' => 230.00,
                'image_url' => 'https://example.com/images/garlic-chicken.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'FRIED CHICKEN',
                'description' => 'Classic crispy fried chicken',
                'price' => 220.00,
                'image_url' => 'https://example.com/images/fried-chicken.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CHICKEN CURRY',
                'description' => 'Chicken in coconut curry sauce with vegetables',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/chicken-curry.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CHICKEN TERIYAKI',
                'description' => 'Grilled chicken in sweet teriyaki sauce',
                'price' => 240.00,
                'image_url' => 'https://example.com/images/chicken-teriyaki.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CHICKEN BUFFALO',
                'description' => 'Crispy chicken in spicy buffalo sauce',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/buffalo-wings.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CHICKEN CORDON BLEU',
                'description' => 'Breaded chicken stuffed with ham and cheese',
                'price' => 270.00,
                'image_url' => 'https://example.com/images/cordon-bleu.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CHICKEN ALA KING',
                'description' => 'Creamy chicken with bell peppers and mushrooms',
                'price' => 260.00,
                'image_url' => 'https://example.com/images/chicken-ala-king.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Fish & Seafoods Dishes
        $fishAndSeafoodDishes = [
            [
                'name' => 'MIXED SEAFOODS',
                'description' => 'Assorted seafood in garlic butter sauce',
                'price' => 280.00,
                'image_url' => 'https://example.com/images/mixed-seafood.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BUTTERED GARLIC SHRIMP',
                'description' => 'Shrimp sautéed in butter and garlic',
                'price' => 290.00,
                'image_url' => 'https://example.com/images/garlic-shrimp.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SWEET AND SOUR FISH',
                'description' => 'Fried fish fillet in sweet and sour sauce',
                'price' => 260.00,
                'image_url' => 'https://example.com/images/sweet-sour-fish.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'FISH FILLET WITH TAOSI SAUCE',
                'description' => 'Fish fillet in black bean sauce',
                'price' => 270.00,
                'image_url' => 'https://example.com/images/fish-taosi.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BREADED FISH FILLET WITH TARTAR SAUCE',
                'description' => 'Crispy breaded fish with homemade tartar sauce',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/breaded-fish.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Vegetables Dishes
        $vegetableDishes = [
            [
                'name' => 'CHOPSUEY WITH QUAIL EGG',
                'description' => 'Mixed vegetables with quail eggs in oyster sauce',
                'price' => 220.00,
                'image_url' => 'https://example.com/images/chopsuey.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'VEGETABLE WITH SEAFOODS',
                'description' => 'Mixed vegetables with assorted seafood',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/vegetable-seafood.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BUTTERED MIX VEGETABLES',
                'description' => 'Assorted vegetables in butter sauce',
                'price' => 200.00,
                'image_url' => 'https://example.com/images/buttered-vegetables.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'PINAKBET',
                'description' => 'Mixed vegetables with shrimp paste',
                'price' => 210.00,
                'image_url' => 'https://example.com/images/pinakbet.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'STIR FRY VEGETABLE',
                'description' => 'Fresh vegetables in light garlic sauce',
                'price' => 190.00,
                'image_url' => 'https://example.com/images/stir-fry-veg.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Pasta Dishes
        $pastaDishes = [
            [
                'name' => 'SPAGHETTI WITH BECHAMEL SAUCE',
                'description' => 'Creamy white sauce pasta with mushrooms',
                'price' => 230.00,
                'image_url' => 'https://example.com/images/bechamel-pasta.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'SEAFOOD MARINARA',
                'description' => 'Mixed seafood in rich tomato sauce',
                'price' => 260.00,
                'image_url' => 'https://example.com/images/seafood-marinara.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'CREAMY CARBONARA',
                'description' => 'Classic carbonara with bacon and parmesan',
                'price' => 240.00,
                'image_url' => 'https://example.com/images/carbonara.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BAKED MACARONI',
                'description' => 'Baked pasta in meat sauce and cheese',
                'price' => 220.00,
                'image_url' => 'https://example.com/images/baked-macaroni.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BAKED LASAGNA',
                'description' => 'Layered pasta with meat sauce and cheese',
                'price' => 250.00,
                'image_url' => 'https://example.com/images/lasagna.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BAKED SPAGHETTI',
                'description' => 'Baked spaghetti in meat sauce and cheese',
                'price' => 230.00,
                'image_url' => 'https://example.com/images/baked-spaghetti.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Noodles Dishes
        $noodlesDishes = [
            [
                'name' => 'SOTANGHON GUISADO',
                'description' => 'Stir-fried glass noodles with vegetables',
                'price' => 200.00,
                'image_url' => 'https://example.com/images/sotanghon.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'PANCIT CANTON',
                'description' => 'Stir-fried egg noodles with meat and vegetables',
                'price' => 210.00,
                'image_url' => 'https://example.com/images/pancit-canton.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BAM-E',
                'description' => 'Mixed noodles with seafood and vegetables',
                'price' => 220.00,
                'image_url' => 'https://example.com/images/bam-e.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Dessert Dishes
        $dessertDishes = [
            [
                'name' => 'FRESH MIX FRUITS',
                'description' => 'Assorted seasonal fresh fruits',
                'price' => 150.00,
                'image_url' => 'https://example.com/images/fresh-fruits.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'MANGO SAGO',
                'description' => 'Fresh mango with sago pearls and cream',
                'price' => 160.00,
                'image_url' => 'https://example.com/images/mango-sago.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BUKO SALAD',
                'description' => 'Young coconut strips with mixed fruits',
                'price' => 170.00,
                'image_url' => 'https://example.com/images/buko-salad.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'BUKO PANDAN',
                'description' => 'Young coconut with pandan flavored cream',
                'price' => 160.00,
                'image_url' => 'https://example.com/images/buko-pandan.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'MACARONI SALAD',
                'description' => 'Sweet macaroni salad with fruits',
                'price' => 150.00,
                'image_url' => 'https://example.com/images/macaroni-salad.jpg',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        // Create dishes for each type
        $dishTypes = [
            [$beefType->id, $beefDishes],
            [$porkType->id, $porkDishes],
            [$chickenType->id, $chickenDishes],
            [$fishAndSeafoodType->id, $fishAndSeafoodDishes],
            [$vegetableType->id, $vegetableDishes],
            [$pastaType->id, $pastaDishes],
            [$noodlesType->id, $noodlesDishes],
            [$dessertType->id, $dessertDishes],
        ];

        foreach ($dishTypes as [$typeId, $dishes]) {
            foreach ($dishes as $dish) {
                $dish['meal_type_id'] = $typeId;
                $dish['is_available'] = true;
                Dish::create($dish);
            }
        }
    }
}
