<?php

namespace Database\Seeders;

use App\Models\AddonPackage;
use App\Models\AddonPackageMenu;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class AddonPackageMenuSeeder extends Seeder
{
    public function run(): void
    {
        // Get packages
        $packageA = AddonPackage::where('name', 'Package A')->first();
        $packageB = AddonPackage::where('name', 'Package B')->first();
        $packageC = AddonPackage::where('name', 'Package C')->first();
        $beefPackage = AddonPackage::where('name', 'Beef Trays')->first();
        $seafoodPackage = AddonPackage::where('name', 'Seafoods Trays')->first();
        $pastaPackage = AddonPackage::where('name', 'Pasta Trays')->first();

        // Bellychon Package A Menus
        $this->createBellychonPackageMenus($packageA, 3);

        // Bellychon Package B Menus (with Beef Steak)
        $this->createBellychonPackageMenus($packageB, 3, true);

        // Bellychon Package C Menus (with Beef Steak)
        $this->createBellychonPackageMenus($packageC, 5, true);

        // Food Tray Menus - Beef
        $beefMenus = [
            ['name' => 'BISTEK TAGALOG', 'price' => 1100],
            ['name' => 'BEEF CALDERETA', 'price' => 1100],
            ['name' => 'BEEF WITH BROCCOLI', 'price' => 1100],
            ['name' => 'BEEF WITH OYSTER', 'price' => 1100],
            ['name' => 'BEEF KARE-KARE', 'price' => 1100],
            ['name' => 'BEEF MENUDO', 'price' => 1100],
            ['name' => 'YAKINIKU BEEF', 'price' => 1100],
            ['name' => 'BEEF WITH MUSHROOM', 'price' => 1100],
            ['name' => 'BEEF SALPICAO', 'price' => 1100],
            ['name' => 'OSSO BUCCO', 'price' => 1100],
            ['name' => 'LENGUA IN MUSHROOM SAUCE', 'price' => 1100],
            ['name' => 'OXTAIL KARE-KARE', 'price' => 1100],
            ['name' => 'BEEF STROGANOFF', 'price' => 1100],
            ['name' => 'SIZZLING BEEF BULALO', 'price' => 1100],
            ['name' => 'CALLOS', 'price' => 1100],
        ];

        foreach ($beefMenus as $menuData) {
            $menu = Menu::where('name', $menuData['name'])->first();
            if ($menu) {
                AddonPackageMenu::create([
                    'package_id' => $beefPackage->id,
                    'menu_id' => $menu->id,
                    'price' => $menuData['price']
                ]);
            }
        }

        // Food Tray Menus - Seafood
        $seafoodMenus = [
            ['name' => 'SEAFOODS PAELLA', 'price' => 1100],
            ['name' => 'THAI SPICY SQUID', 'price' => 1100],
            ['name' => 'MIXED SEAFOODS', 'price' => 1100],
            ['name' => 'BUTTERED GARLIC SHRIMP', 'price' => 1100],
            ['name' => 'CALAMARI', 'price' => 1100],
            ['name' => 'SWEET AND SOUR FISH MALASUGI', 'price' => 1100],
            ['name' => 'BREADED FISH FILLET WITH TARTAR SAUCE', 'price' => 1100],
            ['name' => 'FISH FILLET WITH TOFU AND TAOSI SAUCE', 'price' => 1100],
            ['name' => 'KINILAW MALASUGI', 'price' => 1100],
            ['name' => 'RELLENONG PUSIT', 'price' => 1100],
            ['name' => 'STEAMED POMPANO', 'price' => 1100],
            ['name' => 'GAMBAS', 'price' => 1100],
        ];

        foreach ($seafoodMenus as $menuData) {
            $menu = Menu::where('name', $menuData['name'])->first();
            if ($menu) {
                AddonPackageMenu::create([
                    'package_id' => $seafoodPackage->id,
                    'menu_id' => $menu->id,
                    'price' => $menuData['price']
                ]);
            }
        }

        // Food Tray Menus - Pasta
        $pastaMenus = [
            ['name' => 'SPAGHETTI WITH BECHAMEL SAUCE', 'price' => 800],
            ['name' => 'SEAFOOD MARINARA', 'price' => 800],
            ['name' => 'CREAMY CARBONARA', 'price' => 900],
        ];

        foreach ($pastaMenus as $menuData) {
            $menu = Menu::where('name', $menuData['name'])->first();
            if ($menu) {
                AddonPackageMenu::create([
                    'package_id' => $pastaPackage->id,
                    'menu_id' => $menu->id,
                    'price' => $menuData['price']
                ]);
            }
        }
    }

    private function createBellychonPackageMenus(AddonPackage $package, int $lechonWeight, bool $includeBeefSteak = false): void
    {
        // Fixed Lechon Belly
        $lechonBelly = Menu::where('name', 'LECHON BELLY')->first();
        if ($lechonBelly) {
            AddonPackageMenu::create([
                'package_id' => $package->id,
                'menu_id' => $lechonBelly->id,
                'is_fixed' => true,
                'can_change' => false,
                'quantity' => $lechonWeight
            ]);
        }

        // Changeable sides
        $sideMenus = [
            'GARLIC CHICKEN',
            'SOTANGHON GUISADO',
            'LUMPIA SHANGHAI'
        ];

        if ($includeBeefSteak) {
            $sideMenus[] = 'BEEF STEAK';
        }

        foreach ($sideMenus as $menuName) {
            $menu = Menu::where('name', $menuName)->first();
            if ($menu) {
                AddonPackageMenu::create([
                    'package_id' => $package->id,
                    'menu_id' => $menu->id,
                    'is_fixed' => false,
                    'can_change' => true
                ]);
            }
        }
    }
}
