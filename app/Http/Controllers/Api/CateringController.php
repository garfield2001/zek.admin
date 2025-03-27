<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CateringSetting;
use App\Models\Dish;
use App\Models\MealType;
use App\Models\Package;
use Illuminate\Http\Request;

class CateringController extends Controller
{
    public function getOverview()
    {
        $packages = Package::with('inclusions')
            ->where('status', 'active')
            ->get()
            ->map(function ($package) {
                return [
                    'name' => $package->name,
                    'price' => $package->price,
                    'meals' => $package->meal_limit,
                    'includes' => $package->inclusions->pluck('name')->toArray(),
                ];
            });

        $mealTypes = MealType::with(['dishes' => function ($query) {
            $query->where('is_available', true);
        }])
            ->get()
            ->map(function ($type) {
                return [
                    'title' => $type->name,
                    'items' => $type->dishes->pluck('name')->toArray(),
                ];
            });

        $minimumPersons = CateringSetting::get('minimum_persons', 50);

        return response()->json([
            'packages' => $packages,
            'mealCategories' => $mealTypes,
            'minimumPersons' => $minimumPersons,
        ]);
    }

    public function updateMinimumPersons(Request $request)
    {
        $request->validate([
            'value' => 'required|integer|min:1',
        ]);

        CateringSetting::set('minimum_persons', $request->value, 'integer');

        return response()->json([
            'message' => 'Minimum persons updated successfully',s
            'value' => $request->value,
        ]);
    }
}
